<template>
  <div class="main-container">
    <nav class="navbar">
      <img src="/images/logoo.png" alt="Logo" class="logo" />

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
          <button @click="toggleSearch" class="search-button">
            <img src="/images/lupa.png" alt="Buscar" class="icon-button" />
          </button>
        </div>

        <button 
          v-if="isAdmin" 
          @click="goToAdminPanel" 
          class="admin-button"
        >
          <img src="/images/admin.png" alt="Admin" class="icon-button" />
        </button>

        <button 
          v-if="!isLoggedIn" 
          @click="goToLogin" 
          class="login-button"
        >
          <img src="/images/login.png" alt="Iniciar sesión" class="icon-button" />
        </button>

        <button 
          v-if="isLoggedIn" 
          @click="handleLogout" 
          class="logout-button"
        >
          <img src="/images/out.png" alt="Cerrar sesión" class="icon-button" />
        </button>
      </div>
    </nav>
    <div v-if="showAdminOptions" class="admin-options">
      <button @click="goToMovieCRUD">Ir a Movie CRUD</button>
      <button @click="goToReservationsCRUD">Ir a Reservations CRUD</button>
      <button @click="goToSessionsCRUD">Ir a Sessions CRUD</button>
    </div>
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

    <div v-if="remainingMovies.length" class="all-movies-section">
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

    <!-- Modal de cierre de sesión -->
    <div v-if="showLogoutModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-icon">✅</div>
        <h3>Has cerrado sesión correctamente</h3>
        <button @click="closeModal" class="modal-button">Aceptar</button>
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
const popularIndex = ref(0) 
let interval = null
const isLoggedIn = ref(false) 
const showAdminOptions = ref(false)
const showLogoutModal = ref(false)

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user) {
    isLoggedIn.value = true
    if (user.role === 'admin') {
      isAdmin.value = true
    }
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
  showAdminOptions.value = true 
}

const goToMovieCRUD = () => {
  router.push('/movieCRUD')
}

const goToReservationsCRUD = () => {
  router.push('/reservations-crud')
}

const goToSessionsCRUD = () => {
  router.push('/sessions')
}

const handleLogout = async () => {
  try {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    delete axios.defaults.headers.common['Authorization']
    isLoggedIn.value = false 
    isAdmin.value = false
    showAdminOptions.value = false
    
    // Mostrar modal en lugar de alert
    showLogoutModal.value = true
  } catch (error) {
    console.error('❌ Error al cerrar sesión:', error)
  }
}

const closeModal = () => {
  showLogoutModal.value = false
  router.push('/')
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

const nextPopularSlide = () => {
  const totalSlides = Math.ceil(popularMovies.value.length / 3)
  popularIndex.value = (popularIndex.value + 1) % totalSlides
}

const prevPopularSlide = () => {
  const totalSlides = Math.ceil(popularMovies.value.length / 3)
  popularIndex.value = (popularIndex.value - 1 + totalSlides) % totalSlides
}

const images = ref([
  '/images/carrusel/peli.webp',
  '/images/carrusel/peli1.webp',
  '/images/carrusel/peli2.webp',
  '/images/carrusel/peli3.webp',
  '/images/carrusel/peli4.webp',
  '/images/carrusel/peli5.webp',
  '/images/carrusel/peli6.webp',
  '/images/carrusel/peli7.webp',
  '/images/carrusel/peli8.webp',
  '/images/carrusel/peli9.webp',
  '/images/carrusel/peli10.webp',
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
/* ✅ Estilos base */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.main-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  color: #e6f1ff;
  padding-bottom: 2rem;
}

/* ✅ Barra de navegación */
.navbar {
  background: rgba(10, 25, 47, 0.8);
  backdrop-filter: blur(10px);
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  border-bottom: 1px solid rgba(100, 255, 218, 0.1);
}

.logo {
  width: 50px;
  height: 50px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.1);
}

.navbar h1 {
  font-size: 1.8rem;
  font-weight: 700;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin: 0;
}

.actions {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-left: auto;
}

.search-button,
.login-button,
.logout-button,
.admin-button {
  background: rgba(100, 255, 218, 0.1);
  border: 1px solid rgba(100, 255, 218, 0.2);
  border-radius: 8px;
  padding: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.search-button:hover,
.login-button:hover,
.logout-button:hover,
.admin-button:hover {
  background: rgba(100, 255, 218, 0.2);
  transform: translateY(-2px);
}

.icon-button {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.search-container {
  display: flex;
  align-items: center;
}

.search-bar {
  padding: 0.8rem 1.2rem;
  font-size: 1rem;
  background: rgba(10, 25, 47, 0.8);
  border: 1px solid rgba(100, 255, 218, 0.3);
  border-radius: 8px;
  color: #e6f1ff;
  width: 400px;
  transition: all 0.3s ease;
  margin-right: 20px;
}

.search-bar:focus {
  outline: none;
  border-color: #64ffda;
  box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2);
}

.carousel-container {
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 1200px;
  height: 500px;
  margin: 2rem auto;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.carousel-track {
  display: flex;
  height: 100%;
  transition: transform 0.5s ease-in-out;
}

.carousel-slide {
  flex: 0 0 100%;
  min-width: 100%;
}

.carousel-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.carousel-button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
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
}

.carousel-button:hover {
  background: rgba(30, 144, 255, 0.8);
  color: white;
}

.carousel-button.left {
  left: 20px;
}

.carousel-button.right {
  right: 20px;
}

.title-section {
  text-align: center;
  margin: 3rem 0 1.5rem;
}

.title-section h2 {
  font-size: 2rem;
  font-weight: 600;
  color: #e6f1ff;
  position: relative;
  display: inline-block;
}

.title-section h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  border-radius: 3px;
}

.popular-section,
.all-movies-section {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}

.popular-carousel-container {
  position: relative;
  overflow: hidden;
  width: 100%;
  margin: 0 auto 3rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(5px);
  border-radius: 12px;
  padding: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.popular-carousel-track {
  display: flex;
  transition: transform 0.5s ease;
}

.popular-movie-card {
  flex: 0 0 calc(100% / 3);
  padding: 1rem;
  cursor: pointer;
  transition: transform 0.3s ease;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  margin: 0.5rem;
}

.popular-movie-card:hover {
  transform: translateY(-10px);
  background: rgba(255, 255, 255, 0.1);
}

.popular-movie-card img {
  width: 100%;
  height: 350px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  border: 1px solid rgba(100, 255, 218, 0.1);
}

.popular-movie-card:hover img {
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
  border-color: rgba(100, 255, 218, 0.3);
}

.popular-movie-card h3 {
  color: #e6f1ff;
  font-size: 1.1rem;
  font-weight: 500;
  margin-top: 1rem;
  text-align: center;
}

.movies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
  margin: 0 auto;
}

.movie-card {
  cursor: pointer;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  padding: 1rem;
  overflow: hidden;
}

.movie-card:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.1);
}

