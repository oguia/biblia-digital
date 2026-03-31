import React from 'react';
import { useLocation } from 'wouter';
import { ShieldAlert, Pill, Users, CheckCircle, ArrowRight } from 'lucide-react';

export default function Landing() {
  const [, setLocation] = useLocation();

  return (
    <div className="min-h-screen bg-gray-50 font-sans text-gray-900">
      {/* Header */}
      <header className="bg-white shadow-sm sticky top-0 z-50">
        <div className="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
          <div className="flex items-center gap-2">
            <img src="/logo.png" alt="VouZelar" className="h-10" />
            <span className="text-2xl font-bold text-primary-700 tracking-tight">VouZelar</span>
          </div>
          <div className="flex gap-4">
            <button onClick={() => setLocation('/login')} className="text-gray-600 font-medium hover:text-primary-600 transition-colors">
              Entrar
            </button>
            <button onClick={() => setLocation('/register')} className="bg-primary-600 text-white px-5 py-2 rounded-full font-medium hover:bg-primary-700 transition-colors shadow-sm">
              Começar Grátis
            </button>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-gradient-to-b from-primary-50 to-white py-20 px-4 text-center">
        <div className="max-w-4xl mx-auto">
          <h1 className="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
            Gestão familiar de medicamentos <span className="text-primary-600">sem complicação.</span>
          </h1>
          <p className="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
            O VouZelar ajuda você e sua família a cuidar de quem mais importa. Evite esquecimentos, desperdícios e saiba exatamente quem comprou o quê.
          </p>
          <div className="flex flex-col sm:flex-row justify-center gap-4">
            <button onClick={() => setLocation('/register')} className="bg-primary-600 text-white px-8 py-4 rounded-full text-lg font-bold shadow-lg hover:bg-primary-700 transition-transform hover:-translate-y-1 flex items-center justify-center gap-2">
              Teste Grátis por 7 dias <ArrowRight size={20} />
            </button>
          </div>
        </div>
      </section>

      {/* Dores Resolvidas (Features) */}
      <section className="py-20 px-4 bg-white">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Por que usar o VouZelar?</h2>
            <p className="text-gray-600 max-w-2xl mx-auto">Resolvemos os três maiores problemas na gestão de remédios para idosos.</p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            <div className="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
              <div className="bg-blue-100 text-blue-600 w-14 h-14 rounded-full flex items-center justify-center mb-6">
                <CheckCircle size={28} />
              </div>
              <h3 className="text-xl font-bold mb-3">Fim do Esquecimento</h3>
              <p className="text-gray-600">
                Uma interface gigante e simplificada para o idoso confirmar que tomou o remédio. Se ele esquecer, você recebe um alerta no celular.
              </p>
            </div>

            <div className="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
              <div className="bg-green-100 text-green-600 w-14 h-14 rounded-full flex items-center justify-center mb-6">
                <Pill size={28} />
              </div>
              <h3 className="text-xl font-bold mb-3">Adeus Desperdício</h3>
              <p className="text-gray-600">
                Calculadora inteligente que projeta o dia exato que o remédio vai acabar. Chega de comprar em excesso ou deixar faltar.
              </p>
            </div>

            <div className="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
              <div className="bg-yellow-100 text-yellow-600 w-14 h-14 rounded-full flex items-center justify-center mb-6">
                <Users size={28} />
              </div>
              <h3 className="text-xl font-bold mb-3">Paz na Família</h3>
              <p className="text-gray-600">
                Perfil multiusuário onde os filhos se revezam. Acabe com o conflito de "quem comprou o quê" com nossa escala de compras.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Pricing */}
      <section className="py-20 px-4 bg-gray-50">
        <div className="max-w-6xl mx-auto text-center">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">Planos Simples e Transparentes</h2>
          <p className="text-gray-600 mb-12">Comece grátis por 7 dias. Sem compromisso.</p>

          <div className="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div className="bg-white p-8 rounded-3xl shadow-sm border border-gray-200 text-left">
              <h3 className="text-2xl font-bold mb-2">Individual</h3>
              <p className="text-gray-500 mb-6">Perfeito para quem cuida sozinho.</p>
              <div className="text-4xl font-extrabold mb-8">R$ 5<span className="text-lg text-gray-500 font-medium">/mês</span></div>
              <ul className="space-y-4 mb-8">
                <li className="flex gap-3 text-gray-600"><CheckCircle className="text-green-500" /> 1 Paciente</li>
                <li className="flex gap-3 text-gray-600"><CheckCircle className="text-green-500" /> Interface Simplificada p/ Idoso</li>
                <li className="flex gap-3 text-gray-600"><CheckCircle className="text-green-500" /> Calculadora de Estoque</li>
                <li className="flex gap-3 text-gray-600"><CheckCircle className="text-green-500" /> Buscador de Preços</li>
              </ul>
              <button onClick={() => setLocation('/register')} className="w-full bg-primary-100 text-primary-700 py-3 rounded-xl font-bold hover:bg-primary-200 transition-colors">Testar Individual</button>
            </div>

            <div className="bg-primary-600 p-8 rounded-3xl shadow-xl text-left text-white transform md:-translate-y-4 relative">
              <div className="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-3xl uppercase tracking-wider">Mais Popular</div>
              <h3 className="text-2xl font-bold mb-2">Família</h3>
              <p className="text-primary-100 mb-6">A rede de apoio completa.</p>
              <div className="text-4xl font-extrabold mb-8">R$ 19,90<span className="text-lg text-primary-200 font-medium">/mês</span></div>
              <ul className="space-y-4 mb-8">
                <li className="flex gap-3 text-white"><CheckCircle className="text-primary-300" /> Até 5 cuidadores</li>
                <li className="flex gap-3 text-white"><CheckCircle className="text-primary-300" /> Tudo do plano Individual</li>
                <li className="flex gap-3 text-white"><CheckCircle className="text-primary-300" /> Escala de Compras</li>
                <li className="flex gap-3 text-white"><CheckCircle className="text-primary-300" /> Alertas PWA de Emergência</li>
              </ul>
              <button onClick={() => setLocation('/register')} className="w-full bg-white text-primary-600 py-3 rounded-xl font-bold hover:bg-gray-50 transition-colors shadow-sm">Testar Família</button>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-gray-400 py-12 text-center">
        <p>© {new Date().getFullYear()} VouZelar. Todos os direitos reservados. Feito com amor para a sua família.</p>
      </footer>
    </div>
  );
}
