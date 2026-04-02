import React, { useState, useEffect } from 'react';
import { Download } from 'lucide-react';

export default function InstallPWA() {
  const [deferredPrompt, setDeferredPrompt] = useState(null);
  const [isInstallable, setIsInstallable] = useState(false);
  const [isIOS, setIsIOS] = useState(false);

  useEffect(() => {
    // Detect iOS to show manual instruction if not natively installable
    const userAgent = window.navigator.userAgent.toLowerCase();
    const isIosDevice = /iphone|ipad|ipod/.test(userAgent);
    const isStandalone = ('standalone' in window.navigator) && window.navigator.standalone;

    if (isIosDevice && !isStandalone) {
      setIsIOS(true);
      setIsInstallable(true); // Force show for manual instructions
    }

    const handler = (e) => {
      e.preventDefault();
      setDeferredPrompt(e);
      setIsInstallable(true);
    };

    window.addEventListener('beforeinstallprompt', handler);

    return () => {
      window.removeEventListener('beforeinstallprompt', handler);
    };
  }, []);

  const handleInstallClick = async () => {
    if (isIOS && !deferredPrompt) {
      alert("No iPhone/iPad:\n1. Toque no ícone de 'Compartilhar' no menu do Safari (um quadrado com seta para cima).\n2. Role para baixo e escolha 'Adicionar à Tela de Início'.");
      return;
    }

    if (!deferredPrompt) {
        // Fallback for browsers that don't trigger the event but show the button
        alert("Para instalar:\nNo Chrome: Toque nos três pontinhos e escolha 'Instalar aplicativo' ou 'Adicionar à tela inicial'.");
        return;
    }

    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;

    if (outcome === 'accepted') {
      setIsInstallable(false);
    }
    setDeferredPrompt(null);
  };

  // We are changing to ALWAYS render a button to give users a chance to see instructions
  return (
    <button
      onClick={handleInstallClick}
      className="bg-green-100 text-green-700 border border-green-200 px-3 md:px-4 py-1.5 md:py-2 rounded-full font-bold shadow-sm hover:bg-green-200 flex items-center justify-center gap-2 transition-transform active:scale-95 text-xs md:text-sm whitespace-nowrap"
      title="Instalar App VouZelar"
    >
      <Download size={16} /> Instalar App
    </button>
  );
}
