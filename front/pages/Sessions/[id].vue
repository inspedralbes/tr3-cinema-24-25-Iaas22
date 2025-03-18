<template>
  <div>
    <!-- ✅ Navbar -->
    <nav class="navbar">
      <h1 class="title">Cine de Película</h1>
      <button @click="toggleCart" class="btn-cart">
        🛒 Ver Cesta ({{ reservations.length }})
      </button>
    </nav>

    <!-- ✅ Sidebar para carrito -->
    <div :class="['sidebar', cartOpen ? 'translate-x-0' : 'translate-x-full']">
      <div class="sidebar-content">
        <button @click="toggleCart" class="close-btn">❌</button>
        <h3 class="text-lg font-semibold mb-2">🛒 Cesta de reservas:</h3>
        <ul class="space-y-2">
          <li 
            v-for="reservation in reservations" 
            :key="reservation.seat_id" 
            class="flex justify-between items-center border-b py-2"
          >
            <div>
              🎬 <strong>{{ reservation.movie }}</strong> <br>
              📅 {{ formatDate(reservation.date) }} - 🕒 {{ reservation.time }}<br>
              🎯 Butaca: {{ reservation.row }}{{ reservation.seat_num }} ({{ reservation.type }})
            </div>
            <button 
              @click="cancelReservation(reservation.seat_id)"
              class="text-red-500 hover:text-red-700 font-bold"
            >
              ❌ Cancelar
            </button>
          </li>
        </ul>
      </div>
    </div>

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

const reservations = ref([]);
const cartOpen = ref(false); // ✅ Nueva ref para controlar el carrito

// ✅ Función para abrir/cerrar el carrito
const toggleCart = () => {
  cartOpen.value = !cartOpen.value;
};

const fetchReservations = async () => {
  try {
    // 🔑 ID del usuario autenticado
    const userId = 1; // Cambiar esto para que use el ID real del usuario autenticado
    reservations.value = await CommunicationManager.fetchReservationsByUser();

  } catch (error) {
    console.error('❌ Error al cargar las reservas:', error.message);
  }
};

const cancelReservation = async (seatId) => {
  if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) return;

  try {
    // Endpoint para cancelar la reserva (opcional, si no existe debes crearlo)
    await CommunicationManager.cancelReservation(seatId);
    reservations.value = reservations.value.filter(r => r.seat_id !== seatId);
  } catch (error) {
    console.error('❌ Error al cancelar la reserva:', error.message);
    alert('❌ No se pudo cancelar la reserva.');
  }
};

// ✅ Cargar reservas al montar el componente
onMounted(() => {
  fetchReservations();
});


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

.btn-cart {
  background-color: #3b82f6;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s ease;
}

.btn-cart:hover {
  background-color: #2563eb;
}

.sidebar {
  position: fixed;
  top: 0;
  right: 0;
  width: 300px;
  height: 100%;
  background-color: #f9fafb;
  border-left: 1px solid #e5e7eb;
  padding: 20px;
  overflow-y: auto;
  box-shadow: -4px 0 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
  z-index: 50;
}

.translate-x-full {
  transform: translateX(100%);
}

.translate-x-0 {
  transform: translateX(0);
}

.close-btn {
  background-color: #ef4444;
  color: white;
  padding: 0.3rem 0.8rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  margin-bottom: 1rem;
  transition: background 0.2s ease;
}

.close-btn:hover {
  background-color: #dc2626;
}

</style>
