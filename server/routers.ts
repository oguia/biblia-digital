import { COOKIE_NAME } from "@shared/const";
import { getSessionCookieOptions } from "./_core/cookies";
import { systemRouter } from "./_core/systemRouter";
import { publicProcedure, router } from "./_core/trpc";
import { z } from "zod";
import * as fs from "fs/promises";
import * as path from "path";

export const appRouter = router({
  system: systemRouter,

  auth: router({
    me: publicProcedure.query(opts => opts.ctx.user),
    logout: publicProcedure.mutation(({ ctx }) => {
      const cookieOptions = getSessionCookieOptions(ctx.req);
      ctx.res.clearCookie(COOKIE_NAME, { ...cookieOptions, maxAge: -1 });
      return {
        success: true,
      } as const;
    }),
  }),

  bible: router({
    getVerses: publicProcedure
      .input(z.object({
        book: z.string(),
        chapter: z.number(),
        version: z.string().default("nvi"),
      }))
      .query(async ({ input }) => {
        try {
          const dataPath = path.join(process.cwd(), "server", "data", "bible-data.json");
          const data = await fs.readFile(dataPath, "utf-8");
          const bibleData = JSON.parse(data);
          
          const book = bibleData[input.book];
          if (!book) {
            return { verses: [], error: `Livro '${input.book}' não encontrado` };
          }
          
          const chapter = book.chapters[input.chapter.toString()];
          if (!chapter) {
            return { verses: [], error: `Capítulo ${input.chapter} não encontrado` };
          }
          
          const verses = chapter.verses.map((v: any) => ({
            number: v.number,
            text: v.text[input.version] || v.text.nvi,
          }));
          
          return { verses, error: null };
        } catch (error) {
          console.error("Erro ao buscar versículos:", error);
          return { verses: [], error: "Erro ao buscar versículos" };
        }
      }),
    
    getBooks: publicProcedure
      .query(async () => {
        try {
          const dataPath = path.join(process.cwd(), "server", "data", "bible-data.json");
          const data = await fs.readFile(dataPath, "utf-8");
          const bibleData = JSON.parse(data);
          
          const books = Object.keys(bibleData).map(name => ({
            name,
            chapters: Object.keys(bibleData[name].chapters).length,
          }));
          
          return books;
        } catch (error) {
          console.error("Erro ao buscar livros:", error);
          return [];
        }
      }),
  }),
});

export type AppRouter = typeof appRouter;

