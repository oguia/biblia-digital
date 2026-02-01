import { app, BrowserWindow, BrowserView, ipcMain, session } from 'electron';
import path from 'path';
// @ts-ignore
import Store from 'electron-store';

interface StoreType {
  homePage: string;
}

const store: any = new Store({
  defaults: {
    homePage: 'https://www.google.com'
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

ipcMain.handle('create-tab', async (_, url?: string) => {
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
  return id;
});

ipcMain.handle('get-settings', () => {
  return {
    homePage: store.get('homePage')
  };
});

ipcMain.handle('save-settings', (_, settings: Partial<StoreType>) => {
  if (settings.homePage) {
    store.set('homePage', settings.homePage);
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
  if (mainWindow) {
    console.log('Clearing session data...');
    await session.defaultSession.clearStorageData();
    console.log('Session data cleared.');
  }
  app.exit(0);
});
