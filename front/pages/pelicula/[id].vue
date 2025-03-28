<template>
  <div v-if="pending" class="loading">Cargando detalles de la película...</div>
  <div v-if="error" class="error">
    <p>Error: {{ error.message }}</p>
  </div>

  <div v-if="movie" class="movie-details">
    <button class="back-button" @click="$router.push('/')">
      ⬅️
    </button>
    
    <div class="movie-container">
      <!-- Sección superior: Imagen + datos básicos -->
      <div class="movie-header">
        <div class="movie-poster">
          <img :src="movie.img" alt="Poster de la película" v-if="movie.img" />
        </div>
        
        <div class="movie-basic-info">
          <h1 class="movie-title">{{ movie.title }}</h1>
          
          <div class="movie-meta">
            <div class="meta-item">
              <div class="meta-label">Género</div>
              <div class="meta-value">{{ movie.genre }}</div>
            </div>
            
            <div class="meta-item">
              <div class="meta-label">Duración</div>
              <div class="meta-value">{{ movie.duration }} minutos</div>
            </div>
            
            <div class="meta-item">
              <div class="meta-label">Estreno</div>
              <div class="meta-value">{{ movie.release_date }}</div>
            </div>
            
            <div class="meta-item" v-if="movie.trailer">
              <div class="meta-label">Trailer</div>
              <div class="meta-value trailer">
                <a :href="movie.trailer" target="_blank" class="trailer-link">
                  Ver en YouTube
                </a>
              </div>
            </div>
          </div>
          
          <div class="movie-actions">
            <button class="buy-ticket" @click="goToReserva">Reservar butacas</button>
          </div>
        </div>
      </div>
      
      <!-- Sección de sinopsis -->
      <div class="movie-synopsis">
        <h2 class="synopsis-title">Sinopsis</h2>
        <p class="synopsis-text">{{ movie.synopsis }}</p>
      </div>
      
      <!-- Sección de créditos -->
      <div class="movie-credits">
        <div class="credit-item">
          <div class="credit-label">Director</div>
          <div class="credit-value">{{ movie.director }}</div>
        </div>
        
        <div class="credit-item">
          <div class="credit-label">Actores</div>
          <div class="credit-value">{{ movie.actors }}</div>
        </div>
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
  position: relative;
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
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.loading {
  color: #64ffda;
  border-left: 3px solid #64ffda;
}

.error {
  color: #ff6b6b;
  border-left: 3px solid #ff6b6b;
}

/* ✅ Contenedor principal de información */
.movie-container {
  display: flex;
  flex-direction: column;
  max-width: 1200px;
  width: 100%;
  margin-top: 2rem;
  gap: 2rem;
}

/* ✅ Sección superior (imagen + datos básicos) */
.movie-header {
  display: flex;
  gap: 2.5rem;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* ✅ Contenedor de imagen */
.movie-poster {
  flex: 0 0 350px;
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  align-self: flex-start;
}

.movie-poster img {
  width: 100%;
  height: auto;
  max-height: 500px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(100, 255, 218, 0.2);
  transition: all 0.5s ease;
}

.movie-poster:hover img {
  transform: scale(1.03);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
}

/* ✅ Información básica */
.movie-basic-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.movie-title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: #ffffff;
  font-weight: 600;
  position: relative;
  padding-bottom: 0.5rem;
}

.movie-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 80px;
  height: 3px;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  border-radius: 3px;
}

.movie-meta {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.meta-item {
  background: rgba(255, 255, 255, 0.05);
  padding: 0.8rem 1rem;
  border-radius: 8px;
  border-left: 3px solid rgba(100, 255, 218, 0.3);
}

.meta-label {
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 0.3rem;
}

.meta-value {
  font-size: 1.1rem;
  color: #ffffff;
  font-weight: 500;
}

.meta-value.trailer {
  display: flex;
  align-items: center;
}

/* ✅ Sección de sinopsis */
.movie-synopsis {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.synopsis-title {
  font-size: 1.5rem;
  color: #64ffda;
  margin-bottom: 1.5rem;
  font-weight: 600;
  position: relative;
  display: inline-block;
}

.synopsis-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 50px;
  height: 2px;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
}

.synopsis-text {
  font-size: 1.1rem;
  line-height: 1.8;
  color: rgba(255, 255, 255, 0.9);
  text-align: justify;
}

/* ✅ Sección de créditos */
.movie-credits {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
}

.credit-item {
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 8px;
  border-left: 3px solid rgba(100, 255, 218, 0.3);
}

.credit-label {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 0.5rem;
}

.credit-value {
  font-size: 1.1rem;
  color: #ffffff;
  font-weight: 500;
}

/* ✅ Botón de comprar */
.movie-actions {
  display: flex;
  justify-content: center;
  margin-top: 1rem;
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
  padding: 1rem 2.5rem;
  background: linear-gradient(135deg, #64ffda 0%, #1e90ff 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.4s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.buy-ticket::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #1e90ff 0%, #64ffda 100%);
  opacity: 0;
  transition: opacity 0.4s ease;
  z-index: -1;
}

.buy-ticket:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.buy-ticket:hover::before {
  opacity: 1;
}

.buy-ticket:active {
  transform: translateY(0);
}

/* ✅ Enlace del trailer */
.trailer-link {
  color: #1e90ff;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  margin-left: 0.5rem;
}

.trailer-link::after {
  content: '↗';
  margin-left: 0.3rem;
  font-size: 0.9em;
}

.trailer-link:hover {
  color: #64ffda;
  transform: translateX(3px);
}

.trailer-link::before {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: #64ffda;
  transition: width 0.3s ease;
}

.trailer-link:hover::before {
  width: 100%;
}

/* ✅ Responsive */
@media (max-width: 1024px) {
  .movie-header {
    flex-direction: column;
    padding: 1.5rem;
  }
  
  .movie-poster {
    flex: 1;
    max-width: 100%;
    align-self: center;
  }
  
  .movie-title {
    font-size: 2.2rem;
    margin-top: 1rem;
  }
}

@media (max-width: 768px) {
  .movie-details {
    padding: 1.5rem;
  }
  
  .movie-title {
    font-size: 1.8rem;
  }
  
  .movie-meta {
    grid-template-columns: 1fr 1fr;
  }
  
  .back-button {
    top: 1rem;
    left: 1rem;
    width: 45px;
    height: 45px;
    font-size: 1.3rem;
  }
}

@media (max-width: 480px) {
  .movie-details {
    padding: 1rem;
  }
  
  .movie-header, 
  .movie-synopsis,
  .movie-credits {
    padding: 1.2rem;
  }
  
  .movie-title {
    font-size: 1.6rem;
  }
  
  .movie-meta {
    grid-template-columns: 1fr;
  }
  
  .synopsis-text {
    font-size: 1rem;
  }
  
  .buy-ticket {
    padding: 0.9rem 2rem;
    font-size: 1rem;
    width: 100%;
  }
  
  .back-button {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }
}
</style>