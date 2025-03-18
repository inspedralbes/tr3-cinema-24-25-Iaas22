<script setup>
import { ref, onMounted } from 'vue'
import CommunicationManager from '@/services/CommunicationManager'

const movies = ref([])
const form = ref({
  title: '',
  genre: '',
  duration: null,
  director: '',
  actors: '',
  synopsis: '',
  release_date: '',
  img: ''
})
const editing = ref(false)
const currentId = ref(null)

// ✅ Cloudinary config
const CLOUD_NAME = 'dt5vjbgab'
const UPLOAD_PRESET = 'cinema'

// ✅ Obtener películas
const fetchMovies = async () => {
  try {
    movies.value = await CommunicationManager.fetchMovies()
  } catch (error) {
    console.error('❌ Error al obtener películas:', error)
  }
}

// ✅ Subir imagen a Cloudinary
const uploadImage = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('file', file)
  formData.append('upload_preset', UPLOAD_PRESET)

  try {
    const response = await fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`, {
      method: 'POST',
      body: formData
    })

    const data = await response.json()
    form.value.img = data.secure_url
    console.log('✅ Imagen subida:', form.value.img)
  } catch (error) {
    console.error('❌ Error al subir imagen:', error)
  }
}

// ✅ Crear o actualizar película
const handleSubmit = async () => {
  try {
    if (editing.value) {
      await CommunicationManager.updateMovie(currentId.value, form.value)
    } else {
      await CommunicationManager.createMovie(form.value)
    }
    resetForm()
    fetchMovies()
  } catch (error) {
    console.error('❌ Error al guardar película:', error)
  }
}

// ✅ Editar película
const editMovie = (movie) => {
  form.value = { ...movie }
  editing.value = true
  currentId.value = movie.movie_id
}

// ✅ Eliminar película
const deleteMovie = async (id) => {
  if (confirm('¿Seguro que deseas eliminar esta película?')) {
    try {
      await CommunicationManager.deleteMovie(id)
      fetchMovies()
    } catch (error) {
      console.error('❌ Error al eliminar película:', error)
    }
  }
}

// ✅ Resetear formulario
const resetForm = () => {
  form.value = {
    title: '',
    genre: '',
    duration: null,
    director: '',
    actors: '',
    synopsis: '',
    release_date: '',
    img: ''
  }
  editing.value = false
  currentId.value = null
}

// ✅ Cargar películas al montar el componente
onMounted(fetchMovies)
</script>

<template>
  <div>
    <h2>Películas</h2>
    <form @submit.prevent="handleSubmit">
      <input v-model="form.title" placeholder="Título" required />
      <input v-model="form.genre" placeholder="Género" required />
      <input v-model="form.duration" placeholder="Duración" type="number" required />
      <input v-model="form.director" placeholder="Director" required />
      <input v-model="form.actors" placeholder="Actores" required />
      <textarea v-model="form.synopsis" placeholder="Sinopsis" required></textarea>
      <input v-model="form.release_date" type="date" required />

      <!-- ✅ Carga de imagen -->
      <input type="file" @change="uploadImage" />
      <div v-if="form.img">
        <img :src="form.img" alt="Imagen de la película" style="width: 100px; height: auto; margin-top: 10px;" />
      </div>

      <button type="submit">{{ editing ? 'Actualizar' : 'Crear' }} Película</button>
      <button type="button" @click="resetForm">Cancelar</button>
    </form>

    <div v-if="movies.length">
      <ul>
        <li v-for="movie in movies" :key="movie.movie_id">
          <div>
            <strong>{{ movie.title }}</strong> ({{ movie.genre }}) - {{ movie.director }}
            <img v-if="movie.img" :src="movie.img" alt="Imagen de la película" style="width: 50px; height: auto; margin-left: 10px;" />
          </div>
          <button @click="editMovie(movie)">Editar</button>
          <button @click="deleteMovie(movie.movie_id)">Eliminar</button>
        </li>
      </ul>
    </div>
    <p v-else>No hay películas disponibles.</p>
  </div>
</template>
<style scoped>
/* ✅ Estilo general */
div {
  font-family: 'Arial', sans-serif;
  color: #333;
  padding: 16px;
  background-color: #f9f9f9;
}

/* ✅ Título */
h2 {
  font-size: 24px;
  color: #2c3e50;
  margin-bottom: 16px;
}

/* ✅ Formulario */
form {
  display: grid;
  gap: 12px;
  background: #ffffff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

input,
textarea,
button {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 16px;
  transition: border-color 0.3s ease;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: #3498db;
}

/* ✅ Botones */
button {
  background-color: #3498db;
  color: white;
  border: none;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover {
  background-color: #2980b9;
}

button[type='button'] {
  background-color: #e74c3c;
}

button[type='button']:hover {
  background-color: #c0392b;
}

/* ✅ Imagen de vista previa */
img {
  max-width: 100px;
  border-radius: 8px;
  object-fit: cover;
}

/* ✅ Lista de películas */
ul {
  list-style: none;
  padding: 0;
  display: grid;
  gap: 16px;
}

li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #ffffff;
  padding: 12px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

li img {
  width: 50px;
  height: auto;
  margin-left: 12px;
  border-radius: 6px;
}

li div {
  display: flex;
  align-items: center;
}

li button {
  margin-left: 8px;
}

/* ✅ Diseño responsive */
@media (max-width: 600px) {
  form,
  li {
    flex-direction: column;
    gap: 10px;
  }

  li img {
    width: 100%;
  }
}
</style>
