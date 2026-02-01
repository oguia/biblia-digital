import { contextBridge, ipcRenderer } from 'electron';

contextBridge.exposeInMainWorld('browserAPI', {
  createTab: (url?: string) => ipcRenderer.invoke('create-tab', url),
  closeTab: (id: string) => ipcRenderer.invoke('close-tab', id),
  switchTab: (id: string) => ipcRenderer.invoke('switch-tab', id),
  navigate: (id: string, url: string) => ipcRenderer.invoke('navigate', id, url),
  reload: (id: string) => ipcRenderer.invoke('reload', id),
  goBack: (id: string) => ipcRenderer.invoke('go-back', id),
  goForward: (id: string) => ipcRenderer.invoke('go-forward', id),
  onUrlChange: (callback: (id: string, url: string) => void) => {
    ipcRenderer.on('url-change', (_, id, url) => callback(id, url));
  },
  onTitleChange: (callback: (id: string, title: string) => void) => {
    ipcRenderer.on('title-change', (_, id, title) => callback(id, title));
  },
  onFaviconChange: (callback: (id: string, favicon: string) => void) => {
    ipcRenderer.on('favicon-change', (_, id, favicon) => callback(id, favicon));
  },
  onLoading: (callback: (id: string, isLoading: boolean) => void) => {
    ipcRenderer.on('loading-change', (_, id, isLoading) => callback(id, isLoading));
  },
  onRequestNewTab: (callback: (url: string) => void) => {
    ipcRenderer.on('request-new-tab', (_, url) => callback(url));
  },
  getSettings: () => ipcRenderer.invoke('get-settings'),
  saveSettings: (settings: any) => ipcRenderer.invoke('save-settings', settings)
});
