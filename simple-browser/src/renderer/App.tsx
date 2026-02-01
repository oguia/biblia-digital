import React, { useEffect, useState, useRef } from 'react';
import { ArrowLeft, ArrowRight, RotateCw, X, Plus, Star, Settings } from 'lucide-react';

// Type definitions for the exposed Electron API
interface BrowserAPI {
  createTab: (url?: string) => Promise<string>;
  closeTab: (id: string) => Promise<void>;
  switchTab: (id: string) => Promise<{url: string, title: string}>;
  navigate: (id: string, url: string) => Promise<void>;
  reload: (id: string) => Promise<void>;
  goBack: (id: string) => Promise<void>;
  goForward: (id: string) => Promise<void>;
  onUrlChange: (cb: (id: string, url: string) => void) => void;
  onTitleChange: (cb: (id: string, title: string) => void) => void;
  onFaviconChange: (cb: (id: string, fav: string) => void) => void;
  onLoading: (cb: (id: string, isLoading: boolean) => void) => void;
  onRequestNewTab: (cb: (url: string) => void) => void;
  getSettings: () => Promise<{ homePage: string }>;
  saveSettings: (settings: { homePage: string }) => Promise<void>;
}

declare global {
  interface Window {
    browserAPI: BrowserAPI;
  }
}

interface Tab {
  id: string;
  title: string;
  url: string;
  loading: boolean;
}

