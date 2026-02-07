import React, { useState, useRef, useEffect, useCallback } from 'react';
import { X, ArrowLeftRight } from 'lucide-react';

interface ImageComparisonProps {
  imageOld: string;
  imageNew: string;
  labelOld?: string;
  labelNew?: string;
}

export function ImageComparison({ imageOld, imageNew, labelOld = "Antiga", labelNew = "Atual" }: ImageComparisonProps) {
  const [sliderPosition, setSliderPosition] = useState(50);
  const containerRef = useRef<HTMLDivElement>(null);
  const isDragging = useRef(false);

  const handleMove = useCallback((clientX: number) => {
    if (containerRef.current) {
      const rect = containerRef.current.getBoundingClientRect();
      const x = Math.max(0, Math.min(clientX - rect.left, rect.width));
      const percentage = (x / rect.width) * 100;
      setSliderPosition(percentage);
    }
  }, []);

  const handleMouseDown = () => {
    isDragging.current = true;
  };

  const handleMouseMove = (e: React.MouseEvent) => {
    if (isDragging.current) {
      handleMove(e.clientX);
    }
  };

  const handleTouchMove = (e: React.TouchEvent) => {
      handleMove(e.touches[0].clientX);
  };


  useEffect(() => {
    const handleGlobalMouseUp = () => {
      isDragging.current = false;
    };
    window.addEventListener('mouseup', handleGlobalMouseUp);
    return () => window.removeEventListener('mouseup', handleGlobalMouseUp);
  }, [handleMove]);

  return (
    <div
      className="relative w-full h-64 sm:h-80 md:h-[500px] rounded-lg overflow-hidden cursor-col-resize select-none group shadow-inner bg-gray-100"
      ref={containerRef}
      onMouseDown={handleMouseDown}
      onMouseMove={handleMouseMove}
      onTouchMove={handleTouchMove}
    >
      {/* Modern Image (Base) */}
      <img
        src={imageNew}
        alt="Modern"
        className="absolute top-0 left-0 w-full h-full object-cover select-none pointer-events-none"
      />
      <span className="absolute top-4 right-4 bg-black/60 text-white px-3 py-1 text-sm font-bold rounded pointer-events-none select-none z-10">
        {labelNew}
      </span>

      {/* Ancient Image (Overlay with Clip Path) */}
      <img
        src={imageOld}
        alt="Ancient"
        className="absolute top-0 left-0 w-full h-full object-cover select-none pointer-events-none shadow-[2px_0_10px_rgba(0,0,0,0.3)]"
        style={{ clipPath: `inset(0 ${100 - sliderPosition}% 0 0)` }}
      />
      <span
        className="absolute top-4 left-4 bg-black/60 text-white px-3 py-1 text-sm font-bold rounded pointer-events-none select-none z-10"
        style={{ opacity: sliderPosition > 10 ? 1 : 0, transition: 'opacity 0.2s' }}
      >
        {labelOld}
      </span>

      {/* Slider Handle */}
      <div
        className="absolute top-0 bottom-0 w-1 bg-white cursor-ew-resize flex items-center justify-center pointer-events-none shadow-[0_0_10px_rgba(0,0,0,0.5)]"
        style={{ left: `${sliderPosition}%` }}
      >
        <div className="w-10 h-10 bg-white rounded-full shadow-xl flex items-center justify-center text-blue-600 transform -translate-x-1/2 border border-gray-200">
           <ArrowLeftRight size={20} />
        </div>
      </div>
    </div>
  );
}

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    imageOld?: string;
    imageNew?: string;
    title: string;
}

export function ImageModal({ isOpen, onClose, imageOld, imageNew, title }: ModalProps) {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-[1000] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm" onClick={onClose}>
            <div className="bg-white rounded-xl max-w-5xl w-full p-6 relative shadow-2xl animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col" onClick={e => e.stopPropagation()}>
                <button
                    onClick={onClose}
                    className="absolute -top-3 -right-3 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 shadow-lg transition-colors z-10"
                >
                    <X size={20} />
                </button>
                <h3 className="text-2xl font-bold mb-4 text-gray-800 border-b pb-2 flex-shrink-0">{title}</h3>

                <div className="flex-grow overflow-hidden flex flex-col justify-center">
                    {imageOld && imageNew ? (
                        <ImageComparison imageOld={imageOld} imageNew={imageNew} />
                    ) : (
                        <div className="flex justify-center bg-gray-100 rounded-lg p-2 h-full">
                             <img src={imageOld || imageNew} alt={title} className="max-h-full max-w-full object-contain rounded shadow-md" />
                        </div>
                    )}
                </div>

                <div className="mt-4 text-sm text-gray-500 text-center italic flex-shrink-0">
                    {imageOld && imageNew ? "Arraste o divisor para comparar as imagens" : "Visualização da imagem"}
                </div>
            </div>
        </div>
    )
}
