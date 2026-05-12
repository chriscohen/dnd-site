import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from "@tailwindcss/vite";

// https://vite.dev/config/
export default defineConfig({
  base: '/admin/',
  plugins: [
    vue(),
    vueDevTools(),
    tailwindcss(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      'dnd5e-api': fileURLToPath(new URL('../packages/dnd5e-api/src/index.ts', import.meta.url)),
      '@dnd5e/types': fileURLToPath(new URL('../packages/@dnd5e/types/src/index.ts', import.meta.url)),
    },
  },
});
