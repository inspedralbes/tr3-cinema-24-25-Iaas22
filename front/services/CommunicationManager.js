import axios from 'axios';

export default {
  async fetchMovies() {
    try {
      const apiBase = useRuntimeConfig().public.apiBase;
      const response = await axios.get(`${apiBase}/movies`);
      return response.data;
    } catch (error) {
      throw new Error('No se pudieron cargar las películas');
    }
  },

  async fetchMovieDetails(movie_id) {
    try {
      const apiBase = useRuntimeConfig().public.apiBase;
      const response = await axios.get(`${apiBase}/movies/${movie_id}`);
      return response.data;
    } catch (error) {
      console.error("Error cargando detalles de la película:", error);
      throw new Error('No se pudieron cargar los detalles de la película');
    }
  },

  async fetchSessions() {
    try {
      const response = await axios.get(`${API_BASE_URL}/sessions`)
      console.log('📥 Sesiones recibidas:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error al obtener las sesiones:', error)
      throw error
    }
  }
};
