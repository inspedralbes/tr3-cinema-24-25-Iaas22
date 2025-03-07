<template>
  <div v-if="pending">Cargando detalles de la película...</div>
  <div v-if="error" class="error">
    <p>Error: {{ error.message }}</p>
  </div>

  <div v-if="movie" class="movie-details">
    <button class="back-button" @click="$router.push('/')">Volver</button>
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
        <!-- Botón de comprar entrada -->
        <button class="buy-ticket">Comprar Entrada</button>
      </div>
    </div>
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
/* Estilos para la navbar */
.navbar {
  background-color: #333;
  color: white;
  padding: 1rem;
  text-align: center;
  font-size: 1.5rem;
}

/* Contenedor del grid de películas */
.movies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px;
  justify-content: center;
  max-width: 1400px;
  margin: auto;
}

@media (min-width: 1400px) {
  .movies-grid {
    grid-template-columns: repeat(7, 1fr);
  }
}

/* Estilos de la card de película */
.movie-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 15px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.movie-card:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.movie-card h3 {
  font-size: 1.2rem;
  margin-bottom: 10px;
  color: #333;
}

.movie-card img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  object-fit: cover;
}

/* Estilos para errores y mensajes de carga */
.error {
  color: red;
  text-align: center;
  font-size: 1.2rem;
}

.pending {
  text-align: center;
  font-size: 1.2rem;
}
/* Estilos para los detalles de la película */
.movie-details {
  max-width: 1000px; /* Hace que el contenedor sea más largo */
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
}

.movie-info {
  display: flex;
  flex-direction: row; 
  gap: 20px; 
  margin-top: 100px; 
}

.movie-image {
  flex: 1; /* La imagen ocupa el 50% del espacio */
  max-width: 300px; /* Controla el tamaño máximo de la imagen */
}

.movie-description {
  flex: 2; /* La descripción ocupa el doble de espacio que la imagen */
  text-align: left; /* Para que el texto se alinee a la izquierda */
}

.movie-description h2 {
  margin-top: 0;
}

/* Botón de Volver */
.back-button {
  position: absolute;
  top: 20px;
  left: 20px;
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

.back-button:hover {
  background-color: #0056b3;
}

/* Botón de comprar entrada */
.buy-ticket {
  margin-top: 20px; /* Puedes ajustar si deseas un poco más de espacio */
  background-color: #28a745; /* Color verde */
  color: white;
  padding: 12px 20px; /* Espaciado dentro del botón */
  border: none;
  border-radius: 5px; /* Bordes redondeados */
  cursor: pointer;
  font-size: 1.1rem;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.buy-ticket:hover {
  background-color: #218838; /* Un verde más oscuro cuando pasa el mouse */
  transform: scale(1.05); /* Efecto de agrandar al pasar el mouse */
}

.buy-ticket:active {
  transform: scale(0.98); /* Efecto de presionar el botón */
}

  </style>
  