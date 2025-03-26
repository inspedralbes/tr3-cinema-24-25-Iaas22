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
        <p v-if="movie.trailer">
          <strong>Trailer: </strong> 
          <a :href="movie.trailer" target="_blank" class="trailer-link">
            Ver Trailer en YouTube
          </a>
        </p>
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
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ✅ Contenedor principal */
.movie-details {
  min-height: 100vh;
  padding: 2rem;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  color: #e6f1ff;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* ✅ Mensajes de carga y error */
.loading, .error {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(5px);
  border-radius: 12px;
  margin: 2rem auto;
  max-width: 800px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.error {
  color: #ff6b6b;
  border-left: 3px solid #ff6b6b;
}

/* ✅ Contenedor de información */
.movie-info {
  display: flex;
  gap: 2rem;
  max-width: 1200px;
  width: 100%;
  margin-top: 2rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* ✅ Imagen de la película */
.movie-image {
  flex: 1;
  max-width: 400px;
}

.movie-image img {
  width: 100%;
  height: 600px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(100, 255, 218, 0.2);
  transition: all 0.3s ease;
}

.movie-image img:hover {
  transform: scale(1.02);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
}

/* ✅ Descripción de la película */
.movie-description {
  flex: 2;
  text-align: left;
}

.movie-description h2 {
  font-size: 2.2rem;
  margin-bottom: 1.5rem;
  color: #64ffda;
  font-weight: 600;
}

.movie-description p {
  margin-bottom: 1rem;
  font-size: 1.1rem;
  line-height: 1.6;
}

.movie-description strong {
  color: #1e90ff;
  font-weight: 500;
}

/* ✅ Botón de volver */
.back-button {
  position: absolute;
  top: 1.5rem;
  left: 1.5rem;
  background: rgba(10, 25, 47, 0.7);
  color: #64ffda;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  transition: all 0.3s ease;
  border: 1px solid rgba(100, 255, 218, 0.3);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.back-button:hover {
  background: rgba(30, 144, 255, 0.8);
  color: white;
  transform: translateX(-3px);
}

/* ✅ Botón de comprar entrada */
.buy-ticket {
  margin-top: 2rem;
  padding: 1rem 2rem;
  background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.buy-ticket:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

.buy-ticket:active {
  transform: translateY(0);
}

/* ✅ Enlace del trailer */
.trailer-link {
  color: #4facfe;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
  margin-left: 0.5rem;
}

.trailer-link:hover {
  color: #64ffda;
  text-decoration: underline;
  transform: translateX(3px);
}

/* ✅ Responsive */
@media (max-width: 1024px) {
  .movie-info {
    flex-direction: column;
    align-items: center;
  }
  
  .movie-image {
    max-width: 100%;
  }
  
  .movie-image img {
    height: 500px;
  }
}

@media (max-width: 768px) {
  .movie-details {
    padding: 1.5rem;
  }
  
  .movie-info {
    padding: 1.5rem;
  }
  
  .movie-description h2 {
    font-size: 1.8rem;
  }
  
  .movie-image img {
    height: 400px;
  }
}

@media (max-width: 480px) {
  .movie-details {
    padding: 1rem;
  }
  
  .movie-info {
    padding: 1rem;
    margin-top: 3rem;
  }
  
  .movie-description h2 {
    font-size: 1.5rem;
  }
  
  .movie-image img {
    height: 300px;
  }
  
  .back-button {
    top: 1rem;
    left: 1rem;
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }
  
  .buy-ticket {
    padding: 0.8rem 1.5rem;
    font-size: 1rem;
  }
}
</style>