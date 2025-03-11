<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">🎬 Selecciona una sesión disponible</h1>
    
    <div v-if="sessions.length">
      <!-- Select para elegir sesión -->
      <select 
        v-model="selectedSession" 
        class="w-full p-3 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-400"
      >
        <option value="" disabled>Elige una sesión</option>
        <option 
          v-for="session in sessions" 
          :key="session.id" 
          :value="session.id"
        >
          🎬 {{ session.movie.title }} - 🕒 {{ session.session_time }} - 📅 {{ formatDate(session.session_date) }}
        </option>
      </select>

      <!-- Botón para confirmar sesión -->
      <button 
        @click="goToSeats(selectedSession)"
        class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 disabled:bg-gray-400 transition"
        :disabled="!selectedSession"
      >
        🚀 Ver Butacas
      </button>
    </div>
    
    <!-- Mostrar sesiones disponibles -->
    <div v-else class="text-center text-gray-500">
      No hay sesiones disponibles para esta película.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'nuxt/app'
import axios from 'axios'

const sessions = ref([]) // Lista de sesiones
const selectedSession = ref('') // Sesión seleccionada
const router = useRouter()
const route = useRoute()

// 🔥 Obtener sesiones por ID de película
const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) {
      console.error('❌ ID de película no proporcionado')
      return
    }
    
    const response = await axios.get(`http://localhost:8000/api/sessions/movie/${movieId}`)
    sessions.value = response.data
    console.log('📥 Sesiones recibidas:', sessions.value)
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error)
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// 🚀 Redirigir a las butacas de la sesión seleccionada
const goToSeats = (id) => {
  if (id) {
    router.push(`/seats/${id}`)
  }
}

// 🔥 Obtener el ID de la ruta dinámicamente
onMounted(() => {
  const movieId = route.params.id // ✅ ID de la película desde la ruta
  console.log('🎬 ID de la película:', movieId)
  fetchSessionsByMovie(movieId)
})
</script>

<style scoped>
h1 {
  color: #1a202c;
}

button {
  transition: background-color 0.3s;
}
</style>
