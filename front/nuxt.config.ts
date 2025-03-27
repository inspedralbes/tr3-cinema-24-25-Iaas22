export default defineNuxtConfig({
  compatibilityDate: '2025-03-04',
  devtools: { enabled: true },
  
  // Habilitar las rutas automáticas desde la carpeta `pages`
  pages: true, css: 
  ['normalize.css'],
  runtimeConfig: {
    public: {
      apiBase: 'http://127.0.0.1:8000/api' // URL de la API de Laravel
    }
  }
})
