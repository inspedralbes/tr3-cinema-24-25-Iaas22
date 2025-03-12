<template>
  <div>
    <!-- ✅ Título para seleccionar sesión -->
    <h2 class="text-xl font-bold mb-2">Selecciona tu sección</h2>
    
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

    <!-- ✅ Mostrar título de butacas si hay asientos -->
    <div v-if="seats.length" class="mt-4">
      <h3 class="text-lg font-semibold mb-2">Butacas:</h3>
      
      <!-- ✅ Mostrar butacas en línea con tamaño uniforme -->
      <div class="flex flex-wrap gap-2">
        <span 
          v-for="(seat) in seats" 
          :key="seat.seat_id"
          :class="[ 
            seat.status === 'reservada' 
              ? 'bg-red-500 text-white cursor-not-allowed' 
              : 'cursor-pointer hover:bg-green-500' 
          ]"
          @click="selectSeat(seat)"
          class="seat"
        >
          {{ seat.row }}{{ seat.seat_num }}
        </span>
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

const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) return

    const response = await axios.get(`http://localhost:8000/api/sessions/movie/${movieId}`)
    sessions.value = response.data || []
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error.message || error)
  }
}

const loadSeats = async (sessionId) => {
  if (!sessionId) return
  try {
    const response = await axios.get(`http://localhost:8000/api/seats/session/${sessionId}`)
    seats.value = response.data || []
  } catch (error) {
    console.error('❌ Error al obtener las butacas:', error.message || error)
  }
}

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

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  const movieId = useRoute().params.id
  if (movieId) fetchSessionsByMovie(movieId)
})

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

.seat {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px; /* Ancho fijo */
  height: 50px; /* Alto fijo */
  border-radius: 6px;
  border: 1px solid #ccc;
  cursor: pointer;
  transition: background-color 0.2s;
  font-size: 16px;
}

.bg-red-500 {
  background-color: #ef4444;
}

.hover\:bg-green-500:hover {
  background-color: #22c55e;
}

.cursor-not-allowed {
  cursor: not-allowed;
}

/* Ajuste de espaciado para que el diseño quede uniforme */
.flex-wrap {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
</style>
