// services/CommunicationManager.js
import axios from 'axios';

export default {
  async fetchMovies() {
    try {
      const config = useRuntimeConfig();
      const response = await axios.get(`${config.public.apiBase}/movies`);
      return response.data;
    } catch (error) {
      throw new Error('No se pudieron cargar las películas');
    }
  },

  async fetchMovieDetails(movie_id) {
    try {
      const config = useRuntimeConfig();
      const response = await axios.get(`${config.public.apiBase}/movies/${movie_id}`);
      return response.data;
    } catch (error) {
      console.error(error); // Agregamos un console.log para ver más detalles del error
      throw new Error('No se pudieron cargar los detalles de la película');
    }
  }
};
