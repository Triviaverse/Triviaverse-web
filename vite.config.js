import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import { VitePluginNode } from 'vite-plugin-node'

export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    VitePluginNode({
      adapter: 'express',
      appPath: './server/index.js',
      exportName: 'viteNodeApp',
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    cors: true,
  }
})
