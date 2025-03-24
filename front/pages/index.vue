<template>
  <div>
    <nav class="navbar">
      <h1>C𝕚𝕟𝕖Y𝕒</h1>
      <div class="actions">
        <div class="search-container">
          <input 
            v-if="searchVisible" 
            v-model="searchQuery" 
            type="text" 
            placeholder="Buscar..." 
            class="search-bar" 
          />
          <button @click="toggleSearch" class="search-button">🔍</button>
        </div>

        <button 
          v-if="isAdmin" 
          @click="goToAdminPanel" 
          class="admin-button"
        >
          Panel de Admin
        </button>

        <button @click="goToLogin" class="login-button">Iniciar sesión</button>
        <button @click="handleLogout" class="logout-button">Cerrar sesión</button>
      </div>
    </nav>

    <!-- ✅ Carrusel de Imágenes -->
    <div class="carousel-container">
      <div 
        class="carousel-track"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
        <div 
          class="carousel-slide" 
          v-for="(image, index) in images" 
          :key="index"
        >
          <img :src="image" alt="Imagen de la película" class="carousel-image" />
        </div>
      </div>
      <button @click="prevSlide" class="carousel-button left">❮</button>
      <button @click="nextSlide" class="carousel-button right">❯</button>
    </div>

    <!-- ✅ Carrusel de Películas Populares -->
    <div v-if="popularMovies.length" class="popular-section">
      <div class="title-section">
        <h2>MÁS POPULARES</h2>
      </div>
      <div class="popular-carousel-container">
        <div 
          class="popular-carousel-track"
          :style="{ transform: `translateX(-${popularIndex * 100}%)` }"
        >
          <div 
            class="popular-movie-card" 
            v-for="(movie, index) in popularMovies" 
            :key="index"
            @click="navigateTo(`/pelicula/${movie.movie_id}`)"
          >
            <h3>{{ movie.title }}</h3>
            <img :src="movie.img" alt="Imagen de la película" />
          </div>
        </div>
        <button @click="prevPopularSlide" class="carousel-button left">❮</button>
        <button @click="nextPopularSlide" class="carousel-button right">❯</button>
      </div>
    </div>

    <!-- ✅ Sección de Todas las Películas -->
    <div v-if="remainingMovies.length">
      <div class="title-section">
        <h2>TODAS LAS PELÍCULAS</h2>
      </div>
      <div class="movies-grid">
        <div 
          v-for="movie in remainingMovies" 
          :key="movie.id" 
          class="movie-card"
          @click="navigateTo(`/pelicula/${movie.movie_id}`)"
        >
          <h3>{{ movie.title }}</h3>
          <img :src="movie.img" alt="Imagen de la película" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import CommunicationManager from '@/services/CommunicationManager'
import axios from 'axios' 

const router = useRouter()
const searchQuery = ref('')
const searchVisible = ref(false)
const isAdmin = ref(false)
const currentIndex = ref(0)
const popularIndex = ref(0) // ✅ Índice del carrusel de populares
let interval = null

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user?.role === 'admin') {
    isAdmin.value = true
  }

  interval = setInterval(() => {
    nextSlide()
  }, 3000)
})

onUnmounted(() => {
  clearInterval(interval)
})

const toggleSearch = () => {
  searchVisible.value = !searchVisible.value
  if (!searchVisible.value) searchQuery.value = ''
}

const goToLogin = () => {
  router.push('/login')
}

const goToAdminPanel = () => {
  router.push('/movieCRUD')
}

const handleLogout = async () => {
  try {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    delete axios.defaults.headers.common['Authorization']

    alert('✅ Has cerrado sesión correctamente.')
    router.push('/')
  } catch (error) {
    console.error('❌ Error al cerrar sesión:', error)
  }
}

const { data: movies, pending, error } = await useAsyncData('movies', () =>
  CommunicationManager.fetchMovies()
)

const filteredMovies = computed(() => {
  return movies.value
    ? movies.value.filter(movie =>
        movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
      )
    : []
})

const popularMovies = computed(() => filteredMovies.value.slice(0, 6))
const remainingMovies = computed(() => filteredMovies.value.slice(6))

// ✅ Carrusel de Populares
const nextPopularSlide = () => {
  const totalSlides = Math.ceil(popularMovies.value.length / 3)
  popularIndex.value = (popularIndex.value + 1) % totalSlides
}

const prevPopularSlide = () => {
  const totalSlides = Math.ceil(popularMovies.value.length / 3)
  popularIndex.value = (popularIndex.value - 1 + totalSlides) % totalSlides
}

// ✅ Carrusel de Imágenes
const images = ref([
  '/images/carrusel/peli.png',
  '/images/carrusel/peli1.png',
  '/images/carrusel/peli2.png',
  '/images/carrusel/peli4.png',
  '/images/carrusel/peli5.png',
  '/images/carrusel/peli6.png',
  '/images/carrusel/peli7.png',
  '/images/carrusel/peli8.png',
  '/images/carrusel/peli9.png',
  '/images/carrusel/peli10.png',
])

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % images.value.length
}

