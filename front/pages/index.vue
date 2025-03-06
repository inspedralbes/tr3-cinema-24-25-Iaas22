<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar">
      <h1>Lista de Películas</h1>
      <!-- Lupa y barra de búsqueda -->
      <div class="search-container">
        <button @click="toggleSearch">
          <img src="https://img.icons8.com/material-outlined/24/000000/search.png" alt="lupa" />
        </button>
        <div v-if="searchVisible" class="search-bar">
          <input type="text" v-model="searchQuery" @input="filterMovies" placeholder="Buscar películas..."/>
        </div>
      </div>
    </nav>

    <div v-if="loading">Cargando películas...</div>
    <div v-if="error" class="error">
      <p>Error al cargar las películas: {{ error.message }}</p>
    </div>

    <div v-if="movies.length && !selectedMovie">
      <!-- Lista de películas, solo se muestra si no hay una película seleccionada -->
      <div class="movies-grid">
        <div 
          v-for="(movie, index) in filteredMovies" 
          :key="movie.id" 
          class="movie-card"
          @click="selectMovie(movie)"
        >
          <h3>{{ movie.title }}</h3>
          <img :src="movie.img" alt="Imagen de la película" v-if="movie.img" />
        </div>
      </div>
    </div>

    <!-- Detalles de la película seleccionada -->
    <div v-if="selectedMovie" class="movie-details">
      <h2>{{ selectedMovie.title }}</h2>
      <p><strong>Género:</strong> {{ selectedMovie.genre }}</p>
      <p><strong>Duración:</strong> {{ selectedMovie.duration }} minutos</p>
      <p><strong>Sinopsis:</strong> {{ selectedMovie.synopsis }}</p>
      <p><strong>Director:</strong> {{ selectedMovie.director }}</p>
      <p><strong>Actores:</strong> {{ selectedMovie.actors }}</p>
      <p><strong>Estreno:</strong> {{ selectedMovie.release_date }}</p>
      <img :src="selectedMovie.img" alt="Imagen de la película" v-if="selectedMovie.img" />
      <button @click="deselectMovie">Volver</button>
    </div>
  </div>
</template>

<script>
import CommunicationManager from '@/services/CommunicationManager';

export default {
  data() {
    return {
      movies: [],
      filteredMovies: [], // Array para almacenar las películas filtradas
      searchQuery: '', // Para almacenar el texto de búsqueda
      loading: true,
      error: null,
      selectedMovie: null, // Aquí guardamos la película seleccionada
      searchVisible: false, // Para controlar la visibilidad de la barra de búsqueda
    };
  },
  async created() {
    try {
      const movies = await CommunicationManager.fetchMovies();
      this.movies = movies;
      this.filteredMovies = movies; // Inicialmente mostramos todas las películas
    } catch (error) {
      this.error = error;
    } finally {
      this.loading = false;
    }
  },
  methods: {
    // Método para seleccionar una película
    selectMovie(movie) {
      this.selectedMovie = movie;
    },
    // Método para deseleccionar la película y volver a la lista
    deselectMovie() {
      this.selectedMovie = null;
    },
    // Método para mostrar u ocultar la barra de búsqueda
    toggleSearch() {
      this.searchVisible = !this.searchVisible;
    },
    // Método para filtrar las películas por la búsqueda
    filterMovies() {
      this.filteredMovies = this.movies.filter(movie =>
        movie.title.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  }
};
</script>
<style scoped>
/* Estilos generales */
.error {
  color: red;
  text-align: center;
}

/* Estilos base para el grid */
.movies-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr); /* 1 columna por defecto en móvil */
  gap: 15px;
  margin-top: 20px;
}

.movie-card {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  text-align: center;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  cursor: pointer;
}

.movie-card h3 {
  font-size: 16px;
  margin-bottom: 10px;
}

.movie-card img {
  max-width: 100%;
  height: auto;
  margin-top: 10px;
}

/* Estilos para la sección de detalles de la película */
.movie-details {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.movie-details h2 {
  font-size: 24px;
}

.movie-details img {
  max-width: 100%;
  height: auto;
  margin-top: 20px;
}

.movie-details button {
  margin-top: 20px;
  padding: 10px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.movie-details button:hover {
  background-color: #0056b3;
}

/* Estilos para la barra de búsqueda */
.search-container {
  position: absolute;
  right: 20px;
  top: 50px; /* Baja el botón de lupa */
  display: flex;
  align-items: center;
}

.search-container button {
  background: none;
  border: none;
  cursor: pointer;
}

.search-bar {
  display: flex;
  align-items: center;
  margin-left: 10px; /* Espacio entre la lupa y la barra */
}

.search-bar input {
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
  width: 200px;
}

.search-bar input:focus {
  border-color: #007bff;
  outline: none;
}

/* Estilos para pantallas de tablet en adelante */
@media (min-width: 600px) {
  .movies-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columnas para pantallas medianas */
  }
  .movie-card h3 {
    font-size: 18px;
  }
}

/* Estilos para pantallas de escritorio */
@media (min-width: 1024px) {
  .movies-grid {
    grid-template-columns: repeat(5, 1fr); /* 5 columnas en pantallas grandes */
  }
  .movie-card h3 {
    font-size: 20px;
  }
  /* Estilos para el Navbar */
.navbar {
  background-color: #333;
  padding: 10px 20px;
  text-align: center;
}

.navbar ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: center;
}

.navbar li {
  margin: 0 15px;
}

.navbar a {
  color: white;
  text-decoration: none;
  font-size: 18px;
}

.navbar a:hover {
  text-decoration: underline;
}

}
</style>
