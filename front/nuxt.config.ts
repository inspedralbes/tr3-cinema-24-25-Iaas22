export default defineNuxtConfig({
  compatibilityDate: '2025-03-04',
  devtools: { enabled: true },
  
  pages: true, css: 
  ['normalize.css'],
  runtimeConfig: {
    public: {
      apiBase: 'http://127.0.0.1:8000/api' // URL de la API de Laravel
    }
  }
})
