<template>
  <div>
    <!-- ✅ Navbar -->
    <nav class="navbar">
      <h1 class="title">Cine de Película</h1>
    </nav>

    <!-- ✅ Select para elegir sesión -->
    <div class="mt-4">
      <h2 class="text-xl font-bold mb-2">Selecciona tu sesión</h2>
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
    </div>

    <!-- ✅ Información sobre precios y descuentos -->
    <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-md">
      <p class="text-blue-800 font-semibold">
        🎯 Información de precios:
      </p>
      <ul class="list-disc list-inside text-blue-700 mt-2">
        <li> Las butacas VIP (columna F) cuestan <strong>8 €</strong>.</li>
        <li>Las demás butacas cuestan <strong>6 €</strong>.</li>
        <li> <strong>Día del espectador:</strong> ¡Todas las butacas tienen <strong>2 € de descuento!</strong></li>
      </ul>
    </div>

    <!-- ✅ Mostrar butacas disponibles -->
    <div v-if="seats.length" class="mt-4">
      <h3 class="text-lg font-semibold mb-2">Butacas:</h3>
      <div class="seats-container">
        <span 
          v-for="seat in seats" 
          :key="seat.seat_id"
          :class="[ 
            seat.status === 'reservada' ? 'bg-red-500 text-white cursor-not-allowed' : 'cursor-pointer hover:bg-green-500',
            seat.row === 'F' ? 'border-blue-400 border-2' : '',
            selectedSeats.includes(seat.seat_id) ? 'bg-green-400' : ''
          ]"
          @click="toggleSeat(seat)"
          class="seat"
        >
          {{ seat.row }}{{ seat.seat_num }}
        </span>
      </div>
    </div>

    <!-- ✅ Confirmar reserva -->
    <div v-if="selectedSeats.length" class="mt-4">
      <h3 class="text-lg font-semibold mb-2">Butacas seleccionadas:</h3>
      <div>
        {{ selectedSeats.map(seatId => formatSeat(seatId)).join(', ') }}
      </div>
      <button 
        class="btn-reserve mt-4" 
        @click="confirmReservation"
      >
        Reservar ({{ selectedSeats.length }}/10)
      </button>
    </div>

    <!-- ✅ Mostrar mensaje solo si no hay butacas y no se están cargando -->
    <div v-if="selectedSession && seats.length === 0 && !loadingSeats" class="mt-4 text-gray-500">
      No hay butacas disponibles para esta sesión.
    </div>

    <!-- ✅ Mostrar indicador de carga mientras se cargan las butacas -->
    <div v-if="loadingSeats" class="mt-4 text-gray-500">
      🔄 Cargando butacas...
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'nuxt/app';
import CommunicationManager from '@/services/CommunicationManager';

const sessions = ref([]);
const selectedSession = ref('');
const seats = ref([]);
const selectedSeats = ref([]);
const loadingSeats = ref(false);
const router = useRouter();

// ✅ Obtener sesiones por película
const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) return;
    sessions.value = await CommunicationManager.fetchSessionsByMovie(movieId);
  } catch (error) {
    console.error('❌ Error al obtener sesiones:', error.message);
  }
};

// ✅ Cargar butacas por sesión
const loadSeats = async (sessionId) => {
  if (!sessionId) return;
  loadingSeats.value = true;
  try {
    seats.value = await CommunicationManager.fetchSeatsBySession(sessionId);
  } catch (error) {
    console.error('❌ Error al obtener butacas:', error.message);
  } finally {
    loadingSeats.value = false;
  }
};

// ✅ Alternar selección de butacas
const toggleSeat = (seat) => {
  if (seat.status === 'reservada') {
    alert('🚫 Esta butaca ya está reservada.');
    return;
  }

  if (selectedSeats.value.includes(seat.seat_id)) {
    selectedSeats.value = selectedSeats.value.filter(id => id !== seat.seat_id);
  } else {
    if (selectedSeats.value.length < 10) {
      selectedSeats.value.push(seat.seat_id);
    } else {
      alert('🚫 Solo puedes reservar hasta 10 butacas.');
    }
  }
};

// ✅ Confirmar reserva
const confirmReservation = async () => {
  if (!selectedSeats.value.length) return;

  try {
    await CommunicationManager.reserveSeats(selectedSeats.value, selectedSession.value);

    // ✅ Actualizar estado de butacas reservadas
    seats.value.forEach(seat => {
      if (selectedSeats.value.includes(seat.seat_id)) {
        seat.status = 'reservada';
      }
    });

    selectedSeats.value = [];
    
  
  } catch (error) {
    console.error('❌ Error al reservar las butacas:', error.message);
    alert('❌ No se pudieron reservar las butacas.');
  
};}



// ✅ Formatear asiento
const formatSeat = (seatId) => {
  const seat = seats.value.find(s => s.seat_id === seatId);
  return seat ? `${seat.row}${seat.seat_num}` : '';
};

// ✅ Formatear fecha
const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// ✅ Obtener las sesiones al montar el componente
onMounted(() => {
  const movieId = useRoute().params.id;
  if (movieId) fetchSessionsByMovie(movieId);
});

// ✅ Cargar las butacas al cambiar la sesión seleccionada
watch(selectedSession, (newSession) => {
  if (newSession) loadSeats(newSession);
});
</script>

<style scoped>
.navbar {
  background-color: #333;
  color: white;
  padding: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.title {
  font-size: 1.5rem;
}

.seats-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
}

.seat {
  width: 50px;
  height: 50px;
  border-radius: 6px;
  border: 1px solid #ccc;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  cursor: pointer;
}

.bg-red-500 {
  background-color: #ef4444;
}

.bg-green-400 {
  background-color: #22c55e;
}

.border-blue-400 {
  border-color: #3b82f6;
}
</style>
