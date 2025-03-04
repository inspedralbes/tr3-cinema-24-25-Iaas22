<script setup>
const config = useRuntimeConfig();
const { data: movies, pending, error } = useFetch(`${config.public.apiBase}/movies`);
</script>

<template>
  <div class="container">
    <button @click="navigateTo('/movies')" class="crud-button">Ir al CRUD</button>
    
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
  max-width: 100%;
  margin: 20px auto;
  text-align: center;
  padding: 20px;
}

.crud-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 20px;
  transition: background 0.3s ease;
}

.crud-button:hover {
  background-color: #0056b3;
}

.movie-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 10px;
  padding: 10px;
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
  font-size: 1.5rem;
}

@media (min-width: 600px) {
  .movie-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 900px) {
  .movie-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1200px) {
  .movie-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}
</style>
