<script setup>
const route = useRoute();
const config = useRuntimeConfig();
const { data: movie, pending, error } = useFetch(`${config.public.apiBase}/movies/${route.query.id}`);
</script>

<template>
  <div class="container">
    <div v-if="pending" class="text-center">Cargando detalles...</div>
    <div v-else-if="error" class="text-red-500">Error al cargar la película.</div>
    
    <div v-else class="movie-details">
      <h1>{{ movie.title }}</h1>
      <p><strong>Género:</strong> {{ movie.genre }}</p>
      <p><strong>Duración:</strong> {{ movie.duration }} min</p>
      <p><strong>Director:</strong> {{ movie.director }}</p>
      <p><strong>Actores:</strong> {{ movie.actors }}</p>
      <p><strong>Sinopsis:</strong> {{ movie.sinopsis }}</p>
      <p><strong>Estreno:</strong> {{ movie.premiere }}</p>

      <!-- Botón para redirigir a la vista de reserva -->
      <nuxt-link :to="{ name: 'reservations', query: { movie_id: movie.id } }">
        <button class="btn btn-primary">Reservar asientos</button>
      </nuxt-link>
    </div>
  </div>
</template>

<style scoped>
.container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  text-align: center;
}
.movie-details {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
h1 {
  color: #007BFF;
}
p {
  margin: 5px 0;
  color: #555;
}
</style>
