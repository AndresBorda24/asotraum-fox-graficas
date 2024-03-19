import { fileURLToPath, URL } from 'node:url'

import laravel from 'laravel-vite-plugin'
import { defineConfig, loadEnv } from 'vite'

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd())

  return {
    base: Boolean(env.VITE_APP_BASE)
      ? `${env.VITE_APP_BASE}/build`
      : undefined,
    plugins: [
      laravel({
        input: [
            "assets/ventas/index.js",
            "assets/ventas/grilla.js"
        ],
      }),
    ],
    esbuild: {
      supported: {
        'top-level-await': true
      }
    },
    server: {
      origin: 'http://localhost:5173',
    },
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      }
    }
  }
})
