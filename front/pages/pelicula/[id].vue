<template>
  <div v-if="pending" class="loading">Cargando detalles de la película...</div>
  <div v-if="error" class="error">
    <p>Error: {{ error.message }}</p>
  </div>

  <div v-if="movie" class="movie-details">
    <button class="back-button" @click="$router.push('/')">
      ⬅️
    </button>
    <div class="movie-info">
      <div class="movie-image">
        <img :src="movie.img" alt="Imagen de la película" v-if="movie.img" />
      </div>
      <div class="movie-description">
        <h2>{{ movie.title }}</h2>
        <p><strong>Género:</strong> {{ movie.genre }}</p>
        <p><strong>Duración:</strong> {{ movie.duration }} minutos</p>
        <p><strong>Sinopsis:</strong> {{ movie.synopsis }}</p>
        <p><strong>Director:</strong> {{ movie.director }}</p>
        <p><strong>Actores:</strong> {{ movie.actors }}</p>
        <p><strong>Estreno:</strong> {{ movie.release_date }}</p>
        <button class="buy-ticket" @click="goToReserva">Comprar Entrada</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router';
import CommunicationManager from '@/services/CommunicationManager';

const route = useRoute();
const router = useRouter();
const movieId = route.params.id;

if (!movieId) {
  console.error("Error: No se encontró un ID en la ruta");
}

const { data: movie, pending, error } = await useAsyncData(`movie-${movieId}`, () =>
  CommunicationManager.fetchMovieDetails(movieId)
);

function goToReserva() {
  router.push({ path: `/Sessions/${movieId}` });  
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}

/* Contenedor de los detalles de la película */
.movie-details {
  max-width: 2000px;
  height: 790px;
  padding: 20px;
  background-color: #0a0f2c;
  color: #ffffff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
}

.movie-info {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  margin-top: 100px;
}

.movie-image {
  flex: 1;
  max-width: 400px;
}

.movie-image img {
  width: 100%;
  height: 600px; /* Tamaño constante para todas las imágenes */
  object-fit: cover;
  border-radius: 8px;
}

/* Descripción de la película */
.movie-description {
  flex: 2;
  text-align: left;
}

.movie-description h2 {
  margin-top: 0;
}

/* Botón de volver con flecha */
.back-button {
  position: absolute;
  top: 20px;
  left: 20px;
  background-color: transparent;
  color: #ffffff;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

.back-button:hover {
  color: #007bff;
}

/* Botón de comprar entrada */
.buy-ticket {
  margin-top: 20px;
  background-color: #ffffff;
  color: rgb(0, 0, 0);
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.buy-ticket:hover {
  background-color: #05086e;
  transform: scale(1.05);
}

.buy-ticket:active {
  transform: scale(0.98);
}

/* Mensaje de carga y error */
.loading, .error {
  text-align: center;
  font-size: 1.2rem;
}

.error {
  color: red;
}
</style>
