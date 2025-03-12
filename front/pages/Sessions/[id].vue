<template>
  <div>
    <!-- Select para elegir sesión -->
    <select 
      v-model="selectedSession" 
      class="w-full p-3 border rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-400"
    >
      <option value="" disabled>Elige una sesión</option>
      <option 
        v-for="session in sessions" 
        :key="session.session_id" 
        :value="session.session_id" 
      >
        🎬 {{ session.movie.title }} - 🕒 {{ session.session_time }} - 📅 {{ formatDate(session.session_date) }}
      </option>
    </select>

    <!-- ✅ Botón para cargar las butacas -->
    <button 
      @click.prevent="loadSeats(selectedSession)"
      class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200"
    >
      🚀 Ver Butacas
    </button>

    <!-- ✅ Mostrar butacas -->
    <div v-if="seats.length" class="mt-6 grid grid-cols-5 gap-2">
      <div 
        v-for="seat in seats" 
        :key="seat.seat_id"
        :class="[
          'w-12 h-12 flex items-center justify-center border rounded-lg transition duration-200',
          seat.status === 'reservada' 
            ? 'bg-red-500 text-white cursor-not-allowed' 
            : 'bg-green-400 text-white cursor-pointer hover:bg-green-500'
        ]"
        @click="selectSeat(seat)"
      >
        {{ seat.row }}{{ seat.seat_num }}
      </div>
    </div>

    <!-- ✅ Si no hay butacas -->
    <div v-else-if="selectedSession" class="mt-4 text-gray-500">
      No hay butacas disponibles para esta sesión.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'nuxt/app'
import axios from 'axios'

const sessions = ref([])
const selectedSession = ref('')
const seats = ref([])
const route = useRoute()

// ✅ Obtener sesiones por ID de película
const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) return
    console.log('Buscando sesiones para movieId:', movieId)

    const response = await axios.get(`http://localhost:8000/api/sessions/movie/${movieId}`)
    sessions.value = response.data || []
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error.message || error)
  }
}

// ✅ Obtener butacas por sesión
const loadSeats = async (sessionId) => {
  console.log('👉 sessionId recibido:', sessionId)
  if (!sessionId) {
    console.warn('❌ sessionId no válido:', sessionId)
    return
  }
  try {
    const response = await axios.get(`http://localhost:8000/api/seats/session/${sessionId}`)
    seats.value = response.data || []
    console.log('✅ Butacas cargadas:', seats.value)
  } catch (error) {
    console.error('❌ Error al obtener las butacas:', error.message || error)
  }
}

// ✅ Seleccionar butaca
const selectSeat = (seat) => {
  if (seat.status === 'reservada') {
    alert('🚫 Esta butaca ya está reservada.')
    return
  }
  alert(`✅ Has seleccionado la butaca: ${seat.row}${seat.seat_num}`)
}

// ✅ Formatear fecha
const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// ✅ Obtener el ID de la ruta dinámicamente
onMounted(() => {
  const movieId = route.params.id
  console.log('🎯 ID de la película:', movieId)
  if (movieId) fetchSessionsByMovie(movieId)
})
</script>

<style scoped>
/* ✅ Mejora de estilos */
select, button {
  transition: all 0.2s ease-in-out;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.bg-red-500 {
  background-color: #ef4444; /* Color rojo fuerte */
}

.bg-green-400 {
  background-color: #4ade80; /* Color verde claro */
}

.bg-green-500:hover {
  background-color: #22c55e; /* Verde más oscuro al pasar el mouse */
}
</style>
