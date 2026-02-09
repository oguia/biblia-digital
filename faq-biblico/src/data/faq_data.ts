export interface FAQItem {
  id: number;
  category: string;
  question: string;
  answer: string;
  verse: string;
  reference: string;
}

export const faqData: FAQItem[] = [
  // 1. Deus
  {
    id: 1,
    category: "Deus",
    question: "Deus existe mesmo?",
    answer: "A Bíblia afirma que a criação revela a existência de Deus. O universo complexo e ordenado aponta para um Criador inteligente e poderoso.",
    verse: "Pois os seus atributos invisíveis, o seu eterno poder e divindade, são claramente vistos desde a criação do mundo, sendo percebidos mediante as coisas criadas...",
    reference: "Romanos 1:20"
  },
  {
    id: 2,
    category: "Deus",
    question: "Quem criou Deus?",
    answer: "Deus é eterno, o que significa que Ele não teve início e não terá fim. Ele é a causa primeira de tudo e existe fora do tempo.",
    verse: "Antes que os montes nascessem e se formassem a terra e o mundo, de eternidade a eternidade, tu és Deus.",
    reference: "Salmos 90:2"
  },
  {
    id: 3,
    category: "Deus",
    question: "Deus sabe de tudo?",
    answer: "Sim, Deus é onisciente. Ele conhece o passado, o presente e o futuro, bem como os pensamentos e intenções de cada coração.",
    verse: "Grande é o nosso Senhor e de grande poder; o seu entendimento não tem limites.",
    reference: "Salmos 147:5"
  },
  {
    id: 4,
    category: "Deus",
    question: "Deus pode fazer qualquer coisa?",
    answer: "Deus é onipotente e pode fazer todas as coisas que são consistentes com Sua natureza. Ele não pode mentir ou ser tentado pelo mal.",
    verse: "Para o homem é impossível, mas para Deus não; todas as coisas são possíveis para Deus.",
    reference: "Marcos 10:27"
  },
  {
    id: 5,
    category: "Deus",
    question: "Deus se importa comigo?",
    answer: "Sim, Deus ama profundamente cada pessoa. Ele conhece cada detalhe da sua vida e deseja ter um relacionamento pessoal com você.",
    verse: "Lancem sobre ele toda a sua ansiedade, porque ele tem cuidado de vocês.",
    reference: "1 Pedro 5:7"
  },
  {
    id: 6,
    category: "Deus",
    question: "Por que não podemos ver a Deus?",
    answer: "Deus é Espírito e habita em luz inacessível. Nossa natureza humana limitada não pode compreender plenamente Sua glória, mas podemos conhecê-Lo através de Jesus.",
    verse: "Deus é espírito, e é necessário que os seus adoradores o adorem em espírito e em verdade.",
    reference: "João 4:24"
  },
  {
    id: 7,
    category: "Deus",
    question: "Deus muda de ideia?",
    answer: "O caráter e os propósitos de Deus são imutáveis. Embora Ele possa responder a orações e arrependimento, Sua natureza perfeita permanece a mesma.",
    verse: "Eu, o Senhor, não mudo.",
    reference: "Malaquias 3:6"
  },
  {
    id: 8,
    category: "Deus",
    question: "Onde Deus está?",
    answer: "Deus é onipresente, o que significa que Ele está em todos os lugares ao mesmo tempo. Não há lugar onde possamos nos esconder de Sua presença.",
    verse: "Para onde poderia eu escapar do teu Espírito? Para onde poderia fugir da tua presença?",
    reference: "Salmos 139:7"
  },
  {
    id: 9,
    category: "Deus",
    question: "Deus é três ou um?",
    answer: "A Bíblia ensina a Trindade: um único Deus que existe eternamente em três pessoas distintas: Pai, Filho e Espírito Santo.",
    verse: "Ide, portanto, fazei discípulos de todas as nações, batizando-os em nome do Pai, e do Filho, e do Espírito Santo.",
    reference: "Mateus 28:19"
  },
  {
    id: 10,
    category: "Deus",
    question: "Qual é o nome de Deus?",
    answer: "Deus se revelou a Moisés como 'EU SOU O QUE SOU' (Yahweh). Ele também é chamado por muitos títulos que descrevem Seu caráter, como Senhor, Pai e Todo-Poderoso.",
    verse: "Disse Deus a Moisés: EU SOU O QUE SOU.",
    reference: "Êxodo 3:14"
  },
  // 2. Jesus Cristo
  {
    id: 11,
    category: "Jesus",
    question: "Jesus é Deus?",
    answer: "Sim, Jesus é Deus encarnado. Ele possui toda a plenitude da divindade em forma humana e é um com o Pai.",
    verse: "No princípio era aquele que é a Palavra. Ele estava com Deus, e era Deus.",
    reference: "João 1:1"
  },
  {
    id: 12,
    category: "Jesus",
    question: "Por que Jesus teve que morrer?",
    answer: "Jesus morreu para pagar a penalidade pelos nossos pecados. Seu sacrifício perfeito satisfez a justiça de Deus e nos reconciliou com Ele.",
    verse: "Mas ele foi transpassado por causa das nossas transgressões, foi esmagado por causa de nossas iniquidades...",
    reference: "Isaías 53:5"
  },
  {
    id: 13,
    category: "Jesus",
    question: "Jesus ressuscitou de verdade?",
    answer: "Sim, a ressurreição de Jesus é um fato histórico. Ele venceu a morte, provando Sua divindade e garantindo a nossa futura ressurreição.",
    verse: "Ele não está aqui; ressuscitou, como tinha dito.",
    reference: "Mateus 28:6"
  },
  {
    id: 14,
    category: "Jesus",
    question: "Jesus pecou alguma vez?",
    answer: "Não, Jesus viveu uma vida perfeita e sem pecado, embora tenha sido tentado em todas as coisas como nós.",
    verse: "Pois não temos um sumo sacerdote que não possa compadecer-se das nossas fraquezas, mas sim alguém que, como nós, passou por todo tipo de tentação, porém, sem pecado.",
    reference: "Hebreus 4:15"
  },
  {
    id: 15,
    category: "Jesus",
    question: "Jesus é o único caminho para o céu?",
    answer: "Sim, Jesus declarou ser o único mediador entre Deus e os homens. Ninguém pode chegar ao Pai senão por Ele.",
    verse: "Respondeu Jesus: 'Eu sou o caminho, a verdade e a vida. Ninguém vem ao Pai, a não ser por mim'.",
    reference: "João 14:6"
  },
  {
    id: 16,
    category: "Jesus",
    question: "Por que Jesus nasceu de uma virgem?",
    answer: "O nascimento virginal permitiu que Jesus fosse plenamente humano, mas sem a natureza pecaminosa herdada de Adão, sendo o Filho de Deus.",
    verse: "O anjo respondeu: 'O Espírito Santo virá sobre você, e o poder do Altíssimo a cobrirá com a sua sombra. Assim, aquele que há de nascer será chamado Santo, Filho de Deus'.",
    reference: "Lucas 1:35"
  },
  {
    id: 17,
    category: "Jesus",
    question: "O que Jesus está fazendo agora?",
    answer: "Jesus está à direita de Deus Pai, intercedendo por nós e preparando um lugar para os Seus seguidores.",
    verse: "Cristo Jesus, que morreu, e mais, que ressuscitou e está à direita de Deus, e também intercede por nós.",
    reference: "Romanos 8:34"
  },
  {
    id: 18,
    category: "Jesus",
    question: "Jesus voltará?",
    answer: "Sim, Jesus prometeu voltar gloriosamente para julgar os vivos e os mortos e estabelecer Seu reino eterno.",
    verse: "E, se eu for e lhes preparar lugar, voltarei e os levarei para mim, para que vocês estejam onde eu estiver.",
    reference: "João 14:3"
  },
  {
    id: 19,
    category: "Jesus",
    question: "Por que Jesus fez milagres?",
    answer: "Os milagres de Jesus confirmavam Sua identidade como o Messias enviado por Deus e demonstravam Sua compaixão pelo sofrimento humano.",
    verse: "Jesus realizou na presença dos seus discípulos muitos outros sinais miraculosos... Mas estes foram escritos para que vocês creiam que Jesus é o Cristo, o Filho de Deus...",
    reference: "João 20:30-31"
  },
  {
    id: 20,
    category: "Jesus",
    question: "O que significa 'Messias'?",
    answer: "'Messias' (ou Cristo) significa 'Ungido'. Refere-se ao Salvador prometido por Deus no Antigo Testamento para resgatar a humanidade.",
    verse: "A mulher disse: 'Eu sei que o Messias (chamado Cristo) está para vir...' Disse Jesus: 'Sou eu, que falo com você'.",
    reference: "João 4:25-26"
  },
  // 3. Salvação
  {
    id: 21,
    category: "Salvação",
    question: "O que devo fazer para ser salvo?",
    answer: "Para ser salvo, você deve crer no Senhor Jesus Cristo, confiando nEle como seu Salvador e Senhor, e arrependendo-se de seus pecados.",
    verse: "Eles responderam: 'Creia no Senhor Jesus, e serão salvos, você e os de sua casa'.",
    reference: "Atos 16:31"
  },
  {
    id: 22,
    category: "Salvação",
    question: "A salvação é de graça?",
    answer: "Sim, a salvação é um presente (graça) de Deus. Não podemos comprá-la ou merecê-la por nossas boas obras.",
    verse: "Pois vocês são salvos pela graça, por meio da fé, e isto não vem de vocês, é dom de Deus; não por obras, para que ninguém se glorie.",
    reference: "Efésios 2:8-9"
  },
  {
    id: 23,
    category: "Salvação",
    question: "Posso perder a salvação?",
    answer: "A Bíblia ensina que aqueles que verdadeiramente nasceram de novo estão seguros nas mãos de Deus. Ele garante completar a obra que começou.",
    verse: "Eu lhes dou a vida eterna, e elas jamais perecerão; ninguém as poderá arrancar da minha mão.",
    reference: "João 10:28"
  },
  {
    id: 24,
    category: "Salvação",
    question: "O que é arrependimento?",
    answer: "Arrependimento é uma mudança de mente e coração que leva a uma mudança de atitude. É reconhecer o pecado, sentir tristeza por ele e decidir abandoná-lo.",
    verse: "Arrependam-se, pois, e voltem-se para Deus, para que os seus pecados sejam cancelados.",
    reference: "Atos 3:19"
  },
  {
    id: 25,
    category: "Salvação",
    question: "Preciso ser batizado para ser salvo?",
    answer: "O batismo é um ato de obediência e testemunho público, mas não é o que salva. A salvação vem pela fé em Cristo (ex: o ladrão na cruz).",
    verse: "Porque Cristo não me enviou para batizar, mas para pregar o evangelho...",
    reference: "1 Coríntios 1:17"
  },
  {
    id: 26,
    category: "Salvação",
    question: "Todos serão salvos no final?",
    answer: "Não. A Bíblia é clara ao dizer que apenas aqueles que aceitam a oferta de perdão de Deus através de Cristo terão a vida eterna.",
    verse: "Quem nele crê não é condenado, mas quem não crê já está condenado, por não crer no nome do Filho Unigênito de Deus.",
    reference: "João 3:18"
  },
  {
    id: 27,
    category: "Salvação",
    question: "O que é nascer de novo?",
    answer: "Nascer de novo é a regeneração espiritual operada pelo Espírito Santo, que nos dá uma nova natureza e nos torna filhos de Deus.",
    verse: "Respondeu Jesus: 'Digo-lhe a verdade: Ninguém pode ver o Reino de Deus, se não nascer de novo'.",
    reference: "João 3:3"
  },
  {
    id: 28,
    category: "Salvação",
    question: "Como sei que sou salvo?",
    answer: "A certeza da salvação vem do testemunho do Espírito Santo em nós, da mudança de vida e da confiança nas promessas da Palavra de Deus.",
    verse: "O próprio Espírito testemunha ao nosso espírito que somos filhos de Deus.",
    reference: "Romanos 8:16"
  },
  {
    id: 29,
    category: "Salvação",
    question: "Deus perdoa qualquer pecado?",
    answer: "Sim, o sacrifício de Jesus é suficiente para perdoar qualquer pecado, desde que haja arrependimento sincero e fé.",
    verse: "Se confessarmos os nossos pecados, ele é fiel e justo para perdoar os nossos pecados e nos purificar de toda injustiça.",
    reference: "1 João 1:9"
  },
  {
    id: 30,
    category: "Salvação",
    question: "O que é a graça de Deus?",
    answer: "Graça é o favor imerecido de Deus. É Ele nos dando o que não merecemos (salvação) em vez do que merecemos (julgamento).",
    verse: "Mas ele me disse: 'Minha graça é suficiente para você, pois o meu poder se aperfeiçoa na fraqueza'.",
    reference: "2 Coríntios 12:9"
  },
  // 4. Espírito Santo
  {
    id: 31,
    category: "Espírito Santo",
    question: "Quem é o Espírito Santo?",
    answer: "O Espírito Santo é a terceira pessoa da Trindade, Deus pleno. Ele habita nos crentes, ensina, consola e capacita.",
    verse: "E eu pedirei ao Pai, e ele lhes dará outro Conselheiro para estar com vocês para sempre, o Espírito da verdade.",
    reference: "João 14:16-17"
  },
  {
    id: 32,
    category: "Espírito Santo",
    question: "O que o Espírito Santo faz?",
    answer: "Ele convence o mundo do pecado, guia os crentes em toda a verdade, concede dons espirituais e produz o fruto do caráter cristão.",
    verse: "Quando ele vier, convencerá o mundo do pecado, da justiça e do juízo.",
    reference: "João 16:8"
  },
  {
    id: 33,
    category: "Espírito Santo",
    question: "O que é o batismo no Espírito Santo?",
    answer: "É um revestimento de poder para testemunhar de Jesus. Pode ocorrer na conversão ou como uma experiência subsequente de busca por mais de Deus.",
    verse: "Mas receberão poder quando o Espírito Santo descer sobre vocês, e serão minhas testemunhas...",
    reference: "Atos 1:8"
  },
  {
    id: 34,
    category: "Espírito Santo",
    question: "Quais são os dons do Espírito?",
    answer: "São capacidades sobrenaturais dadas pelo Espírito para a edificação da igreja, como sabedoria, cura, profecia, línguas, entre outros.",
    verse: "A cada um, porém, é dada a manifestação do Espírito, visando ao bem comum.",
    reference: "1 Coríntios 12:7"
  },
  {
    id: 35,
    category: "Espírito Santo",
    question: "O que é o fruto do Espírito?",
    answer: "São as virtudes de caráter que o Espírito Santo produz na vida do cristão: amor, alegria, paz, paciência, amabilidade, bondade, fidelidade, mansidão e domínio próprio.",
    verse: "Mas o fruto do Espírito é amor, alegria, paz, paciência, amabilidade, bondade, fidelidade, mansidão e domínio próprio.",
    reference: "Gálatas 5:22-23"
  },
  {
    id: 36,
    category: "Espírito Santo",
    question: "O Espírito Santo pode ser entristecido?",
    answer: "Sim, como uma Pessoa divina, o Espírito pode ser entristecido quando pecamos ou resistimos à Sua direção.",
    verse: "Não entristeçam o Espírito Santo de Deus, com o qual vocês foram selados para o dia da redenção.",
    reference: "Efésios 4:30"
  },
  {
    id: 37,
    category: "Espírito Santo",
    question: "Todo cristão tem o Espírito Santo?",
    answer: "Sim, a Bíblia diz que se alguém não tem o Espírito de Cristo, não pertence a Ele. O Espírito passa a habitar em nós no momento da salvação.",
    verse: "E, se alguém não tem o Espírito de Cristo, não pertence a Cristo.",
    reference: "Romanos 8:9"
  },
  {
    id: 38,
    category: "Espírito Santo",
    question: "O que significa andar no Espírito?",
    answer: "Significa viver sob a direção e o poder do Espírito Santo, obedecendo à Palavra de Deus e não satisfazendo os desejos da carne.",
    verse: "Por isso digo: vivam pelo Espírito, e de modo nenhum satisfarão os desejos da carne.",
    reference: "Gálatas 5:16"
  },
  {
    id: 39,
    category: "Espírito Santo",
    question: "O Espírito Santo é uma força ou uma pessoa?",
    answer: "O Espírito Santo é uma Pessoa. Ele tem intelecto, emoções e vontade. Ele fala, ensina e pode ser mentido (Atos 5:3).",
    verse: "Enquanto adoravam o Senhor e jejuavam, disse o Espírito Santo: 'Separem-me Barnabé e Saulo para a obra a que os tenho chamado'.",
    reference: "Atos 13:2"
  },
  {
    id: 40,
    category: "Espírito Santo",
    question: "Qual a função do Espírito na oração?",
    answer: "Ele nos ajuda em nossa fraqueza, intercedendo por nós com gemidos inexprimíveis quando não sabemos como orar.",
    verse: "Da mesma forma o Espírito nos ajuda em nossa fraqueza, pois não sabemos como orar, mas o próprio Espírito intercede por nós...",
    reference: "Romanos 8:26"
  },
  // 5. Sofrimento e Mal
  {
    id: 41,
    category: "Sofrimento",
    question: "Por que Deus permite o sofrimento?",
    answer: "O sofrimento entrou no mundo pelo pecado. Deus permite para propósitos maiores, como nosso crescimento, disciplina ou para Sua glória final.",
    verse: "Sabemos que Deus age em todas as coisas para o bem daqueles que o amam...",
    reference: "Romanos 8:28"
  },
  {
    id: 42,
    category: "Sofrimento",
    question: "De onde vem o mal?",
    answer: "O mal não foi criado por Deus, mas é a ausência do bem e resultado da desobediência (pecado) e da rebelião de Satanás.",
    verse: "Portanto, da mesma forma como o pecado entrou no mundo por um homem, e pelo pecado a morte...",
    reference: "Romanos 5:12"
  },
  {
    id: 43,
    category: "Sofrimento",
    question: "Por que pessoas boas sofrem?",
    answer: "Num mundo caído, todos estão sujeitos ao sofrimento. Às vezes, o sofrimento testa e refina a fé dos justos (ex: Jó).",
    verse: "Muitas são as aflições do justo, mas o Senhor o livra de todas.",
    reference: "Salmos 34:19"
  },
  {
    id: 44,
    category: "Sofrimento",
    question: "O diabo existe?",
    answer: "Sim, Satanás é um anjo caído real, inimigo de Deus e dos homens, que procura roubar, matar e destruir.",
    verse: "O ladrão vem apenas para furtar, matar e destruir; eu vim para que tenham vida, e a tenham plenamente.",
    reference: "João 10:10"
  },
  {
    id: 45,
    category: "Sofrimento",
    question: "Como vencer a tentação?",
    answer: "Resistindo ao diabo, sujeitando-se a Deus, orando, vigiando e usando a Palavra de Deus como arma.",
    verse: "Portanto, submetam-se a Deus. Resistam ao diabo, e ele fugirá de vocês.",
    reference: "Tiago 4:7"
  },
  {
    id: 46,
    category: "Sofrimento",
    question: "Deus me castiga?",
    answer: "Deus disciplina seus filhos por amor, para corrigir e ensinar, mas não condena aqueles que estão em Cristo.",
    verse: "Pois o Senhor disciplina a quem ama, e castiga todo aquele a quem aceita como filho.",
    reference: "Hebreus 12:6"
  },
  {
    id: 47,
    category: "Sofrimento",
    question: "Onde está Deus quando dói?",
    answer: "Ele está perto dos que têm o coração quebrantado. Ele sofre conosco e nos oferece consolo e força.",
    verse: "Perto está o Senhor dos que têm o coração quebrantado e salva os de espírito oprimido.",
    reference: "Salmos 34:18"
  },
  {
    id: 48,
    category: "Sofrimento",
    question: "Posso ficar com raiva de Deus?",
    answer: "É normal sentir angústia e expressar isso a Deus (lamento). Ele suporta nossa sinceridade, mas devemos confiar em Sua soberania.",
    verse: "Derramem diante dele o coração, pois ele é o nosso refúgio.",
    reference: "Salmos 62:8"
  },
  {
    id: 49,
    category: "Sofrimento",
    question: "O que é o pecado?",
    answer: "Pecado é qualquer pensamento, palavra ou ação que viola a lei de Deus. É errar o alvo da santidade divina.",
    verse: "Todo aquele que pratica o pecado transgride a Lei; de fato, o pecado é a transgressão da Lei.",
    reference: "1 João 3:4"
  },
  {
    id: 50,
    category: "Sofrimento",
    question: "Como perdoar quem me feriu?",
    answer: "O perdão é uma decisão, não apenas um sentimento. Perdoamos porque Deus nos perdoou em Cristo.",
    verse: "Sejam bondosos e compassivos uns para com os outros, perdoando-se mutuamente, assim como Deus os perdoou em Cristo.",
    reference: "Efésios 4:32"
  },
  // 6. Vida Cristã
  {
    id: 51,
    category: "Vida Cristã",
    question: "Como orar?",
    answer: "Orar é conversar com Deus. Fale com sinceridade, em nome de Jesus, agradecendo, confessando e pedindo.",
    verse: "Não andem ansiosos por coisa alguma, mas em tudo, pela oração e súplicas, e com ação de graças, apresentem seus pedidos a Deus.",
    reference: "Filipenses 4:6"
  },
  {
    id: 52,
    category: "Vida Cristã",
    question: "Como ler a Bíblia?",
    answer: "Leia com regularidade, oração e desejo de obedecer. Comece pelos Evangelhos (ex: João) para conhecer Jesus.",
    verse: "Toda a Escritura é inspirada por Deus e útil para o ensino, para a repreensão, para a correção e para a instrução na justiça.",
    reference: "2 Timóteo 3:16"
  },
  {
    id: 53,
    category: "Vida Cristã",
    question: "Preciso ir à igreja?",
    answer: "Congregar é vital para o crescimento, comunhão e encorajamento mútuo. A igreja é o corpo de Cristo.",
    verse: "Não deixemos de reunir-nos como igreja, segundo o costume de alguns, mas procuremos encorajar-nos uns aos outros...",
    reference: "Hebreus 10:25"
  },
  {
    id: 54,
    category: "Vida Cristã",
    question: "O cristão pode beber álcool?",
    answer: "A Bíblia condena a embriaguez, não necessariamente o consumo moderado. Porém, recomenda-se sabedoria e não causar escândalo.",
    verse: "Não se embriaguem com vinho, que leva à libertinagem, mas deixem-se encher pelo Espírito.",
    reference: "Efésios 5:18"
  },
  {
    id: 55,
    category: "Vida Cristã",
    question: "O que é dízimo?",
    answer: "É a entrega da décima parte da renda para a obra de Deus. É um ato de adoração, gratidão e sustento do ministério.",
    verse: "Tragam o dízimo todo ao depósito do templo, para que haja alimento em minha casa.",
    reference: "Malaquias 3:10"
  },
  {
    id: 56,
    category: "Vida Cristã",
    question: "Como saber a vontade de Deus?",
    answer: "A vontade de Deus é revelada na Bíblia. Busque-a através da oração, conselho sábio e direção do Espírito Santo.",
    verse: "Não se amoldem ao padrão deste mundo, mas transformem-se pela renovação da sua mente, para que sejam capazes de experimentar e comprovar a boa, agradável e perfeita vontade de Deus.",
    reference: "Romanos 12:2"
  },
  {
    id: 57,
    category: "Vida Cristã",
    question: "O cristão pode julgar?",
    answer: "Não devemos julgar hipocritamente ou para condenar. Devemos exercer discernimento, mas com amor e misericórdia.",
    verse: "Não julguem, para que vocês não sejam julgados.",
    reference: "Mateus 7:1"
  },
  {
    id: 58,
    category: "Vida Cristã",
    question: "Como vencer a ansiedade?",
    answer: "Confiando no cuidado de Deus, orando e entregando as preocupações a Ele, que promete paz.",
    verse: "E a paz de Deus, que excede todo o entendimento, guardará os seus corações e as suas mentes em Cristo Jesus.",
    reference: "Filipenses 4:7"
  },
  {
    id: 59,
    category: "Vida Cristã",
    question: "O que é santidade?",
    answer: "Santidade é ser separado do pecado e consagrado a Deus. É um processo contínuo de se tornar mais parecido com Jesus.",
    verse: "Mas, assim como é santo aquele que os chamou, sejam santos vocês também em tudo o que fizerem.",
    reference: "1 Pedro 1:15"
  },
  {
    id: 60,
    category: "Vida Cristã",
    question: "Como evangelizar?",
    answer: "Compartilhando as boas novas de Jesus com amor, através do testemunho de vida e da explicação do evangelho.",
    verse: "Vão pelo mundo todo e preguem o evangelho a todas as pessoas.",
    reference: "Marcos 16:15"
  },
  // 7. Fim dos Tempos
  {
    id: 61,
    category: "Fim dos Tempos",
    question: "O mundo vai acabar?",
    answer: "O mundo como conhecemos passará, mas Deus criará novos céus e nova terra onde habita a justiça.",
    verse: "Vi novos céus e nova terra, pois o primeiro céu e a primeira terra tinham passado...",
    reference: "Apocalipse 21:1"
  },
  {
    id: 62,
    category: "Fim dos Tempos",
    question: "O que acontece depois da morte?",
    answer: "A Bíblia ensina que após a morte vem o juízo. Os salvos estarão com Cristo, e os não salvos enfrentarão a separação eterna.",
    verse: "Da mesma forma, como o homem está destinado a morrer uma só vez e depois disso enfrentar o juízo...",
    reference: "Hebreus 9:27"
  },
  {
    id: 63,
    category: "Fim dos Tempos",
    question: "O que é o céu?",
    answer: "É o lugar de habitação de Deus, onde não haverá mais dor, morte ou choro, e os salvos viverão em alegria eterna.",
    verse: "Ele enxugará dos seus olhos toda lágrima. Não haverá mais morte, nem tristeza, nem choro, nem dor...",
    reference: "Apocalipse 21:4"
  },
  {
    id: 64,
    category: "Fim dos Tempos",
    question: "O inferno existe?",
    answer: "Sim, Jesus falou sobre o inferno como um lugar real de tormento e separação eterna de Deus para aqueles que rejeitam a salvação.",
    verse: "E estes irão para o castigo eterno, mas os justos para a vida eterna.",
    reference: "Mateus 25:46"
  },
  {
    id: 65,
    category: "Fim dos Tempos",
    question: "O que é o arrebatamento?",
    answer: "É o evento em que Jesus levará sua Igreja da terra para encontrá-Lo nos ares antes do julgamento final.",
    verse: "Depois nós, os que ficarmos vivos, seremos arrebatados com eles nas nuvens, para o encontro com o Senhor nos ares.",
    reference: "1 Tessalonicenses 4:17"
  },
  {
    id: 66,
    category: "Fim dos Tempos",
    question: "Quem é o Anticristo?",
    answer: "Uma figura que surgirá no fim dos tempos, opondo-se a Cristo e enganando muitos, antes de ser derrotado por Jesus.",
    verse: "Filhinhos, esta é a última hora; e, assim como vocês ouviram que o anticristo está vindo, já agora muitos anticristos têm surgido.",
    reference: "1 João 2:18"
  },
  {
    id: 67,
    category: "Fim dos Tempos",
    question: "Quando será o fim?",
    answer: "Ninguém sabe o dia nem a hora, apenas o Pai. Devemos viver preparados e vigilantes.",
    verse: "Quanto ao dia e à hora ninguém sabe, nem os anjos dos céus, nem o Filho, senão somente o Pai.",
    reference: "Mateus 24:36"
  },
  {
    id: 68,
    category: "Fim dos Tempos",
    question: "Haverá um julgamento?",
    answer: "Sim, todos comparecerão diante do tribunal de Cristo para prestar contas de suas vidas.",
    verse: "Pois todos nós devemos comparecer perante o tribunal de Cristo...",
    reference: "2 Coríntios 5:10"
  },
  {
    id: 69,
    category: "Fim dos Tempos",
    question: "O que é a Grande Tribulação?",
    answer: "Um período de grande sofrimento na terra antes da volta de Cristo, como nunca houve antes.",
    verse: "Porque haverá então grande tribulação, como nunca houve desde o princípio do mundo até agora...",
    reference: "Mateus 24:21"
  },
  {
    id: 70,
    category: "Fim dos Tempos",
    question: "Como será o corpo ressuscitado?",
    answer: "Será um corpo glorioso, incorruptível e imortal, semelhante ao corpo ressuscitado de Jesus.",
    verse: "Semeia-se corpo natural, ressuscita corpo espiritual.",
    reference: "1 Coríntios 15:44"
  },
  // 8. Família e Relacionamentos
  {
    id: 71,
    category: "Família",
    question: "O que Deus diz sobre o casamento?",
    answer: "É uma aliança sagrada entre um homem e uma mulher, instituída por Deus para companheirismo, procriação e reflexo do amor de Cristo pela Igreja.",
    verse: "Por essa razão, o homem deixará pai e mãe e se unirá à sua mulher, e eles se tornarão uma só carne.",
    reference: "Gênesis 2:24"
  },
  {
    id: 72,
    category: "Família",
    question: "O divórcio é permitido?",
    answer: "Deus odeia o divórcio, mas a Bíblia permite em casos de imoralidade sexual ou abandono por descrente. O ideal é sempre a reconciliação.",
    verse: "Portanto, o que Deus uniu, ninguém o separe.",
    reference: "Marcos 10:9"
  },
  {
    id: 73,
    category: "Família",
    question: "Como educar os filhos?",
    answer: "Com amor e disciplina, ensinando-lhes os caminhos do Senhor desde cedo.",
    verse: "Eduque a criança no caminho em que deve andar, e até o fim da vida não se desviará dele.",
    reference: "Provérbios 22:6"
  },
  {
    id: 74,
    category: "Família",
    question: "O cristão pode namorar descrente?",
    answer: "A Bíblia adverte contra o 'jugo desigual', ou seja, unir-se profundamente com quem não compartilha da mesma fé.",
    verse: "Não se ponham em jugo desigual com descrentes.",
    reference: "2 Coríntios 6:14"
  },
  {
    id: 75,
    category: "Família",
    question: "Como honrar os pais?",
    answer: "Respeitando, obedecendo (enquanto menores) e cuidando deles na velhice. É o primeiro mandamento com promessa.",
    verse: "Honra teu pai e tua mãe, para que tenhas longa vida na terra que o Senhor, o teu Deus, te dá.",
    reference: "Êxodo 20:12"
  },
  {
    id: 76,
    category: "Família",
    question: "O que a Bíblia diz sobre sexo?",
    answer: "O sexo é um presente de Deus para ser desfrutado exclusivamente dentro do casamento.",
    verse: "O casamento deve ser honrado por todos; o leito conjugal, puro...",
    reference: "Hebreus 13:4"
  },
  {
    id: 77,
    category: "Família",
    question: "Como lidar com a ira no casamento?",
    answer: "Não deixando o sol se pôr sobre a ira, resolvendo conflitos rapidamente e com mansidão.",
    verse: "Quando vocês ficarem irados, não pequem. Apaziguem a sua ira antes que o sol se ponha.",
    reference: "Efésios 4:26"
  },
  {
    id: 78,
    category: "Família",
    question: "O marido é superior à esposa?",
    answer: "Não. Ambos têm igual valor diante de Deus. O marido tem o papel de liderança servidora, amando a esposa como Cristo amou a igreja.",
    verse: "Maridos, amem suas mulheres, assim como Cristo amou a igreja e entregou-se a si mesmo por ela.",
    reference: "Efésios 5:25"
  },
  {
    id: 79,
    category: "Família",
    question: "A mulher pode trabalhar fora?",
    answer: "A Bíblia elogia a mulher virtuosa que é empreendedora e cuida bem de sua casa (Provérbios 31).",
    verse: "Ela avalia um campo e o compra; com o que ganha planta uma vinha.",
    reference: "Provérbios 31:16"
  },
  {
    id: 80,
    category: "Família",
    question: "Como lidar com parentes difíceis?",
    answer: "Com paciência, amor e estabelecendo limites saudáveis, buscando a paz sempre que possível.",
    verse: "Façam todo o possível para viver em paz com todos.",
    reference: "Romanos 12:18"
  },
  // 9. Curiosidades Bíblicas
  {
    id: 81,
    category: "Curiosidades",
    question: "Quem foi o homem mais velho da Bíblia?",
    answer: "Matusalém, que viveu 969 anos.",
    verse: "Ao todo, Matusalém viveu novecentos e sessenta e nove anos, e morreu.",
    reference: "Gênesis 5:27"
  },
  {
    id: 82,
    category: "Curiosidades",
    question: "Quem escreveu a Bíblia?",
    answer: "Cerca de 40 autores diferentes, ao longo de 1500 anos, todos inspirados pelo Espírito Santo.",
    verse: "Porque a profecia nunca foi produzida por vontade de homem algum, mas homens falaram da parte de Deus, movidos pelo Espírito Santo.",
    reference: "2 Pedro 1:21"
  },
  {
    id: 83,
    category: "Curiosidades",
    question: "Qual o maior versículo da Bíblia?",
    answer: "Ester 8:9 é o versículo mais longo na versão original.",
    verse: "Então foram chamados os escribas do rei...",
    reference: "Ester 8:9"
  },
  {
    id: 84,
    category: "Curiosidades",
    question: "Qual o menor versículo da Bíblia?",
    answer: "Na maioria das versões, é 'Jesus chorou' (João 11:35).",
    verse: "Jesus chorou.",
    reference: "João 11:35"
  },
  {
    id: 85,
    category: "Curiosidades",
    question: "O que é o Apócrifo?",
    answer: "Livros escritos no período intertestamentário que não foram considerados inspirados e não fazem parte do cânon bíblico protestante.",
    verse: "Toda a Escritura é divinamente inspirada...",
    reference: "2 Timóteo 3:16"
  },
  {
    id: 86,
    category: "Curiosidades",
    question: "O que são os Evangelhos?",
    answer: "São os quatro primeiros livros do Novo Testamento (Mateus, Marcos, Lucas e João) que narram a vida e obra de Jesus.",
    verse: "Princípio do evangelho de Jesus Cristo, o Filho de Deus.",
    reference: "Marcos 1:1"
  },
  {
    id: 87,
    category: "Curiosidades",
    question: "O que é o Pentateuco?",
    answer: "São os cinco primeiros livros da Bíblia (Gênesis a Deuteronômio), escritos por Moisés.",
    verse: "Disse-lhes: 'Foi isso que eu lhes falei enquanto ainda estava com vocês: Era necessário que se cumprisse tudo o que a meu respeito estava escrito na Lei de Moisés...'",
    reference: "Lucas 24:44"
  },
  {
    id: 88,
    category: "Curiosidades",
    question: "Quem foi Paulo?",
    answer: "Um apóstolo que perseguiu a igreja, mas se converteu e escreveu grande parte do Novo Testamento.",
    verse: "Eu sou o menor dos apóstolos e nem sequer mereço ser chamado apóstolo, porque persegui a igreja de Deus.",
    reference: "1 Coríntios 15:9"
  },
  {
    id: 89,
    category: "Curiosidades",
    question: "O que é o Antigo Testamento?",
    answer: "A parte da Bíblia escrita antes de Jesus, contendo a lei, a história de Israel e as profecias.",
    verse: "Pois tudo o que foi escrito no passado, foi escrito para nos ensinar...",
    reference: "Romanos 15:4"
  },
  {
    id: 90,
    category: "Curiosidades",
    question: "A Bíblia é verdadeira?",
    answer: "Sim, a arqueologia, a história e a profecia cumprida confirmam a veracidade da Bíblia, além da transformação de vidas.",
    verse: "A tua palavra é a verdade.",
    reference: "João 17:17"
  },
  // 10. Dúvidas Comuns
  {
    id: 91,
    category: "Dúvidas",
    question: "Tatuagem é pecado?",
    answer: "O Novo Testamento não proíbe explicitamente, mas o cristão deve considerar a motivação, a consciência e o testemunho.",
    verse: "Portanto, quer comais quer bebais, ou façais outra qualquer coisa, fazei tudo para glória de Deus.",
    reference: "1 Coríntios 10:31"
  },
  {
    id: 92,
    category: "Dúvidas",
    question: "Pode comer carne de porco?",
    answer: "Sim. No Novo Testamento, Jesus declarou puros todos os alimentos.",
    verse: "(Disse isso, tornando puros todos os alimentos.)",
    reference: "Marcos 7:19"
  },
  {
    id: 93,
    category: "Dúvidas",
    question: "O que a Bíblia diz sobre horóscopo?",
    answer: "A Bíblia condena a astrologia e a adivinhação. Devemos buscar direção em Deus, não nas estrelas.",
    verse: "Não se achará entre ti... quem consulte os astros, nem agoureiro...",
    reference: "Deuteronômio 18:10"
  },
  {
    id: 94,
    category: "Dúvidas",
    question: "O suicídio tem perdão?",
    answer: "O suicídio é um pecado grave (homicídio), mas a Bíblia não diz que é imperdoável. A salvação depende da fé em Cristo, embora seja um ato de desespero trágico.",
    verse: "Porque estou certo de que, nem a morte... nos poderá separar do amor de Deus...",
    reference: "Romanos 8:38-39"
  },
  {
    id: 95,
    category: "Dúvidas",
    question: "Dinheiro é raiz de todo mal?",
    answer: "Não o dinheiro em si, mas o AMOR ao dinheiro.",
    verse: "Pois o amor ao dinheiro é a raiz de todos os males.",
    reference: "1 Timóteo 6:10"
  },
  {
    id: 96,
    category: "Dúvidas",
    question: "O que é blasfêmia contra o Espírito?",
    answer: "É a rejeição contínua e deliberada da obra do Espírito Santo, atribuindo-a a Satanás, e recusando o arrependimento final.",
    verse: "Mas quem blasfemar contra o Espírito Santo nunca terá perdão: é culpado de pecado eterno.",
    reference: "Marcos 3:29"
  },
  {
    id: 97,
    category: "Dúvidas",
    question: "Crente pode ter depressão?",
    answer: "Sim. Grandes homens de Deus como Elias e Davi enfrentaram angústia profunda. Depressão é uma doença e precisa de tratamento médico e espiritual.",
    verse: "Por que você está assim tão triste, ó minha alma? Por que está assim tão perturbada dentro de mim? Ponha a sua esperança em Deus...",
    reference: "Salmos 42:5"
  },
  {
    id: 98,
    category: "Dúvidas",
    question: "O que é jugo desigual?",
    answer: "É uma união (parceria ou casamento) entre um crente e um descrente, o que gera conflito de valores fundamentais.",
    verse: "Não se ponham em jugo desigual com descrentes. Pois que sociedade há entre a justiça e a iniquidade?",
    reference: "2 Coríntios 6:14"
  },
  {
    id: 99,
    category: "Dúvidas",
    question: "Deus ouve a oração do pecador?",
    answer: "Deus ouve a oração de arrependimento. Porém, o pecado não confessado pode ser uma barreira.",
    verse: "Se eu acalentasse o pecado no coração, o Senhor não me ouviria.",
    reference: "Salmos 66:18"
  },
  {
    id: 100,
    category: "Dúvidas",
    question: "Qual o propósito da vida?",
    answer: "Glorificar a Deus e gozá-Lo para sempre.",
    verse: "Porque dele, e por ele, e para ele são todas as coisas; glória, pois, a ele eternamente. Amém.",
    reference: "Romanos 11:36"
  }
];
