<template>
  <div>
    <!-- ✅ Select para elegir sesión -->
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
        <span v-if="session.special_day" class="ml-2 text-yellow-500 font-bold">🌟 Especial</span>
      </option>
    </select>

    <!-- ✅ Botón para cargar las butacas -->
    <button 
      @click.prevent="loadSeats(selectedSession)"
      :disabled="!selectedSession"
      class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
    >
      🚀 Ver Butacas
    </button>

    <!-- ✅ Mostrar butacas -->
    <div v-if="seats.length" class="mt-6 grid grid-cols-5 gap-2">
      <div 
        v-for="seat in seats" 
        :key="seat.seat_id"
        :class="[ 
          'w-16 h-16 flex items-center justify-center border rounded-lg transition duration-200',
          seat.status === 'reservada'
            ? 'bg-red-500 text-white cursor-not-allowed' 
            : 'bg-green-400 text-white cursor-pointer hover:bg-green-500'
        ]"
        @click="selectSeat(seat)"
      >
        <span class="font-bold">{{ seat.row }}{{ seat.seat_num }}</span>
      </div>
    </div>

    <!-- ✅ Si no hay butacas disponibles -->
    <div v-else-if="selectedSession" class="mt-4 text-gray-500">
      No hay butacas disponibles para esta sesión.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'nuxt/app'
import axios from 'axios'

const sessions = ref([])
const selectedSession = ref('')
const seats = ref([])

const selectedSessionData = computed(() => 
  sessions.value.find(session => session.session_id === selectedSession.value) || {}
)

// ✅ Obtener sesiones por ID de película
const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) return

    const response = await axios.get(`http://localhost:8000/api/sessions/movie/${movieId}`)
    sessions.value = response.data || []
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error.message || error)
  }
}

// ✅ Obtener butacas por sesión
const loadSeats = async (sessionId) => {
  if (!sessionId) return
  try {
    const response = await axios.get(`http://localhost:8000/api/seats/session/${sessionId}`)
    seats.value = response.data || []
  } catch (error) {
    console.error('❌ Error al obtener las butacas:', error.message || error)
  }
}

// ✅ Seleccionar butaca y mostrar precio
const selectSeat = (seat) => {
  if (seat.status === 'reservada') {
    alert('🚫 Esta butaca ya está reservada.')
    return
  }

  const isSpecialDay = !!selectedSessionData.value?.special_day
  const price = isSpecialDay ? seat?.precio_con_descuento : seat?.precio_normal

  if (price !== undefined) {
    alert(`✅ Butaca seleccionada: ${seat.row}${seat.seat_num} - Precio: ${price.toFixed(2)}€`)
  } else {
    alert('⚠️ No se pudo obtener el precio de la butaca.')
  }
}

// ✅ Formatear fecha (en español)
const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// ✅ Obtener el ID de la película de la URL dinámicamente
onMounted(() => {
  const movieId = useRoute().params.id
  if (movieId) fetchSessionsByMovie(movieId)
})

// ✅ Recargar butacas automáticamente al cambiar de sesión
watch(selectedSession, (newSession) => {
  if (newSession) {
    loadSeats(newSession)
  }
})
</script>

<style scoped>
select, button {
  transition: all 0.2s ease-in-out;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.bg-red-500 {
  background-color: #ef4444;
}

.bg-green-400 {
  background-color: #4ade80;
}

.bg-green-500:hover {
  background-color: #22c55e;
}
</style>
