import axios from 'axios';

export default {
  async fetchMovies() {
    try {
      // ⚠️ No se puede usar `useRuntimeConfig()` aquí, así que pasamos la base URL como parámetro
      const apiBase = useRuntimeConfig().public.apiBase;
      const response = await axios.get(`${apiBase}/movies`);
      return response.data;
    } catch (error) {
      throw new Error('No se pudieron cargar las películas');
    }
  },

  async fetchMovieDetails(movie_id) {
    try {
      const apiBase = useRuntimeConfig().public.apiBase; // 🔥 Corrige el uso de `useRuntimeConfig`
      const response = await axios.get(`${apiBase}/movies/${movie_id}`);
      return response.data;
    } catch (error) {
      console.error("Error cargando detalles de la película:", error);
      throw new Error('No se pudieron cargar los detalles de la película');
    }
  }
};