function App() {
  const [tabs, setTabs] = useState<Tab[]>([]);
  const [activeTabId, setActiveTabId] = useState<string | null>(null);
  const [urlInput, setUrlInput] = useState('');
  const [showSettings, setShowSettings] = useState(false);
  const [homePageUrl, setHomePageUrl] = useState('');
  const initialized = useRef(false);

  // Ref to access current activeTabId inside callbacks
  const activeTabIdRef = useRef(activeTabId);
  useEffect(() => {
    activeTabIdRef.current = activeTabId;
  }, [activeTabId]);

  useEffect(() => {
    if (initialized.current) return;
    initialized.current = true;

    // Create initial tab
    if (window.browserAPI) {
      handleCreateTab();

      // Listeners
      window.browserAPI.onUrlChange((id, url) => {
        setTabs(prev => prev.map(t => t.id === id ? { ...t, url } : t));
        if (id === activeTabIdRef.current) setUrlInput(url);
      });

      window.browserAPI.onTitleChange((id, title) => {
        setTabs(prev => prev.map(t => t.id === id ? { ...t, title } : t));
      });

      window.browserAPI.onLoading((id, isLoading) => {
        setTabs(prev => prev.map(t => t.id === id ? { ...t, loading: isLoading } : t));
      });

      window.browserAPI.onRequestNewTab((url) => {
        handleCreateTab(url);
      });

      // Load Settings
      window.browserAPI.getSettings().then(settings => {
        setHomePageUrl(settings.homePage);
      });
    }
  }, []);

  const saveSettings = async () => {
    if (!window.browserAPI) return;
    await window.browserAPI.saveSettings({ homePage: homePageUrl });
    setShowSettings(false);
  };

  // Update url input when switching tabs
  useEffect(() => {
    const activeTab = tabs.find(t => t.id === activeTabId);
    if (activeTab) {
      setUrlInput(activeTab.url);
    }
  }, [activeTabId, tabs]);

  const handleCreateTab = async (url?: string | React.MouseEvent) => {
    // handleCreateTab is used as onClick handler too, so args might be event.
    const targetUrl = typeof url === 'string' ? url : undefined;

    if (!window.browserAPI) return;
    const id = await window.browserAPI.createTab(targetUrl);
    const newTab: Tab = { id, title: 'New Tab', url: targetUrl || '', loading: true };
    setTabs(prev => [...prev, newTab]);
    handleSwitchTab(id);
  };

  const handleSwitchTab = async (id: string) => {
    if (!window.browserAPI) return;
    setActiveTabId(id);
    const state = await window.browserAPI.switchTab(id);
    if (state) {
        setUrlInput(state.url);
    }
  };

  const handleCloseTab = async (e: React.MouseEvent, id: string) => {
    e.stopPropagation();
    if (!window.browserAPI) return;
    await window.browserAPI.closeTab(id);
    setTabs(prev => prev.filter(t => t.id !== id));
    if (activeTabId === id) {
      const remaining = tabs.filter(t => t.id !== id);
      if (remaining.length > 0) {
        handleSwitchTab(remaining[remaining.length - 1].id);
      } else {
        setActiveTabId(null);
        setUrlInput('');
      }
    }
  };

  const handleNavigate = (e: React.FormEvent) => {
    e.preventDefault();
    if (activeTabId && window.browserAPI) {
      window.browserAPI.navigate(activeTabId, urlInput);
    }
  };

  const activeTab = tabs.find(t => t.id === activeTabId);

  return (
    <div className="app">
      <div className="top-bar">
        {/* Tab Bar */}
        <div className="tabs-container">
          {tabs.map(tab => (
            <div
              key={tab.id}
              className={`tab ${tab.id === activeTabId ? 'active' : ''}`}
              onClick={() => handleSwitchTab(tab.id)}
            >
              <span>{tab.loading ? 'Loading...' : (tab.title || 'New Tab')}</span>
              <div className="tab-close" onClick={(e) => handleCloseTab(e, tab.id)}>
                <X size={12} />
              </div>
            </div>
          ))}
          <button className="nav-button" onClick={() => handleCreateTab()}>
            <Plus size={16} />
          </button>
        </div>

        {/* Address Bar */}
        <div className="address-bar-container">
          <button className="nav-button" onClick={() => activeTabId && window.browserAPI.goBack(activeTabId)}>
            <ArrowLeft size={16} />
          </button>
          <button className="nav-button" onClick={() => activeTabId && window.browserAPI.goForward(activeTabId)}>
            <ArrowRight size={16} />
          </button>
          <button className="nav-button" onClick={() => activeTabId && window.browserAPI.reload(activeTabId)}>
            <RotateCw size={16} className={activeTab?.loading ? 'animate-spin' : ''} />
          </button>

          <form onSubmit={handleNavigate} style={{ flex: 1, display: 'flex' }}>
            <input
              className="url-input"
              value={urlInput}
              onChange={(e) => setUrlInput(e.target.value)}
              placeholder="Enter URL..."
              onFocus={(e) => e.target.select()}
              // @ts-ignore
              style={{ WebkitAppRegion: 'no-drag' }}
            />
          </form>

          <button className="nav-button">
            <Star size={16} />
          </button>

          <div style={{ width: '1px', height: '20px', backgroundColor: '#ccc', margin: '0 8px' }}></div>

          <button
            className="nav-button"
            onClick={() => {
              console.log('Settings clicked!');
              setShowSettings(prev => !prev);
            }}
            style={{
              zIndex: 1000,
              position: 'relative',
              // @ts-ignore
              WebkitAppRegion: 'no-drag',
              cursor: 'pointer',
              padding: '8px',
              backgroundColor: showSettings ? '#e0e0e0' : 'transparent',
              borderRadius: '50%'
            }}
            title="Configurações"
          >
            <Settings size={20} />
          </button>
        </div>
      </div>

      {showSettings && (
        <div style={{
          position: 'absolute',
          top: '80px',
          right: '10px',
          backgroundColor: 'white',
          border: '1px solid #ccc',
          borderRadius: '8px',
          padding: '16px',
          boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
          zIndex: 1000
        }}>
          <h3 style={{ marginTop: 0, fontSize: '16px' }}>Configurações</h3>
          <div style={{ marginBottom: '12px' }}>
            <label style={{ display: 'block', fontSize: '12px', marginBottom: '4px' }}>Página Inicial</label>
            <input
              type="text"
              value={homePageUrl}
              onChange={(e) => setHomePageUrl(e.target.value)}
              style={{ width: '200px', padding: '4px' }}
            />
          </div>
          <button onClick={saveSettings} style={{ padding: '6px 12px', cursor: 'pointer' }}>Salvar</button>
        </div>
      )}
    </div>
  );
}

export default App;
