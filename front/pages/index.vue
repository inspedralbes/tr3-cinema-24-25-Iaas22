<template>
  <div>
    <nav class="navbar">
      <h1>Lista de Películas</h1>
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

        <!-- ✅ Mostrar botón solo si el usuario es admin -->
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

    <div v-if="pending">Cargando películas...</div>
    <div v-if="error" class="error">
      <p>Error al cargar las películas: {{ error.message }}</p>
    </div>
    <div class="title-section">
  <h2>TODAS LAS PELICULAS</h2>
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
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import CommunicationManager from '@/services/CommunicationManager'
import axios from 'axios' 

const router = useRouter()
const searchQuery = ref('')
const searchVisible = ref(false)
const isAdmin = ref(false)
const currentIndex = ref(0)
let interval = null

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user?.role === 'admin') {
    isAdmin.value = true
  }
})

 if (process.client) {
    interval = setInterval(() => {
      nextSlide()
    }, 3000)
  }


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

// ✅ Redirigir al archivo "movieCRUD.vue"
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

// ✅ Rutas de las imágenes
const images = ref([
  '/images/carrusel/peli.jpg',
  '/images/carrusel/peli1.jpg',
  '/images/carrusel/peli2.jpg',
  '/images/carrusel/peli4.jpg',
  '/images/carrusel/peli5.jpg',
  '/images/carrusel/peli6.jpg',
  '/images/carrusel/peli7.jpg',
  '/images/carrusel/peli8.jpg',
  '/images/carrusel/peli9.jpg',
  '/images/carrusel/peli10.jpg',
])

// ✅ Función para avanzar en el carrusel
const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % images.value.length
}

// ✅ Función para retroceder en el carrusel
const prevSlide = () => {
  currentIndex.value =
    (currentIndex.value - 1 + images.value.length) % images.value.length
}
</script>

<style scoped>
.navbar {
  background-color: #333;
  color: white;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.5rem;
}

.actions {
  display: flex;
  align-items: center;
  gap: 10px;
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
  background: #ff6600;
  color: white;
  border: none;
  padding: 5px 10px;
  font-size: 1rem;
  cursor: pointer;
  border-radius: 5px;
  transition: background 0.3s;
}

.search-button:hover,
.login-button:hover,
.logout-button:hover,
.admin-button:hover {
  background: #e65c00;
}

.movies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px;
  justify-content: center;
  max-width: 1400px;
  margin: auto;
}

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
  width: 100%; /* Esto mantiene el ancho al 100% del contenedor */
  height: 300px; /* Altura fija para que todas las imágenes tengan el mismo tamaño */
  border-radius: 8px;
  object-fit: cover; /* Cubre el área manteniendo la proporción sin deformar */
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
  color: #333;
}


</style>
