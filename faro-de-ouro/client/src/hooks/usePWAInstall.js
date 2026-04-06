import { useState, useEffect } from 'react';

export const usePWAInstall = () => {
  const [supportsPWA, setSupportsPWA] = useState(false);

  useEffect(() => {
    // Check if the event was already captured by index.html before React loaded
    if (window.deferredPWAInstallPrompt) {
      setSupportsPWA(true);
    }

    const handleReady = () => {
      setSupportsPWA(true);
    };

    const handleInstalled = () => {
      window.deferredPWAInstallPrompt = null;
      setSupportsPWA(false);
    };

    window.addEventListener('pwa-install-ready', handleReady);
    window.addEventListener('appinstalled', handleInstalled);

    return () => {
      window.removeEventListener('pwa-install-ready', handleReady);
      window.removeEventListener('appinstalled', handleInstalled);
    };
  }, []);

  const promptInstall = async () => {
    const promptEvent = window.deferredPWAInstallPrompt;
    if (!promptEvent) return;

    // Show the install prompt
    promptEvent.prompt();
    // Wait for the user to respond to the prompt
    const { outcome } = await promptEvent.userChoice;
    if (outcome === 'accepted') {
      setSupportsPWA(false);
      window.deferredPWAInstallPrompt = null;
    }
  };

  return { supportsPWA, promptInstall };
};
