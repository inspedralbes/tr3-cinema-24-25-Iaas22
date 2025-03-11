<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">🎬 Selecciona una sesión disponible</h1>
    
    <!-- Select para elegir sesión -->
    <div v-if="sessions.length">
      <select 
        v-model="selectedSession" 
        class="w-full p-3 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-400"
      >
        <option value="" disabled>Elige una sesión</option>
        <option 
          v-for="session in sessions" 
          :key="session.id ?? 'sin-id'" 
          :value="String(session.id) || ''"
        >
          🎬 {{ session.movie.title }} - 🕒 {{ session.session_time }} - 📅 {{ formatDate(session.session_date) }}
        </option>
      </select>

      <!-- Botón para confirmar sesión -->
      <button 
        @click="fetchSeats(selectedSession)"
        class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition"
        :disabled="!selectedSession"
      >
        🚀 Ver Butacas
      </button>
    </div>

    <!-- Mostrar sesiones disponibles -->
    <div v-else class="text-center text-gray-500">
      No hay sesiones disponibles para esta película.
    </div>

    <!-- Mostrar butacas disponibles -->
    <div v-if="seats.length" class="mt-6">
      <h2 class="text-2xl font-semibold mb-3">🪑 Butacas Disponibles</h2>
      <div class="grid grid-cols-4 gap-2">
        <div 
          v-for="(seat, index) in uniqueSeats" 
          :key="seat.seat_id || index"
          :class="{
            'bg-green-400': seat.status === 'disponible',
            'bg-red-400': seat.status === 'reservado'
          }"
          class="p-3 text-center rounded-lg text-white font-bold cursor-pointer"
        >
          {{ seat.row }}{{ seat.seat_num }}
        </div>
      </div>
    </div>

    <!-- Mostrar mensaje si no hay butacas disponibles -->
    <div v-else-if="seatsLoaded" class="text-center text-gray-500 mt-4">
      No hay butacas disponibles para esta sesión.
    </div>
  </div>
</template>

---

### ✅ **Script Corregido**
```js
<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute } from 'nuxt/app'
import axios from 'axios'

const sessions = ref([]) // Lista de sesiones
const selectedSession = ref('') // Sesión seleccionada
const seats = ref([]) // Lista de butacas
const seatsLoaded = ref(false) // Estado para saber si se cargaron las butacas
const route = useRoute()

// 🔥 Obtener sesiones por ID de película
const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) {
      console.error('❌ ID de película no proporcionado')
      return
    }

    console.log(`📥 Obteniendo sesiones para película: ${movieId}`)
    const response = await axios.get(`http://localhost:8000/api/sessions/movie/${movieId}`)
    
    if (response.data && response.data.length) {
      sessions.value = response.data
      console.log('🎯 Sesiones recibidas:', sessions.value)
    } else {
      console.warn('⚠️ No hay sesiones disponibles para esta película')
      sessions.value = []
    }
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error.message || error)
  }
}

// 🔥 Obtener butacas por ID de sesión
const fetchSeats = async (sessionId) => {
  seatsLoaded.value = false
  seats.value = [] // Limpiar las butacas antes de cargar nuevas
  
  if (!sessionId) {
    console.error('❌ ID de sesión no proporcionado')
    return
  }

  console.log(`🚀 Obteniendo butacas para sesión: ${sessionId}`)
  try {
    const response = await axios.get(`http://localhost:8000/api/seats/session/${sessionId}`)
    if (response.data && response.data.length) {
      seats.value = response.data
      console.log('🪑 Butacas recibidas:', seats.value)
    } else {
      console.warn('⚠️ No hay butacas disponibles para esta sesión')
    }
  } catch (error) {
    console.error('❌ Error al obtener las butacas:', error.message || error)
  } finally {
    seatsLoaded.value = true
  }
}

// ✅ Eliminar butacas duplicadas por seat_id
const uniqueSeats = computed(() => {
  const seen = new Set()
  return seats.value.filter(seat => {
    if (seen.has(seat.seat_id)) return false
    seen.add(seat.seat_id)
    return true
  })
})

// 🔎 Verificar valor seleccionado para depuración
watch(selectedSession, (newValue) => {
  console.log('🔎 Sesión seleccionada:', newValue)
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// 🔥 Obtener el ID de la ruta dinámicamente
onMounted(() => {
  const movieId = route.params.id
  console.log('🎬 ID de la película:', movieId)
  if (movieId) fetchSessionsByMovie(movieId)
})
</script>
