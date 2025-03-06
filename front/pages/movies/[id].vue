<template>
  <div v-if="loading">
    <p>Cargando detalles...</p>
  </div>

  <div v-if="error" class="error">
    <p>Error al cargar los detalles de la película: {{ error.message }}</p>
  </div>

  <div v-if="movie">
    <h1>{{ movie.title }}</h1>
    <p><strong>Género:</strong> {{ movie.genre }}</p>
    <p><strong>Duración:</strong> {{ movie.duration }} minutos</p>
    <p><strong>Director:</strong> {{ movie.director }}</p>
    <p><strong>Actores:</strong> {{ movie.actors }}</p>
    <p><strong>Sinopsis:</strong> {{ movie.synopsis }}</p>
    <p><strong>Estreno:</strong> {{ movie.release_date }}</p>
    <img :src="movie.img" alt="Imagen de la película" v-if="movie.img" />
  </div>
</template>

<script>
import CommunicationManager from '@/services/CommunicationManager';

export default {
  data() {
    return {
      movie: null,
      loading: true,
      error: null,
    };
  },
  async created() {
    const movie_id = this.$route.params.id;  // Extraemos el id desde la URL
    try {
      // Hacemos la solicitud para obtener los detalles de la película
      const movie = await CommunicationManager.fetchMovieDetails(movie_id);
      this.movie = movie;
    } catch (error) {
      console.error('Error al cargar los detalles de la película:', error); // Ver detalles en consola
      this.error = error;  // Muestra el error en el frontend
    } finally {
      this.loading = false;
    }
  }
};
</script>

<style scoped>
.error {
  color: red;
  text-align: center;
}
</style>
