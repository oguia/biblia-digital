import axios from 'axios';

const USE_MOCK = true; // Set to true for development environment without PHP server

export interface Verse {
  number: number;
  text: string;
}

export interface BibleResponse {
  book: { id: number; name: string; abbrev: string };
  chapter: { number: number; total: number };
  verses: Verse[];
  version: string;
}

export interface EnrichmentResponse {
  context: {
    who_involved: string; // JSON string
    when_text: string;
    where_text: string;
    historical_events: string;
  };
  timeline: Array<{
    year: number;
    title: string;
    description: string;
    era: string;
  }>;
  map: Array<{
    name: string;
    latitude: number;
    longitude: number;
    description: string;
    type: string;
  }>;
  application: {
    truth: string;
    alert: string;
    action: string;
  };
}

// Mock Data matching the seed_data.sql
const MOCK_GENESIS_1: BibleResponse = {
  book: { id: 1, name: 'Gênesis', abbrev: 'gn' },
  chapter: { number: 1, total: 50 },
  verses: [
    { number: 1, text: 'No princípio Deus criou os céus e a terra.' },
    { number: 2, text: 'Era a terra sem forma e vazia; trevas cobriam a face do abismo, e o Espírito de Deus se movia sobre a face das águas.' },
    { number: 3, text: 'Disse Deus: "Haja luz", e houve luz.' },
    { number: 4, text: 'Deus viu que a luz era boa, e separou a luz das trevas.' },
    { number: 5, text: 'Deus chamou à luz dia, e às trevas chamou noite. Passaram-se a tarde e a manhã; esse foi o primeiro dia.' },
  ],
  version: 'nvi'
};

const MOCK_ENRICHMENT_GENESIS_1: EnrichmentResponse = {
  context: {
    who_involved: '["Deus (Elohim)", "Espírito Santo", "A Palavra (Jesus)"]',
    when_text: 'No princípio (Eternidade Passada)',
    where_text: 'O Universo; A Terra em formação',
    historical_events: 'A Criação do Universo; O início do Tempo; Formação da matéria'
  },
  timeline: [
    { year: -4004, title: 'A Criação', description: 'Deus cria os céus e a terra a partir do nada (Creatio Ex Nihilo).', era: 'Origens' },
    { year: -4004, title: 'Dia 1: Luz', description: 'Separação entre luz e trevas; criação do tempo.', era: 'Origens' }
  ],
  map: [
    { name: 'Jardim do Éden (Provável)', latitude: 31.0, longitude: 47.0, description: 'Localização tradicional baseada na confluência dos rios Tigre e Eufrates.', type: 'region' }
  ],
  application: {
    truth: 'Deus é o Criador Soberano que traz ordem ao caos e luz às trevas.',
    alert: 'Sem a presença ativa de Deus, a vida permanece "sem forma e vazia", em trevas espirituais.',
    action: 'Identifique uma área "caótica" em sua vida hoje e convide Deus para trazer Sua luz e ordem sobre ela.'
  }
};

export const api = {
  getBibleText: async (book: string, chapter: number, version = 'nvi'): Promise<BibleResponse> => {
    if (USE_MOCK) return MOCK_GENESIS_1;
    const res = await axios.get(`/api/bible.php?book=${book}&chapter=${chapter}&version=${version}`);
    return res.data;
  },

  getEnrichment: async (book: string, chapter: number): Promise<EnrichmentResponse> => {
    if (USE_MOCK) return MOCK_ENRICHMENT_GENESIS_1;
    const res = await axios.get(`/api/enrichment.php?book=${book}&chapter=${chapter}`);
    return res.data;
  },

  saveProgress: async (userId: number, book: string, chapter: number, notes?: string) => {
      if (USE_MOCK) {
          console.log('Progress saved mock');
          return { success: true };
      }
      return axios.post('/api/user.php?action=save_progress', { user_id: userId, book, chapter, notes });
  }
};
