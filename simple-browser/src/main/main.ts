import { app, BrowserWindow, BrowserView, ipcMain, session, Menu, MenuItem } from 'electron';
import path from 'path';
import Store from 'electron-store';

interface StoreType {
  homePage: string;
  restoreSession: boolean;
  lastSessionTabs: string[];
}

const store = new Store<StoreType>({
  defaults: {
    homePage: 'https://www.google.com',
    restoreSession: false,
    lastSessionTabs: []
  }
});

let mainWindow: BrowserWindow | null = null;
const tabs = new Map<string, BrowserView>();
let activeTabId: string | null = null;

// Simple AdBlock List (Example)
const AD_DOMAINS = [
  '*://*.doubleclick.net/*',
  '*://*.googleadservices.com/*',
  '*://*.googlesyndication.com/*',
  '*://*.moatads.com/*',
  '*://*.facebook.com/tr/*',
];

function setupAdBlocker(sess: Electron.Session) {
  sess.webRequest.onBeforeRequest({ urls: ['<all_urls>'] }, (details, callback) => {
    const isAd = AD_DOMAINS.some(pattern => {
      // Very basic glob matching
      const regex = new RegExp(pattern.replace(/\*/g, '.*'));
      return regex.test(details.url);
    });

    if (isAd) {
      console.log(`Blocked Ad: ${details.url}`);
      callback({ cancel: true });
    } else {
      callback({ cancel: false });
    }
  });
}

function createMainWindow() {
  mainWindow = new BrowserWindow({
    width: 1200,
    height: 800,
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
      nodeIntegration: false,
      contextIsolation: true,
    },
    title: 'Simple Secure Browser'
  });

  // Load the React App (Renderer)
  if (process.env.NODE_ENV === 'development') {
    mainWindow.loadURL('http://localhost:5173');
  } else {
    mainWindow.loadFile(path.join(__dirname, '../renderer/index.html'));
  }

  setupAdBlocker(session.defaultSession);

  // Restore Session or Open Home Page
  mainWindow.webContents.once('did-finish-load', async () => {
    if (store.get('restoreSession')) {
      const lastTabs = store.get('lastSessionTabs');
      if (lastTabs && lastTabs.length > 0) {
        // Create tab for each saved URL
        // We need to wait a bit or just loop
        for (const url of lastTabs) {
          // Use our internal handler via a mock event or just call logic
          // But create-tab is an IPC handle. We can call the logic directly if we extract it,
          // or we can simulate it by calling the implementation.
          // Better: Extract tab creation logic or just use the IPC handler from here?
          // Actually, we can't call ipcMain.handle directly easily.
          // Let's refactor createTab logic to a function.
          await createTabInternal(url);
        }
        return;
      }
    }
    // Default behavior if no session to restore
    createTabInternal(store.get('homePage'));
  });

  mainWindow.on('resize', updateActiveViewBounds);
  mainWindow.on('maximize', updateActiveViewBounds);
  mainWindow.on('unmaximize', updateActiveViewBounds);
}

function updateActiveViewBounds() {
  if (!mainWindow || !activeTabId) return;
  const view = tabs.get(activeTabId);
  if (view) {
    const bounds = mainWindow.getContentBounds();
    // Offset for the top bar (Tabs + Address Bar). Let's assume 80px height.
    view.setBounds({ x: 0, y: 80, width: bounds.width, height: bounds.height - 80 });
  }
}

