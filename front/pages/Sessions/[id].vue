<template>
  <div>
    <!-- ✅ Navbar -->
    <nav class="navbar">
      <h1 class="title">Cine de Película</h1>
    </nav>

    <!-- ✅ Select para elegir sesión -->
    <div class="mt-4">
      <h2 class="text-xl font-bold mb-2">Selecciona tu sección</h2>
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

    <!-- ✅ Mostrar título de butacas si hay asientos -->
    <div v-if="seats.length" class="mt-4">
      <h3 class="text-lg font-semibold mb-2">Butacas:</h3>
      
      <p class="text-gray-600 mb-4">
        Todas las butacas tienen un descuento de <strong>2€</strong> en el <strong>día del espectador</strong>.  
        Las butacas de la <strong>columna F</strong> son <strong>VIP</strong>, ofreciendo una experiencia premium.
      </p>

      <div class="seats-container">
        <span 
          v-for="seat in seats" 
          :key="seat.seat_id"
          :class="[ 
            seat.status === 'reservada' 
              ? 'bg-red-500 text-white cursor-not-allowed' 
              : 'cursor-pointer hover:bg-green-500',
            seat.row === 'F' ? 'border-yellow-400 border-2' : '' 
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

    <!-- ✅ Modal para mostrar el precio -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h3 class="text-xl font-bold mb-2">Butaca seleccionada</h3>
        <p class="text-gray-700 mb-4">
          🎯 <strong>{{ selectedSeat.row }}{{ selectedSeat.seat_num }}</strong>  
          💰 Precio: <strong>{{ selectedSeatPrice.toFixed(2) }}€</strong>
        </p>
        <div class="modal-buttons">
          <button class="btn-reserve" @click="reserveSeat">Reservar</button>
          <button class="btn-cancel" @click="closeModal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'nuxt/app';
import CommunicationManager from '@/services/CommunicationManager';

const sessions = ref([]);
const selectedSession = ref('');
const seats = ref([]);
const showModal = ref(false);
const selectedSeat = ref({});
const selectedSeatPrice = ref(0);

const selectedSessionData = computed(() =>
  sessions.value.find(session => session.session_id === selectedSession.value) || {}
);

const fetchSessionsByMovie = async (movieId) => {
  try {
    if (!movieId) return;
    sessions.value = await CommunicationManager.fetchSessionsByMovie(movieId);
  } catch (error) {
    console.error('❌ Error al obtener sesiones:', error.message);
  }
};

const loadSeats = async (sessionId) => {
  if (!sessionId) return;
  try {
    seats.value = await CommunicationManager.fetchSeatsBySession(sessionId);
  } catch (error) {
    console.error('❌ Error al obtener butacas:', error.message);
  }
};

const selectSeat = (seat) => {
  if (seat.status === 'reservada') {
    alert('🚫 Esta butaca ya está reservada.');
    return;
  }

  const isSpecialDay = !!selectedSessionData.value?.special_day;
  const price = isSpecialDay ? seat?.precio_con_descuento : seat?.precio_normal;

  if (price !== undefined) {
    selectedSeat.value = seat;
    selectedSeatPrice.value = price;
    showModal.value = true;
  }
};

const closeModal = () => {
  showModal.value = false;
};

const reserveSeat = async () => {
  try {
    await CommunicationManager.reserveSeat(selectedSeat.value.seat_id, selectedSession.value);

    // ✅ Marcar asiento como reservado
    const seatIndex = seats.value.findIndex(seat => seat.seat_id === selectedSeat.value.seat_id);
    if (seatIndex !== -1) {
      seats.value[seatIndex].status = 'reservada';
    }

    alert(`🎉 ¡Butaca ${selectedSeat.value.row}${selectedSeat.value.seat_num} reservada con éxito!`);
    closeModal();
  } catch (error) {
    console.error('❌ Error al reservar la butaca:', error.message);
    alert('❌ No se pudo reservar la butaca.');
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(() => {
  const movieId = useRoute().params.id;
  if (movieId) fetchSessionsByMovie(movieId);
});

watch(selectedSession, (newSession) => {
  if (newSession) loadSeats(newSession);
});
</script>

<style scoped>
/* ✅ Navbar */
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

/* ✅ Butacas */
.seats-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
}

.seat {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px;
  height: 50px;
  border-radius: 6px;
  border: 1px solid #ccc;
  cursor: pointer;
  font-size: 16px;
}

.bg-red-500 {
  background-color: #ef4444;
}

.hover\:bg-green-500:hover {
  background-color: #22c55e;
}

/* ✅ Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
}

.modal-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn-reserve {
  background-color: #3b82f6;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
}

.btn-cancel {
  background-color: #ef4444;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
}
</style>
