export interface TimelineEvent {
  id: string;
  title: string;
  date: string;
  description: string;
  location: string;
  coordinates: [number, number]; // [lat, lng]
  category: 'primevo' | 'patriarcas' | 'exodo' | 'juizes' | 'reino' | 'exilio' | 'jesus' | 'igreja' | 'apocalipse';
  imageOld?: string;
  imageNew?: string;
}

export const timelineData: TimelineEvent[] = [
  // 1. Período Primevo — Gênesis 1–11
  {
    id: 'eden',
    title: 'Criação e Jardim do Éden',
    date: '~4000 a.C.',
    description: 'Criação do homem e o início da história humana no Jardim do Éden.',
    location: 'Jardim do Éden (Mesopotâmia)',
    coordinates: [33.0, 44.0],
    category: 'primevo',
    imageOld: 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&q=80&w=800', // Forest/Nature
    imageNew: 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&q=80&w=800', // Landscape
  },
  {
    id: 'havila',
    title: 'Terra de Havilá',
    date: '~4000 a.C.',
    description: 'Região descrita como rica em ouro, bdélio e pedra de ônix.',
    location: 'Havilá (Leste da Arábia / Mesopotâmia)',
    coordinates: [26.0, 48.0],
    category: 'primevo',
  },
  {
    id: 'cuxe',
    title: 'Terra de Cuxe',
    date: '~4000 a.C.',
    description: 'Região associada ao rio Giom, um dos quatro rios do Éden.',
    location: 'Cuxe (Etiópia / Núbia)',
    coordinates: [12.0, 37.0],
    category: 'primevo',
  },
  {
    id: 'nod',
    title: 'Terra de Nod',
    date: '~3900 a.C.',
    description: 'Região para onde Caim foi exilado após matar Abel.',
    location: 'Nod (Leste do Éden)',
    coordinates: [34.0, 46.0],
    category: 'primevo',
  },
  {
    id: 'enoque-caim',
    title: 'Cidade de Enoque',
    date: '~3900 a.C.',
    description: 'Primeira cidade mencionada na Bíblia, fundada por Caim.',
    location: 'Enoque (Local desconhecido)',
    coordinates: [34.5, 46.5],
    category: 'primevo',
  },
  {
    id: 'ararat',
    title: 'Repouso da Arca de Noé',
    date: '~2348 a.C.',
    description: 'Onde a Arca de Noé repousou após o Dilúvio.',
    location: 'Monte Ararate (Turquia Oriental)',
    coordinates: [39.7, 44.3],
    category: 'primevo',
    imageOld: 'https://images.unsplash.com/photo-1565551977794-3995818317e1?auto=format&fit=crop&q=80&w=800', // Mountain
    imageNew: 'https://images.unsplash.com/photo-1565551977794-3995818317e1?auto=format&fit=crop&q=80&w=800', // Mountain
  },

  // 2. Patriarcas — Gênesis 12–50
  {
    id: 'babel',
    title: 'Torre de Babel',
    date: '~2200 a.C.',
    description: 'Construção da torre e a confusão das línguas.',
    location: 'Babel (Babilônia)',
    coordinates: [32.5, 44.4],
    category: 'patriarcas',
    imageOld: 'https://images.unsplash.com/photo-1616790934988-1e4a64d08183?auto=format&fit=crop&q=80&w=800', // Ancient Ruins (Representation)
    imageNew: 'https://images.unsplash.com/photo-1596708304019-216527582b13?auto=format&fit=crop&q=80&w=800', // Modern Ruins/Iraq
  },
  {
    id: 'sinear',
    title: 'Reino de Ninrode',
    date: '~2200 a.C.',
    description: 'O início do reino de Ninrode foi Babel, Ereque, Acade e Calné.',
    location: 'Sinear (Sul da Mesopotâmia)',
    coordinates: [32.0, 44.0],
    category: 'patriarcas',
  },
  {
    id: 'ur',
    title: 'Chamado de Abraão',
    date: '~2090 a.C.',
    description: 'Deus chama Abrão para sair de sua terra e ir para a terra que Ele mostraria.',
    location: 'Ur dos Caldeus',
    coordinates: [30.9, 46.1],
    category: 'patriarcas',
    imageOld: 'https://images.unsplash.com/photo-1590076215667-875d4ef2d743?auto=format&fit=crop&q=80&w=800', // Ziggurat
    imageNew: 'https://images.unsplash.com/photo-1590076215667-875d4ef2d743?auto=format&fit=crop&q=80&w=800', // Ziggurat
  },
  {
    id: 'hara',
    title: 'Estadia em Harã',
    date: '~2090–2080 a.C.',
    description: 'Abrão habitou em Harã antes de partir para Canaã.',
    location: 'Harã (Turquia)',
    coordinates: [37.2, 38.9],
    category: 'patriarcas',
  },
  {
    id: 'siquem',
    title: 'Promessa da Terra',
    date: '~2080 a.C.',
    description: 'O Senhor apareceu a Abrão e disse: "À tua descendência darei esta terra".',
    location: 'Siquém (Nablus)',
    coordinates: [32.2, 35.3],
    category: 'patriarcas',
  },
  {
    id: 'betel-ai',
    title: 'Altar entre Betel e Ai',
    date: '~2080 a.C.',
    description: 'Abrão edificou um altar ao Senhor e invocou o Seu nome.',
    location: 'Betel/Ai',
    coordinates: [31.9, 35.2],
    category: 'patriarcas',
  },
  {
    id: 'hebrom',
    title: 'Morada dos Patriarcas',
    date: '~2070 a.C.',
    description: 'Abraão mudou suas tendas e foi habitar nos carvalhais de Manre, em Hebrom.',
    location: 'Hebrom',
    coordinates: [31.6, 35.1],
    category: 'patriarcas',
  },
  {
    id: 'sodoma',
    title: 'Destruição de Sodoma',
    date: '~2067 a.C.',
    description: 'Destruição da cidade devido à sua maldade.',
    location: 'Sodoma (Sul do Mar Morto)',
    coordinates: [31.2, 35.4],
    category: 'patriarcas',
    imageOld: 'https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?auto=format&fit=crop&q=80&w=800', // Desert/Dead Sea
    imageNew: 'https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?auto=format&fit=crop&q=80&w=800', // Desert/Dead Sea
  },
  {
    id: 'gomorra',
    title: 'Destruição de Gomorra',
    date: '~2067 a.C.',
    description: 'Destruição juntamente com Sodoma.',
    location: 'Gomorra (Sul do Mar Morto)',
    coordinates: [31.3, 35.5],
    category: 'patriarcas',
  },
  {
    id: 'dota',
    title: 'Venda de José',
    date: '~1898 a.C.',
    description: 'José é vendido por seus irmãos a mercadores ismaelitas.',
    location: 'Dotã',
    coordinates: [32.4, 35.2],
    category: 'patriarcas',
  },
  {
    id: 'menfis',
    title: 'José no Egito',
    date: '~1880 a.C.',
    description: 'José torna-se governador do Egito.',
    location: 'Mênfis (Egito)',
    coordinates: [29.8, 31.0],
    category: 'patriarcas',
    imageOld: 'https://images.unsplash.com/photo-1539650116455-8efdb5640c45?auto=format&fit=crop&q=80&w=800', // Pyramids
    imageNew: 'https://images.unsplash.com/photo-1539650116455-8efdb5640c45?auto=format&fit=crop&q=80&w=800', // Pyramids
  },
  {
    id: 'gosen',
    title: 'Habitação de Israel',
    date: '~1876 a.C.',
    description: 'Jacó e sua família habitam na terra de Gósen.',
    location: 'Gósen (Delta do Nilo)',
    coordinates: [30.8, 31.0],
    category: 'patriarcas',
  },

  // 3. Êxodo e Conquista — Êxodo a Juízes
  {
    id: 'ramesses',
    title: 'Partida de Israel',
    date: '~1446 a.C.',
    description: 'O povo de Israel parte de Ramessés, iniciando o Êxodo.',
    location: 'Ramessés (Qantir)',
    coordinates: [30.8, 31.8], // Coordinates for Qantir/Pi-Ramesses
    category: 'exodo',
  },
];
