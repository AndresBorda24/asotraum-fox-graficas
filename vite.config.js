import laravel from 'laravel-vite-plugin'
import { defineConfig, loadEnv } from 'vite'
import { fileURLToPath, URL } from 'node:url'

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd())

  return {
    base: `/graficas/build`,
    plugins: [
      laravel({
        input: [
          "assets/qx/index.js",
          "assets/ventas/index.js",
          "assets/ventas/grilla.js",
          "assets/home/index.js"
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
