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
