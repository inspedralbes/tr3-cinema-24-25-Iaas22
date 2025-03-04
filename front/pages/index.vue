<script setup>
const config = useRuntimeConfig();
const { data: movies, pending, error } = useFetch(`${config.public.apiBase}/movies`);
</script>

<template>
    <div class="container">
      <h1>Cartelera de Películas</h1>
  
      <div v-if="pending" class="text-center">Cargando películas...</div>
      <div v-else-if="error" class="text-red-500">Error al cargar películas.</div>
      
      <div v-else class="movie-grid">
        <div v-for="movie in movies" :key="movie.id" class="movie-card" @click="navigateTo(`/detallesMovies?id=${movie.id}`)">
          <h2>{{ movie.title }}</h2>
        </div>
      </div>
    </div>
  </template>
  

<style scoped>
.container {
  max-width: 1200px;
  margin: 20px auto;
  text-align: center;
  padding: 20px;
}
.movie-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 20px;
  padding: 20px;
}
.movie-card {
  background: #fff;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
}
.movie-card:hover {
  transform: scale(1.05);
}
h1 {
  color: #333;
  margin-bottom: 20px;
}
</style>
