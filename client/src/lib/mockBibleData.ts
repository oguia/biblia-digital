export interface Verse {
  number: number;
  text: string;
}

export interface ChapterData {
  book: {
    abbrev: {
      pt: string;
      en: string;
    };
    name: string;
    author: string;
    group: string;
    version: string;
  };
  chapter: {
    number: number;
    verses: number;
  };
  verses: Verse[];
}

// Mock data for Genesis Chapter 1 (NVI version)
export const mockBibleData: Record<string, Record<number, ChapterData>> = {
  nvi: {
    1: {
      book: {
        abbrev: { pt: "gn", en: "gn" },
        name: "Gênesis",
        author: "Moisés",
        group: "Pentateuco",
        version: "nvi",
      },
      chapter: {
        number: 1,
        verses: 31,
      },
      verses: [
        {
          number: 1,
          text: "No princípio Deus criou os céus e a terra.",
        },
        {
          number: 2,
          text: "Ora, a terra era sem forma e vazia; havia trevas sobre a face do abismo, e o Espírito de Deus se movia sobre a face das águas.",
        },
        {
          number: 3,
          text: "Disse Deus: Haja luz. E houve luz.",
        },
        {
          number: 4,
          text: "Viu Deus que era boa a luz; e fez Deus separação entre a luz e as trevas.",
        },
        {
          number: 5,
          text: "Chamou Deus à luz Dia, e às trevas chamou Noite. Houve tarde e manhã, o primeiro dia.",
        },
        {
          number: 6,
          text: "Disse Deus: Haja um firmamento no meio das águas, e haja separação entre águas e águas.",
        },
        {
          number: 7,
          text: "Fez Deus o firmamento e fez separação entre as águas que estavam debaixo do firmamento e as águas que estavam acima do firmamento. E assim foi.",
        },
        {
          number: 8,
          text: "Chamou Deus ao firmamento Céu. Houve tarde e manhã, o segundo dia.",
        },
        {
          number: 9,
          text: "Disse Deus: Ajuntem-se as águas debaixo do céu num só lugar, e apareça a porção seca. E assim foi.",
        },
        {
          number: 10,
          text: "Chamou Deus à porção seca Terra, e ao ajuntamento das águas chamou Mar. E viu Deus que era bom.",
        },
        {
          number: 11,
          text: "Disse Deus: Produza a terra relva, erva que dê sementes e árvore frutífera que dê fruto segundo a sua espécie, cuja sementes estejam nela sobre a terra. E assim foi.",
        },
        {
          number: 12,
          text: "A terra, pois, produziu relva, erva que dá sementes segundo a sua espécie, e árvore que dá fruto, cuja sementes estão nele, segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 13,
          text: "Houve tarde e manhã, o terceiro dia.",
        },
        {
          number: 14,
          text: "Disse Deus: Haja luzeiros no firmamento do céu, para fazer separação entre o dia e a noite; e sejam eles para sinais e para estações, e para dias e anos;",
        },
        {
          number: 15,
          text: "e sejam para luzeiros no firmamento do céu, para alumiar a terra. E assim foi.",
        },
        {
          number: 16,
          text: "Fez, pois, Deus os dois grandes luzeiros: o maior para governar o dia, e o menor para governar a noite; e as estrelas.",
        },
        {
          number: 17,
          text: "Colocou-os Deus no firmamento do céu para alumiar a terra,",
        },
        {
          number: 18,
          text: "para governar o dia e a noite, e para fazer separação entre a luz e as trevas. E viu Deus que era bom.",
        },
        {
          number: 19,
          text: "Houve tarde e manhã, o quarto dia.",
        },
        {
          number: 20,
          text: "Disse Deus: Produzam as águas abundantemente répteis de alma vivente; e voem as aves sobre a face da expansão do firmamento do céu.",
        },
        {
          number: 21,
          text: "Criou, pois, Deus os grandes animais marinhos, e todo réptil de alma vivente que as águas produziram abundantemente, segundo a sua espécie; e toda ave de asa, segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 22,
          text: "E Deus os abençoou, dizendo: Frutificai e multiplicai-vos, e enchei as águas nos mares; e as aves se multipliquem sobre a terra.",
        },
        {
          number: 23,
          text: "Houve tarde e manhã, o quinto dia.",
        },
        {
          number: 24,
          text: "Disse Deus: Produza a terra alma vivente segundo a sua espécie; gado, réptil e besta-fera da terra, segundo a sua espécie. E assim foi.",
        },
        {
          number: 25,
          text: "Fez Deus as bestas-feras da terra segundo a sua espécie, e o gado segundo a sua espécie, e todo réptil da terra segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 26,
          text: "Disse Deus: Façamos o homem à nossa imagem, conforme a nossa semelhança; e domine sobre os peixes do mar, sobre as aves do céu, sobre os animais domésticos, e sobre toda a terra, e sobre todo réptil que se move sobre a terra.",
        },
        {
          number: 27,
          text: "Criou, pois, Deus o homem à sua imagem, à imagem de Deus o criou; homem e mulher os criou.",
        },
        {
          number: 28,
          text: "E Deus os abençoou e lhes disse: Frutificai e multiplicai-vos, e enchei a terra, e sujeitai-a; e dominai sobre os peixes do mar, sobre as aves do céu, e sobre todo animal que se move sobre a terra.",
        },
        {
          number: 29,
          text: "E disse Deus: Eis que vos tenho dado toda erva que dá sementes, que está sobre a face de toda a terra; e toda árvore, em que há fruto de árvore que dá sementes, ser-vos-á para mantimento.",
        },
        {
          number: 30,
          text: "E a todo animal da terra, e a toda ave do céu, e a todo réptil da terra, em que há alma vivente, toda erva verde será para mantimento. E assim foi.",
        },
        {
          number: 31,
          text: "E viu Deus tudo quanto tinha feito, e eis que era muito bom. Houve tarde e manhã, o sexto dia.",
        },
      ],
    },
  },
  ra: {
    1: {
      book: {
        abbrev: { pt: "gn", en: "gn" },
        name: "Gênesis",
        author: "Moisés",
        group: "Pentateuco",
        version: "ra",
      },
      chapter: {
        number: 1,
        verses: 31,
      },
      verses: [
        {
          number: 1,
          text: "No princípio criou Deus os céus e a terra.",
        },
        {
          number: 2,
          text: "E a terra era sem forma e vazia; e havia trevas sobre a face do abismo; e o Espírito de Deus se movia sobre a face das águas.",
        },
        {
          number: 3,
          text: "E disse Deus: Haja luz. E houve luz.",
        },
        {
          number: 4,
          text: "E viu Deus que era boa a luz; e Deus separou a luz das trevas.",
        },
        {
          number: 5,
          text: "E Deus chamou à luz Dia; e às trevas chamou Noite. E houve tarde e manhã, um dia.",
        },
        {
          number: 6,
          text: "E disse Deus: Haja uma expansão no meio das águas; e haja separação entre águas e águas.",
        },
        {
          number: 7,
          text: "Então fez Deus a expansão, e separou as águas que estavam debaixo da expansão das águas que estavam acima da expansão. E assim foi.",
        },
        {
          number: 8,
          text: "E Deus chamou à expansão Céu. E houve tarde e manhã, o segundo dia.",
        },
        {
          number: 9,
          text: "E disse Deus: Ajuntem-se as águas debaixo dos céus num só lugar; e apareça a porção seca. E assim foi.",
        },
        {
          number: 10,
          text: "E Deus chamou à porção seca Terra; e ao ajuntamento das águas chamou Mares. E viu Deus que era bom.",
        },
        {
          number: 11,
          text: "E disse Deus: Produza a terra erva verde, erva que dê sementes, e árvore frutífera que dê fruto segundo a sua espécie, cuja sementes estejam nela sobre a terra. E assim foi.",
        },
        {
          number: 12,
          text: "E a terra produziu erva verde, erva dando sementes segundo a sua espécie, e árvore frutífera dando fruto, cuja sementes estavam nele, segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 13,
          text: "E houve tarde e manhã, o terceiro dia.",
        },
        {
          number: 14,
          text: "E disse Deus: Haja luminares na expansão dos céus, para fazer separação entre o dia e a noite; e sejam eles para sinais e para estações, e para dias e anos.",
        },
        {
          number: 15,
          text: "E sejam para luminares na expansão dos céus, para alumiar a terra. E assim foi.",
        },
        {
          number: 16,
          text: "Então fez Deus os dois grandes luminares, o luminar maior para governar o dia, e o luminar menor para governar a noite; e as estrelas.",
        },
        {
          number: 17,
          text: "E Deus os colocou na expansão dos céus, para alumiar a terra,",
        },
        {
          number: 18,
          text: "e para governar o dia e a noite, e para fazer separação entre a luz e as trevas. E viu Deus que era bom.",
        },
        {
          number: 19,
          text: "E houve tarde e manhã, o quarto dia.",
        },
        {
          number: 20,
          text: "E disse Deus: Produzam as águas abundantemente a criatura movente, alma vivente; e voem as aves sobre a terra sobre a face da expansão dos céus.",
        },
        {
          number: 21,
          text: "E Deus criou as grandes baleias, e toda criatura vivente que se move, que as águas produziram abundantemente, segundo a sua espécie; e toda ave de asa, segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 22,
          text: "E Deus os abençoou, dizendo: Frutificai, e multiplicai-vos, e enchei as águas nos mares; e as aves se multipliquem na terra.",
        },
        {
          number: 23,
          text: "E houve tarde e manhã, o quinto dia.",
        },
        {
          number: 24,
          text: "E disse Deus: Produza a terra criatura vivente segundo a sua espécie; gado, e réptil, e besta da terra, segundo a sua espécie. E assim foi.",
        },
        {
          number: 25,
          text: "E fez Deus a besta da terra segundo a sua espécie, e o gado segundo a sua espécie, e todo réptil da terra segundo a sua espécie. E viu Deus que era bom.",
        },
        {
          number: 26,
          text: "E disse Deus: Façamos o homem à nossa imagem, conforme a nossa semelhança; e domine sobre os peixes do mar, e sobre as aves dos céus, e sobre o gado, e sobre toda a terra, e sobre todo réptil que se move sobre a terra.",
        },
        {
          number: 27,
          text: "E criou Deus o homem à sua imagem, à imagem de Deus o criou; macho e fêmea os criou.",
        },
        {
          number: 28,
          text: "E Deus os abençoou, e Deus lhes disse: Frutificai, e multiplicai-vos, e enchei a terra, e sujeitai-a; e dominai sobre os peixes do mar, e sobre as aves dos céus, e sobre todo animal que se move sobre a terra.",
        },
        {
          number: 29,
          text: "E disse Deus: Eis que vos tenho dado toda erva que dá sementes, que está sobre a face de toda a terra; e toda árvore, em que há fruto de árvore que dá sementes, vos será para mantimento.",
        },
        {
          number: 30,
          text: "E a todo animal da terra, e a toda ave dos céus, e a todo réptil da terra, em que há alma vivente, toda erva verde será para mantimento. E assim foi.",
        },
        {
          number: 31,
          text: "E viu Deus tudo quanto tinha feito, e eis que era muito bom; e houve tarde e manhã, o sexto dia.",
        },
      ],
    },
  },
};

export function getMockChapterData(
  version: string,
  chapter: number
): ChapterData | null {
  const versionData = mockBibleData[version];
  if (!versionData) return null;
  return versionData[chapter] || null;
}

