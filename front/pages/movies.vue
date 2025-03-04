<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const movies = ref([])
const newMovie = ref({
  title: '',
  duration: '',
  genre: '',
  director: '',
  actors: '',
  sinopsis: '',
  premiere: '',
  room_id: ''
})
const editingMovie = ref(null)

const fetchMovies = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/movies')
    movies.value = response.data
  } catch (error) {
    console.error('Error al obtener las películas:', error)
  }
}

const createMovie = async () => {
  try {
    await axios.post('http://127.0.0.1:8000/api/movies', newMovie.value)
    fetchMovies()
    newMovie.value = {}
  } catch (error) {
    console.error('Error al crear la película:', error)
  }
}

const updateMovie = async (id) => {
  try {
    await axios.put(`http://127.0.0.1:8000/api/movies/${id}`, editingMovie.value)
    editingMovie.value = null
    fetchMovies()
  } catch (error) {
    console.error('Error al actualizar la película:', error)
  }
}

const deleteMovie = async (id) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/movies/${id}`)
    fetchMovies()
  } catch (error) {
    console.error('Error al eliminar la película:', error)
  }
}

onMounted(fetchMovies)
</script>

<template>
  <div class="container">
    <h1 class="title">🎬 Administrador de Películas</h1>

    <!-- Formulario para nueva película -->
    <div class="form-box">
      <h2>Agregar Nueva Película</h2>
      <input v-model="newMovie.title" placeholder="Título">
      <input v-model="newMovie.duration" placeholder="Duración (minutos)" type="number">
      <input v-model="newMovie.genre" placeholder="Género">
      <input v-model="newMovie.director" placeholder="Director">
      <input v-model="newMovie.actors" placeholder="Actores">
      <input v-model="newMovie.premiere" type="date">
      <input v-model="newMovie.room_id" placeholder="Sala" type="number">
      <textarea v-model="newMovie.sinopsis" placeholder="Sinopsis"></textarea>
      <button @click="createMovie" class="btn btn-primary">Crear Película</button>
    </div>

    <!-- Lista de películas -->
    <div class="movie-list">
      <h2>🎥 Lista de Películas</h2>
      <div v-for="movie in movies" :key="movie.id" class="movie-card">
        <h3>{{ movie.title }}</h3>
        <p><strong>Género:</strong> {{ movie.genre }}</p>
        <p><strong>Director:</strong> {{ movie.director }}</p>
        <p><strong>Actores:</strong> {{ movie.actors }}</p>
        <p><strong>Sinopsis:</strong> {{ movie.sinopsis }}</p>
        <p><strong>Estreno:</strong> {{ movie.premiere }}</p>
        <p><strong>Sala:</strong> {{ movie.room_id }}</p>
        <button @click="editingMovie = { ...movie }" class="btn btn-edit">Editar</button>
        <button @click="deleteMovie(movie.id)" class="btn btn-danger">Eliminar</button>
      </div>
    </div>

    <!-- Formulario para editar película -->
    <div v-if="editingMovie" class="form-box">
      <h2>✏️ Editar Película</h2>
      <input v-model="editingMovie.title">
      <input v-model="editingMovie.duration" type="number">
      <input v-model="editingMovie.genre">
      <input v-model="editingMovie.director">
      <input v-model="editingMovie.actors">
      <input v-model="editingMovie.premiere" type="date">
      <input v-model="editingMovie.room_id" type="number">
      <textarea v-model="editingMovie.sinopsis"></textarea>
      <button @click="updateMovie(editingMovie.id)" class="btn btn-primary">Actualizar</button>
      <button @click="editingMovie = null" class="btn btn-cancel">Cancelar</button>
    </div>
  </div>
</template>

<style>
/* Diseño general */
body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 800px;
  margin: 30px auto;
  padding: 20px;
  background: white;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

/* Títulos */
.title {
  text-align: center;
  font-size: 28px;
  color: #333;
  margin-bottom: 20px;
}

/* Formulario */
.form-box {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.form-box h2 {
  font-size: 20px;
  margin-bottom: 15px;
}

input, textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

textarea {
  height: 80px;
  resize: none;
}

/* Botones */
.btn {
  display: inline-block;
  padding: 10px 15px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-edit {
  background: #ffc107;
  color: black;
}

.btn-danger {
  background: #dc3545;
  color: white;
}

.btn-cancel {
  background: #6c757d;
  color: white;
}

.btn:hover {
  opacity: 0.8;
}

/* Lista de películas */
.movie-list h2 {
  font-size: 22px;
  margin-bottom: 10px;
}

.movie-card {
  background: white;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 5px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
}

.movie-card h3 {
  font-size: 18px;
  margin-bottom: 5px;
}

.movie-card p {
  margin: 5px 0;
}

.movie-card .btn {
  margin-right: 5px;
}
</style>
