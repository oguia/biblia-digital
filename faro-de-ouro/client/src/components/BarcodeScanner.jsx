import React, { useEffect } from 'react';
import { Html5QrcodeScanner } from 'html5-qrcode';

const BarcodeScanner = ({ onScan, onClose }) => {
  useEffect(() => {
    const scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);

    scanner.render((decodedText) => {
      onScan(decodedText);
      scanner.clear();
      onClose();
    }, (error) => {
      // Ignore scan errors, standard behavior
    });

    return () => {
      scanner.clear().catch(error => {
        console.error("Failed to clear scanner", error);
      });
    };
  }, [onScan, onClose]);

  return (
    <div className="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-xl p-4 w-full max-w-md">
        <div className="flex justify-between items-center mb-4">
          <h3 className="font-bold">Ler Código</h3>
          <button onClick={onClose} className="text-gray-500 hover:text-gray-800">Fechar</button>
        </div>
        <div id="reader"></div>
      </div>
    </div>
  );
};

export default BarcodeScanner;