.movie-card img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(100, 255, 218, 0.1);
  transition: all 0.3s ease;
}

.movie-card:hover img {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
  border-color: rgba(100, 255, 218, 0.3);
}

.movie-card h3 {
  color: #e6f1ff;
  font-size: 1rem;
  font-weight: 400;
  margin-top: 0.8rem;
  text-align: center;
}

@media (max-width: 1024px) {
  .popular-movie-card {
    flex: 0 0 calc(100% / 2);
  }
  
  .movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  }
}

@media (max-width: 768px) {
  .navbar {
    padding: 1rem;
    flex-wrap: wrap;
  }
  
  .navbar h1 {
    font-size: 1.5rem;
  }
  
  .search-bar {
    width: 300px;
    margin-left: 20px;
  }
  
  .carousel-container {
    height: 350px;
  }
  
  .popular-movie-card {
    flex: 0 0 100%;
  }
  
  .movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    padding: 0 1rem;
  }
  
  .movie-card img {
    height: 250px;
  }
}

@media (max-width: 480px) {
  .actions {
    gap: 0.5rem;
  }
  
  .search-bar {
    width: 150px;
    padding: 0.6rem;
  }
  
  .carousel-container {
    height: 250px;
  }
  
  .movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  }
  
  .movie-card img {
    height: 200px;
  }
}

.admin-options {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin: 2rem auto;
  max-width: 1200px;
  padding: 0 2rem;
}

.admin-options button {
  background: rgba(100, 255, 218, 0.1);
  border: 1px solid rgba(100, 255, 218, 0.3);
  color: #64ffda;
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(5px);
}

.admin-options button:hover {
  background: rgba(100, 255, 218, 0.2);
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  border-color: rgba(100, 255, 218, 0.5);
}

.admin-options button:active {
  transform: translateY(0);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
  .admin-options {
    flex-direction: column;
    gap: 1rem;
    align-items: center;
  }
  
  .admin-options button {
    width: 100%;
    max-width: 300px;
    padding: 0.8rem;
  }
}

/* Estilos para el modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: linear-gradient(135deg, #081e27, #02465d);
  padding: 2rem;
  border-radius: 12px;
  max-width: 400px;
  width: 90%;
  text-align: center;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(100, 255, 218, 0.2);
}

.modal-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #64ffda;
}

.modal-content h3 {
  color: #e6f1ff;
  margin-bottom: 1.5rem;
  font-weight: 500;
}

.modal-button {
  background: rgba(100, 255, 218, 0.1);
  border: 1px solid rgba(100, 255, 218, 0.3);
  color: #64ffda;
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.modal-button:hover {
  background: rgba(100, 255, 218, 0.2);
  transform: translateY(-2px);
}
</style>