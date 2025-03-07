<template>
  <div>
    <nav class="navbar">
      <h1>Lista de Películas</h1>
    </nav>

    <div v-if="pending">Cargando películas...</div>
    <div v-if="error" class="error">
      <p>Error al cargar las películas: {{ error.message }}</p>
    </div>

    <div v-if="movies.length">
      <div class="movies-grid">
        <div 
          v-for="movie in filteredMovies" 
          :key="movie.id" 
          class="movie-card"
          @click="navigateTo(`/pelicula/${movie.movie_id}`)"
        >
          <h3>{{ movie.title }}</h3>
          <img :src="movie.img" alt="Imagen de la película" v-if="movie.img" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import CommunicationManager from '@/services/CommunicationManager';

const searchQuery = ref('');
const searchVisible = ref(false);

const { data: movies, pending, error } = await useAsyncData('movies', () => 
  CommunicationManager.fetchMovies()
);

const filteredMovies = computed(() => {
  return movies.value
    ? movies.value.filter(movie =>
        movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
      )
    : [];
});
</script>

<style scoped>
/* Estilos aquí */
</style>