const prevSlide = () => {
  currentIndex.value =
    (currentIndex.value - 1 + images.value.length) % images.value.length
}
</script>

<style scoped>
/* ✅ Carrusel de Populares */
.popular-carousel-container {
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
}

.popular-carousel-track {
  display: flex;
  transition: transform 0.5s ease;
}

.popular-movie-card {
  flex: 0 0 calc(100% / 3);
  padding: 10px;
  text-align: center;
  cursor: pointer;
}

.popular-movie-card img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
}

.carousel-button {
  position: absolute;
  top: 50%;
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 12px;
  cursor: pointer;
  font-size: 24px;
  border-radius: 50%;
}

.carousel-button.left {
  left: 10px;
}

.carousel-button.right {
  right: 10px;
}

@media (max-width: 768px) {
  .popular-movie-card {
    flex: 0 0 calc(100% / 1);
  }
}
</style>


<style scoped>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}
div {
  background-color: #0a0f2c; /* Azul oscuro */
  color: white; /* Para que el texto sea legible sobre el fondo oscuro */
}

.navbar {
  background-color: #0a0f2c; /* Azul oscuro */
  color: white;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.5rem;
}
.navbar h1 {
  margin-left: 50px; /* Ajusta el valor según lo necesites */
}

.actions {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-left: -50px; /* Mueve los botones más a la izquierda */
}

.search-bar {
  padding: 5px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.search-button,
.login-button,
.logout-button,
.admin-button {
  color: rgb(0, 0, 0);
  border: none;
  padding: 12px 20px; /* Aumenta el tamaño de los botones */
  font-size: 1.2rem; /* Aumenta el tamaño de la fuente */
  cursor: pointer;
  border-radius: 5px; /* Bordes más suaves */
  transition: background 0.3s;
}

.search-button:hover,
.login-button:hover,
.logout-button:hover,
.admin-button:hover {
  background: #010a43;
}

/* ✅ Sección de Todas las Películas */
.movies-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
  max-width: 1400px;
  margin: 20px auto;
}

.movie-card {
  flex: 0 0 calc(100% / 6); /* ✅ Mostrar 6 películas por fila */
  padding: 10px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.movie-card img {
  width: 100%;
  height: 250px; /* ✅ Ajustar altura */
  object-fit: cover;
  border-radius: 8px;
}

.movie-card h3 {
  color: #fff;
  font-size: 1rem;
  margin-top: 8px;
}

/* ✅ Efecto hover */
.movie-card:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}



.error {
  color: red;
  text-align: center;
  font-size: 1.2rem;
}

.pending {
  text-align: center;
  font-size: 1.2rem;
}

/* ✅ Carrusel más grande */
.carousel-container {
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 1200px; /* Aumentar tamaño del carrusel */
  height: 500px; /* Altura mayor */
  margin: 20px auto;
  border-radius: 16px;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s ease-in-out;
}

.carousel-slide {
  min-width: 100%;
}

.carousel-image {
  width: 100%;
  height: 500px; /* Altura fija */
  object-fit: cover; /* Mantener proporción sin deformar */
  border-radius: 16px;
}

/* ✅ Botones de navegación */
.carousel-button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 14px;
  cursor: pointer;
  font-size: 28px;
  border-radius: 50%;
  transition: background 0.3s;
  z-index: 10;
}

.carousel-button:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

.carousel-button.left {
  left: 10px;
}

.carousel-button.right {
  right: 10px;
}

/* ✅ Carrusel de Populares */
.popular-carousel-container {
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
}

.popular-carousel-track {
  display: flex;
  transition: transform 0.5s ease;
}

.popular-movie-card {
  flex: 0 0 calc(100% / 3); /* ✅ Mostrar 3 películas a la vez */
  padding: 10px;
  text-align: center;
  cursor: pointer;
}

.popular-movie-card img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
}

.popular-movie-card h3 {
  color: #fff;
  font-size: 1.2rem;
  margin-top: 8px;
}

/* ✅ Carrusel Botones */
.carousel-button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 14px;
  cursor: pointer;
  font-size: 28px;
  border-radius: 50%;
  z-index: 10;
}

.carousel-button.left {
  left: 10px;
}

.carousel-button.right {
  right: 10px;
}

/* ✅ Diseño responsive */
@media (max-width: 768px) {
  .popular-movie-card {
    flex: 0 0 calc(100% / 1); /* ✅ Mostrar 1 película en dispositivos móviles */
  }
}
/* ✅ Diseño responsive */
@media (max-width: 768px) {
  .carousel-container {
    height: 300px;
  }

  .carousel-image {
    height: 300px;
  }
}
.title-section {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  margin-top: 20px;
  color: #ffffff;
}


</style>
