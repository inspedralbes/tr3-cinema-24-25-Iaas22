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
  <div class="movies-container">
    <div class="movies-card">
      <h2 class="movies-title">Gestión de Películas</h2>
      
      <form @submit.prevent="handleSubmit" class="movies-form">
        <div class="form-group">
          <label>Título</label>
          <input v-model="form.title" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Género</label>
          <input v-model="form.genre" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Duración (minutos)</label>
          <input v-model="form.duration" type="number" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Director</label>
          <input v-model="form.director" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Actores</label>
          <input v-model="form.actors" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Sinopsis</label>
          <textarea v-model="form.synopsis" class="form-input" required></textarea>
        </div>
        
        <div class="form-group">
          <label>Fecha de estreno</label>
          <input v-model="form.release_date" type="date" class="form-input" required />
        </div>
        
        <div class="form-group">
          <label>Imagen</label>
          <input type="file" @change="uploadImage" class="form-input" />
          <div v-if="form.img" class="image-preview">
            <img :src="form.img" alt="Imagen de la película" />
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="submit-button">
            {{ editing ? 'Actualizar' : 'Crear' }} Película
          </button>
          <button type="button" @click="resetForm" class="cancel-button">
            Cancelar
          </button>
        </div>
      </form>

      <div class="movies-list">
        <h3 class="list-title">Películas Registradas</h3>
        
        <div v-if="movies.length" class="movie-items">
          <div v-for="movie in movies" :key="movie.movie_id" class="movie-item">
            <div class="movie-info">
              <div class="movie-header">
                <h4>{{ movie.title }}</h4>
                <span class="movie-genre">{{ movie.genre }}</span>
              </div>
              <p class="movie-director">Director: {{ movie.director }}</p>
              <p class="movie-duration">Duración: {{ movie.duration }} minutos</p>
              <img v-if="movie.img" :src="movie.img" alt="Imagen de la película" />
            </div>
            <div class="movie-actions">
              <button @click="editMovie(movie)" class="edit-button">Editar</button>
              <button @click="deleteMovie(movie.movie_id)" class="delete-button">Eliminar</button>
            </div>
          </div>
        </div>
        <p v-else class="empty-message">No hay películas disponibles.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* ✅ Fondo azul oscuro con gradiente */
.movies-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 2rem 1rem;
}

/* ✅ Tarjeta principal con efecto de vidrio (glassmorphism) */
.movies-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  padding: 2.5rem;
  width: 100%;
  max-width: 900px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.movies-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
}

/* ✅ Título */
.movies-title {
  font-size: 2rem;
  font-weight: 600;
  color: #ffffff;
  margin-bottom: 2rem;
  text-align: center;
  letter-spacing: 0.5px;
}

.list-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #ffffff;
  margin: 2rem 0 1rem;
  letter-spacing: 0.5px;
}

/* ✅ Formulario */
.movies-form {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  background: rgba(255, 255, 255, 0.05);
  padding: 1.5rem;
  border-radius: 12px;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 0.5rem;
  display: block;
  font-weight: 500;
}

.form-input {
  width: 350px;
  padding: 0.9rem 1.2rem;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.1);
  color: #ffffff;
}

.form-input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.form-input:focus {
  border-color: rgba(255, 255, 255, 0.4);
  box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.15);
  outline: none;
}

textarea.form-input {
  min-height: 100px;
  resize: vertical;
}

/* ✅ Imagen de vista previa */
.image-preview {
  margin-top: 10px;
}

.image-preview img {
  max-width: 100px;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* ✅ Botones del formulario */
.form-actions {
  grid-column: span 2;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

.submit-button {
  padding: 1rem 1.5rem;
  background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
}

.submit-button:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.cancel-button {
  padding: 1rem 1.5rem;
  background: rgba(255, 255, 255, 0.1);
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
}

.cancel-button:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* ✅ Lista de películas */
.movies-list {
  background: rgba(255, 255, 255, 0.05);
  padding: 1.5rem;
  border-radius: 12px;
}

.movie-items {
  display: grid;
  gap: 1rem;
}

.movie-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  padding: 1.2rem;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.movie-item:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.movie-info {
  flex: 1;
}

.movie-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.movie-header h4 {
  font-size: 1.1rem;
  color: #ffffff;
  margin: 0;
}

.movie-genre {
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.7);
  background: rgba(255, 255, 255, 0.1);
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
}

.movie-director, .movie-duration {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
  margin: 0.3rem 0;
}

.movie-info img {
  max-width: 60px;
  border-radius: 6px;
  margin-top: 0.5rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.movie-actions {
  display: flex;
  gap: 0.8rem;
}

.edit-button {
  padding: 0.6rem 1rem;
  background: rgba(74, 144, 226, 0.2);
  color: #4a90e2;
  font-size: 0.9rem;
  font-weight: 600;
  border: 1px solid rgba(74, 144, 226, 0.3);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-button:hover {
  background: rgba(74, 144, 226, 0.3);
  transform: translateY(-2px);
}

.delete-button {
  padding: 0.6rem 1rem;
  background: rgba(231, 76, 60, 0.2);
  color: #e74c3c;
  font-size: 0.9rem;
  font-weight: 600;
  border: 1px solid rgba(231, 76, 60, 0.3);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.delete-button:hover {
  background: rgba(231, 76, 60, 0.3);
  transform: translateY(-2px);
}

.empty-message {
  color: rgba(255, 255, 255, 0.6);
  text-align: center;
  padding: 1rem;
  font-style: italic;
}

/* ✅ Diseño responsive */
@media (max-width: 768px) {
  .movies-form {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    grid-column: span 1;
  }
  
  .movie-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .movie-actions {
    width: 100%;
    justify-content: flex-end;
  }
}

@media (max-width: 480px) {
  .movies-card {
    padding: 1.5rem;
  }
  
  .movies-title {
    font-size: 1.7rem;
    margin-bottom: 1.5rem;
  }
  
  .form-input {
    padding: 0.8rem 1rem;
  }
  
  .submit-button, .cancel-button {
    padding: 0.9rem 1.2rem;
    font-size: 0.95rem;
  }
}
</style>