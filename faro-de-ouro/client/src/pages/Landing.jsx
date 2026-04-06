import React from 'react';
import { Link } from 'react-router-dom';
import { Package, Shield, BarChart3, Smartphone, Camera, Users, Download } from 'lucide-react';
import { usePWAInstall } from '../hooks/usePWAInstall';

const Landing = () => {
  const { shouldShowInstallButton, promptInstall } = usePWAInstall();

  return (
    <div className="min-h-screen bg-brand-gray-light font-sans selection:bg-brand-orange selection:text-white">
      {/* Header */}
      <header className="bg-brand-gray-light text-brand-dark p-4 sticky top-0 z-50 shadow-md">
        <div className="container mx-auto flex justify-between items-center max-w-6xl">
          <Link to="/" className="flex items-center gap-3">
            <img src="/logo.png" alt="Faro de Ouro" className="h-28 object-contain" />
          </Link>
          <div className="flex items-center gap-4">
            {shouldShowInstallButton && (
              <button
                onClick={promptInstall}
                className="flex items-center gap-2 text-brand-dark border border-gray-300 px-4 py-2 rounded-lg font-semibold hover:bg-white transition"
              >
                <Download size={18} />
                <span className="hidden sm:inline">Instalar App</span>
              </button>
            )}
            <Link to="/login" className="text-gray-500 hover:text-brand-dark transition">Entrar</Link>
            <Link to="/register" className="bg-brand-orange text-white px-5 py-2 rounded-lg font-semibold hover:bg-[#a68a57] transition shadow-lg shadow-orange-500/30">
              Teste Grátis
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-brand-gray-light text-brand-dark py-20 relative overflow-hidden">
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-brand-orange via-brand-gray-light to-brand-gray-light pointer-events-none"></div>
        <div className="container mx-auto px-4 max-w-6xl text-center relative z-10">
          <h1 className="text-4xl md:text-6xl font-extrabold mb-6 leading-tight text-brand-dark">
            Controle seu Estoque como um <span className="text-brand-orange">Especialista</span>
          </h1>
          <p className="text-lg md:text-xl text-gray-500 mb-10 max-w-2xl mx-auto">
            O Faro de Ouro é o sistema de gestão de estoque ideal para pequenas e médias empresas. Simples, rápido e com tudo que você precisa para não perder vendas por falta de produtos.
          </p>
          <div className="flex flex-col sm:flex-row justify-center gap-4">
            <Link to="/register" className="bg-brand-orange text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#a68a57] transition transform hover:-translate-y-1 shadow-xl shadow-orange-500/20">
              Começar Teste de 7 Dias
            </Link>
            <Link to="/login" className="bg-white/50 text-brand-dark border border-gray-200 px-8 py-4 rounded-xl font-bold text-lg hover:bg-white transition shadow-sm">
              Já tenho uma conta
            </Link>
          </div>
        </div>
      </section>

      {/* Features Grid */}
      <section className="py-20 bg-brand-gray-light">
        <div className="container mx-auto px-4 max-w-6xl">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-brand-black mb-4">Tudo que o seu negócio precisa</h2>
            <p className="text-gray-600">Projetado para facilitar o seu dia a dia.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Camera className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Leitor de Código de Barras</h3>
              <p className="text-gray-600 leading-relaxed">
                Use a câmera do seu celular ou computador para dar entrada e saída de produtos rapidamente. Sem necessidade de equipamentos caros.
              </p>
            </div>

            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <BarChart3 className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Kardex de Movimentações</h3>
              <p className="text-gray-600 leading-relaxed">
                Tenha um histórico completo e imutável de todas as entradas e saídas. Saiba exatamente quando e por que seu estoque mudou.
              </p>
            </div>

            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Users className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Sistema Multi-usuário</h3>
              <p className="text-gray-600 leading-relaxed">
                Gerencie permissões para seus funcionários. Acompanhe quem fez qual movimentação dentro do sistema, garantindo segurança.
              </p>
            </div>

            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Smartphone className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Responsivo e Rápido</h3>
              <p className="text-gray-600 leading-relaxed">
                Acesse do seu celular, tablet ou computador. O Faro de Ouro foi construído com tecnologia de ponta para carregar instantaneamente.
              </p>
            </div>

            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Package className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Controle de Estoque Mínimo</h3>
              <p className="text-gray-600 leading-relaxed">
                Configure limites mínimos para cada produto e receba sugestões automáticas de compra antes que o estoque acabe.
              </p>
            </div>

            <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
              <div className="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                <Shield className="text-brand-orange w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold mb-3 text-brand-black">Assinatura Segura</h3>
              <p className="text-gray-600 leading-relaxed">
                Pagamentos mensais via Mercado Pago. Cancele quando quiser, sem burocracia ou multas. 7 dias grátis para você testar.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="bg-brand-orange py-16">
        <div className="container mx-auto px-4 max-w-4xl text-center">
          <h2 className="text-3xl font-bold text-white mb-6">Pronto para organizar sua empresa?</h2>
          <p className="text-orange-100 mb-8 text-lg">
            Pare de usar planilhas complexas. Cadastre-se agora e veja a diferença no seu dia a dia.
          </p>
          <Link to="/register" className="bg-brand-dark text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-gray-800 transition shadow-xl inline-block">
            Criar Conta Gratuita
          </Link>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-brand-gray-light text-gray-500 py-12 border-t border-gray-200">
        <div className="container mx-auto px-4 max-w-6xl flex flex-col md:flex-row justify-between items-center">
          <div className="flex items-center gap-2 mb-4 md:mb-0">
            <img src="/logo.png" alt="Faro de Ouro" className="h-20 object-contain grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition" />
          </div>
          <p className="text-sm">© {new Date().getFullYear()} Faro de Ouro. Todos os direitos reservados.</p>
        </div>
      </footer>
    </div>
  );
};

export default Landing;
