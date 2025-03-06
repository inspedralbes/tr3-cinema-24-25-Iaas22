<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';  // Importar useRouter para redirigir

const config = useRuntimeConfig();
const movies = ref([]); // Almacena las películas
const pending = ref(true); // Estado para saber si se está cargando
const error = ref(null); // Almacena el error en caso de que ocurra
const router = useRouter(); // Instancia del enrutador

// Obtener las películas desde la API
const fetchMovies = async () => {
  try {
    const response = await fetch(`${config.public.apiBase}/movies`);
    if (response.ok) {
      movies.value = await response.json();
    } else {
      error.value = 'Error al cargar las películas';
    }
  } catch (err) {
    error.value = 'Error al cargar las películas: ' + err.message;
  } finally {
    pending.value = false; // Termina el proceso de carga
  }
};

// Llamada a la función al montar el componente
onMounted(() => {
  fetchMovies();
});

// Función para redirigir a la página de detalles de la película
const goToMovieDetails = (id) => {
  router.push(`/movie/infoMovies/${id}`);  // Redirige a la página de detalles de la película con el id
};
</script>

<template>
  <div>
    <div v-if="pending" class="text-center">Cargando películas...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>

    <div v-else class="movie-grid">
      <div 
        v-for="movie in movies" 
        :key="movie.id" 
        class="movie-card"
        @click="goToMovieDetails(movie.id)" 
      >
        <img v-if="movie.img" :src="movie.img" alt="Imagen de la película" class="movie-img" />
        <h2>{{ movie.title }}</h2>
        <p>{{ movie.genre }}</p>
        <p>{{ movie.duration }} min</p>
        <p>{{ movie.director }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
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
}

.movie-img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
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
