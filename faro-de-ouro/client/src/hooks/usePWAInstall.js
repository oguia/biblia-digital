import { useState, useEffect } from 'react';

export const usePWAInstall = () => {
  const [isInstalled, setIsInstalled] = useState(false);

  useEffect(() => {
    // Check if the app is already installed or running in standalone mode
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
    setIsInstalled(isStandalone);

    const handleInstalled = () => {
      setIsInstalled(true);
      window.deferredPWAInstallPrompt = null;
    };

    window.addEventListener('appinstalled', handleInstalled);

    return () => {
      window.removeEventListener('appinstalled', handleInstalled);
    };
  }, []);

  const promptInstall = async () => {
    const promptEvent = window.deferredPWAInstallPrompt;

    if (promptEvent) {
      // Browser gave us the native popup
      promptEvent.prompt();
      const { outcome } = await promptEvent.userChoice;
      if (outcome === 'accepted') {
        setIsInstalled(true);
        window.deferredPWAInstallPrompt = null;
      }
    } else {
      // Browser withheld the popup or it's an iPhone
      const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

      if (isIOS) {
        alert('Para instalar no iPhone/iPad: \n\n1. Toque no botão "Compartilhar" (quadrado com seta para cima) no menu do Safari.\n2. Role para baixo e selecione "Adicionar à Tela de Início".');
      } else {
        alert('Para instalar este aplicativo: \n\nAbra o menu do seu navegador (três pontinhos no canto superior direito) e selecione "Instalar aplicativo" ou "Adicionar à tela inicial".');
      }
    }
  };

  // We show the button if it's NOT already installed.
  // We no longer rely strictly on supportsPWA to determine visibility.
  const shouldShowInstallButton = !isInstalled;

  return { shouldShowInstallButton, promptInstall };
};
