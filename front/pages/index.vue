<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const config = useRuntimeConfig();
const router = useRouter();

const { data: movies, pending, error } = useFetch(`${config.public.apiBase}/movies`);

const showSearch = ref(false);
const searchQuery = ref("");
const isAuthenticated = ref(false); // Simulación de estado de autenticación
const showModal = ref(false); // Estado para controlar el modal
const selectedMovie = ref(null);

// Mostrar/Ocultar la barra de búsqueda
const toggleSearch = () => {
  showSearch.value = !showSearch.value;
  if (!showSearch.value) searchQuery.value = ""; // Limpia el input al cerrar
};

// Filtrar películas en base al texto ingresado
const filteredMovies = computed(() => {
  if (!searchQuery.value) return movies.value;
  return movies.value.filter(movie =>
    movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Manejar clic en una película
const handleMovieClick = (movie) => {
  if (!isAuthenticated.value) {
    selectedMovie.value = movie;
    showModal.value = true;
  } else {
    router.push(`/detallesMovies?id=${movie.id}`);
  }
};

// Redirigir al login
const goToLogin = () => {
  showModal.value = false;
  router.push('/login'); // Asegúrate de tener la ruta de login configurada en Vue Router
};
</script>

<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar">
      <h1 class="navbar-title">🎬 Cartelera</h1>
      <div class="navbar-right">
        <transition name="fade">
          <input 
            v-if="showSearch"
            v-model="searchQuery"
            type="text"
            placeholder="Buscar películas..."
            class="search-bar"
          />
        </transition>
        <button @click="toggleSearch" class="search-button">🔍</button>
      </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
      <button @click="router.push('/movies')" class="crud-button">Ir al CRUD</button>
      
      <div v-if="pending" class="text-center">Cargando películas...</div>
      <div v-else-if="error" class="text-red-500">Error al cargar películas.</div>
      
      <div v-else class="movie-grid">
        <div 
          v-for="movie in filteredMovies" 
          :key="movie.id" 
          class="movie-card" 
          @click="handleMovieClick(movie)"
        >
          <h2>{{ movie.title }}</h2>
        </div>
      </div>
    </div>

    <!-- Modal de Login -->
    <div v-if="showModal" class="modal">
      <div class="modal-content">
        <h2>🔒 Acceso restringido</h2>
        <p>Debes iniciar sesión o registrarte para ver los detalles de esta película.</p>
        <div class="modal-buttons">
          <button @click="goToLogin" class="login-button">Iniciar sesión</button>
          <button @click="showModal = false" class="close-button">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Navbar */
.navbar {
  background-color: #002147;
  color: white;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.navbar-title {
  font-size: 22px;
  font-weight: bold;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.search-button {
  background: transparent;
  border: none;
  color: white;
  font-size: 22px;
  cursor: pointer;
}

.search-bar {
  padding: 8px;
  border-radius: 5px;
  border: none;
  font-size: 16px;
  outline: none;
  transition: width 0.3s ease-in-out;
  width: 380px;
  margin-top: 10px;
  height: 30px;
}

/* Contenedor */
.container {
  max-width: 100%;
  margin: 20px auto;
  text-align: center;
  padding: 20px;
}

.crud-button {
  background-color: #010b15;
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
  background-color: #074486;
}

/* Grid de películas */
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
  color: #f8f7f7;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

/* Modal */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  max-width: 400px;
}

.modal-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 15px;
}

.login-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
}

.close-button {
  background-color: #ccc;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
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
