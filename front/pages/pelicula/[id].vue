<template>
    <div v-if="pending">Cargando detalles de la película...</div>
    <div v-if="error" class="error">
      <p>Error: {{ error.message }}</p>
    </div>
  
    <div v-if="movie" class="movie-details">
      <h2>{{ movie.title }}</h2>
      <p><strong>Género:</strong> {{ movie.genre }}</p>
      <p><strong>Duración:</strong> {{ movie.duration }} minutos</p>
      <p><strong>Sinopsis:</strong> {{ movie.synopsis }}</p>
      <p><strong>Director:</strong> {{ movie.director }}</p>
      <p><strong>Actores:</strong> {{ movie.actors }}</p>
      <p><strong>Estreno:</strong> {{ movie.release_date }}</p>
      <img :src="movie.img" alt="Imagen de la película" v-if="movie.img" />
      <button @click="$router.push('/')">Volver</button>
    </div>
  </template>
  
  <script setup>
  import { useRoute } from 'vue-router';
  import CommunicationManager from '@/services/CommunicationManager';
  
  const route = useRoute();
  const movieId = route.params.id; // Verifica que 'id' es correcto
  
  if (!movieId) {
    console.error("Error: No se encontró un ID en la ruta");
  }
  
  const { data: movie, pending, error } = await useAsyncData(`movie-${movieId}`, () => 
    CommunicationManager.fetchMovieDetails(movieId)
  );
  </script>
  
  <style scoped>
  /* Estilos aquí */
  </style>
  