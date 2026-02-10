import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Lightbulb, AlertTriangle, CheckCircle } from 'lucide-react';

interface ApplicationCardProps {
  truth: string;
  alert: string;
  action: string;
}

export const ApplicationCard: React.FC<ApplicationCardProps> = ({ truth, alert, action }) => {
  return (
    <Card className="bg-gradient-to-br from-background to-muted/50 border-primary/20 shadow-xl overflow-hidden">
      <CardHeader className="bg-primary/5 pb-4 border-b border-primary/10">
        <CardTitle className="text-xl font-bold flex items-center gap-2">
          <span className="text-primary">✝️</span> E isso tem a ver comigo?
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-6 pt-6">

        {/* Truth */}
        <div className="flex gap-4 group">
          <div className="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
            <Lightbulb className="w-5 h-5" />
          </div>
          <div>
            <h4 className="font-semibold text-green-700 dark:text-green-300 text-sm uppercase tracking-wide mb-1">Verdade Central</h4>
            <p className="text-sm leading-relaxed text-muted-foreground font-medium">{truth}</p>
          </div>
        </div>

        {/* Alert */}
        <div className="flex gap-4 group">
          <div className="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform">
            <AlertTriangle className="w-5 h-5" />
          </div>
          <div>
            <h4 className="font-semibold text-yellow-700 dark:text-yellow-300 text-sm uppercase tracking-wide mb-1">Alerta</h4>
            <p className="text-sm leading-relaxed text-muted-foreground font-medium">{alert}</p>
          </div>
        </div>

        {/* Action */}
        <div className="flex gap-4 group bg-blue-50 dark:bg-blue-950/30 p-4 rounded-lg border border-blue-100 dark:border-blue-900/50">
          <div className="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
            <CheckCircle className="w-5 h-5" />
          </div>
          <div>
            <h4 className="font-semibold text-blue-700 dark:text-blue-300 text-sm uppercase tracking-wide mb-1">Ação Prática para Hoje</h4>
            <p className="text-sm leading-relaxed font-bold text-blue-900 dark:text-blue-100">{action}</p>
          </div>
        </div>

      </CardContent>
    </Card>
  );
};