async function createTabInternal(url?: string) {
  if (!mainWindow) return;

  const targetUrl = url || store.get('homePage');
  const id = Math.random().toString(36).substring(7);
  const view = new BrowserView({
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      sandbox: true, // Security: Sandbox the content
    }
  });

  // Set User Agent to avoid "Download App" banners and improve compatibility
  view.webContents.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

  tabs.set(id, view);

  // Setup events
  view.webContents.on('did-start-loading', () => {
    mainWindow?.webContents.send('loading-change', id, true);
  });

  // Context Menu for Translation
  view.webContents.on('context-menu', (event, params) => {
    const menu = new Menu();

    menu.append(new MenuItem({
      label: 'Traduzir para Português',
      click: () => {
        const currentUrl = view.webContents.getURL();
        if (currentUrl && !currentUrl.includes('translate.google')) {
          const translateUrl = `https://translate.google.com/translate?sl=auto&tl=pt&u=${encodeURIComponent(currentUrl)}`;
          view.webContents.loadURL(translateUrl);
        }
      }
    }));

    menu.append(new MenuItem({ type: 'separator' }));
    menu.append(new MenuItem({ role: 'copy', label: 'Copiar' }));
    menu.append(new MenuItem({ role: 'cut', label: 'Recortar' }));
    menu.append(new MenuItem({ role: 'paste', label: 'Colar' }));

    menu.popup();
  });

  view.webContents.on('did-finish-load', () => {
    // Hide "Download Chrome" banners on Google
    view.webContents.insertCSS(`
      div[aria-label="Fazer o download do Chrome"],
      .gb_Fa,
      #gb .gb_ld { display: none !important; }
    `);

    mainWindow?.webContents.send('loading-change', id, false);
    mainWindow?.webContents.send('title-change', id, view.webContents.getTitle());
    mainWindow?.webContents.send('url-change', id, view.webContents.getURL());
  });
  view.webContents.on('did-fail-load', () => {
    mainWindow?.webContents.send('loading-change', id, false);
  });
  view.webContents.setWindowOpenHandler(({ url }) => {
    // Open new windows in new tabs instead
    // But since we are in the main process, we can't easily trigger 'create-tab' via IPC handler recursion easily without refactoring.
    // For simplicity, we allow external browser or deny.
    // Ideally: Tell renderer to create a new tab.
    mainWindow?.webContents.send('request-new-tab', url);
    return { action: 'deny' };
  });

  // Ensure targetUrl is a string
  const urlToLoad = typeof targetUrl === 'string' ? targetUrl : 'https://www.google.com';
  await view.webContents.loadURL(urlToLoad);

  // Notify Renderer that a tab was created (crucial for initial load restoration)
  mainWindow.webContents.send('tab-created', { id, url: urlToLoad });

  return id;
}

ipcMain.handle('create-tab', async (_, url?: string) => {
  return await createTabInternal(url);
});

ipcMain.handle('get-settings', () => {
  return {
    homePage: store.get('homePage'),
    restoreSession: store.get('restoreSession')
  };
});

ipcMain.handle('save-settings', (_, settings: Partial<StoreType>) => {
  if (settings.homePage !== undefined) {
    store.set('homePage', settings.homePage);
  }
  if (settings.restoreSession !== undefined) {
    store.set('restoreSession', settings.restoreSession);
  }
});

ipcMain.handle('switch-tab', (_, id: string) => {
  if (!mainWindow) return;
  const view = tabs.get(id);
  if (!view) return;

  if (activeTabId && tabs.has(activeTabId)) {
    mainWindow.removeBrowserView(tabs.get(activeTabId)!);
  }

  activeTabId = id;
  mainWindow.setBrowserView(view);
  updateActiveViewBounds();

  // Send current state to UI
  return {
    url: view.webContents.getURL(),
    title: view.webContents.getTitle()
  };
});

ipcMain.handle('close-tab', (_, id: string) => {
  const view = tabs.get(id);
  if (view) {
    if (activeTabId === id) {
      mainWindow?.removeBrowserView(view);
      activeTabId = null;
    }
    // view.webContents.destroy(); // Optional, but BrowserView cleanup usually enough
    tabs.delete(id);
  }
});

ipcMain.handle('navigate', (_, id: string, url: string) => {
  const view = tabs.get(id);
  if (view) {
    // Force HTTPS if not specified, unless localhost
    if (!url.startsWith('http')) {
        url = 'https://' + url;
    }
    view.webContents.loadURL(url);
  }
});

ipcMain.handle('reload', (_, id: string) => {
  tabs.get(id)?.webContents.reload();
});

ipcMain.handle('go-back', (_, id: string) => {
  tabs.get(id)?.webContents.goBack();
});

ipcMain.handle('go-forward', (_, id: string) => {
  tabs.get(id)?.webContents.goForward();
});

app.whenReady().then(createMainWindow);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

// Security: Clear data on exit
app.on('before-quit', async (e) => {
  e.preventDefault();

  // Save Session if enabled
  if (store.get('restoreSession')) {
    const urls: string[] = [];
    tabs.forEach((view) => {
      urls.push(view.webContents.getURL());
    });
    store.set('lastSessionTabs', urls);
    console.log('Session saved:', urls);
  } else {
    store.set('lastSessionTabs', []);
  }

  if (mainWindow) {
    console.log('Clearing session data...');
    // We clear cookies/cache for security, but we kept the URLs to restore them
    await session.defaultSession.clearStorageData();
    console.log('Session data cleared.');
  }
  app.exit(0);
});
