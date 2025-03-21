<template>
  <div>
    <!-- ✅ Navbar -->
    <nav class="navbar">
      <h1 class="title">Cine de Película</h1>
      <button @click="toggleCart" class="btn-cart">
        🛒 Ver Cesta ({{ reservations.length }})
      </button>
    </nav>

    <!-- ✅ Sidebar como componente externo -->
    <Cart 
      :reservations="reservations"
      :cartOpen="cartOpen"
      @toggle-cart="toggleCart"
      @cancel-reservation="cancelReservation"
    />

    <!-- ✅ Select para elegir sesión -->
    <div class="mt-4">
      <h2 class="text-xl font-bold mb-2">Selecciona tu sesión</h2>
      <select 
        v-model="selectedSession" 
        @change="onSessionChange"
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

   <!-- ✅ Mostrar butacas disponibles -->
<div v-if="seats.length" class="mt-4">
  <h3 class="text-lg font-semibold mb-2">Butacas:</h3>
  <!-- ✅ Texto de información -->
<div class="mt-4 text-sm text-gray-600">
  <ul class="list-disc list-inside">
    <li>🎯 El Día del Espectador, todas las entradas tienen un descuento de 2€.</li>
    <li>💺 La fila VIP (F) normalmente cuesta 8€.</li>
    <li>🎟️ Las demás filas tienen un precio de 6€.</li>
  </ul>
</div>

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

  <!-- ✅ Botón de reservar -->
  <div class="mt-4 text-center">
    <button 
      v-if="selectedSeats.length" 
      @click="reserveSeats" 
      class="btn-reserve"
    >
      🎟️ Reservar Butacas ({{ selectedSeats.length }})
    </button>
  </div>
</div>
 <!-- ✅ Formulario de confirmación como popup -->
<div v-if="showConfirmationForm" class="modal-container">
  <div class="modal-content">
    <button  @click="() => { 
        showConfirmationForm = false; 
        if (confirmationData.seats.length) {
          cancelReservation(confirmationData.seatIds[0]);
        }
      }" 
      class="close-btn">✖️</button>
    <h2 class="text-xl font-bold mb-4">Confirmar Reserva</h2>

    <!-- ✅ Mostrar información de la reserva -->
    <div class="mb-4">
      <p><strong>🎬 Película:</strong> {{ confirmationData.movieTitle }}</p>
      <p><strong>🕒 Hora:</strong> {{ confirmationData.sessionTime }}</p>
      <p><strong>📅 Fecha:</strong> {{ formatDate(confirmationData.sessionDate) }}</p>
      <p><strong>💺 Butacas:</strong></p>
      <ul>
        <li 
          v-for="seat in confirmationData.seats" 
          :key="seat.seatNum"
        >
          Fila {{ seat.row }}, Asiento {{ seat.seatNum }} – {{ seat.price }}€
        </li>
      </ul>
    </div>

    <!-- ✅ Formulario de usuario -->
    <form @submit.prevent="handleConfirmReservation">
      <input 
        type="text"
        v-model="confirmationData.name"
        placeholder="Nombre"
        required
        class="input-field"
      />
      <input 
        type="text"
        v-model="confirmationData.lastName"
        placeholder="Apellidos"
        required
        class="input-field"
      />
      <input 
        type="email"
        v-model="confirmationData.email"
        placeholder="Correo electrónico"
        required
        class="input-field"
      />

      <button type="submit" class="btn-confirm">
        ✅ Confirmar Reserva
      </button>
    </form>
  </div>
</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import CommunicationManager from '@/services/CommunicationManager';
import Cart from '@/components/Cart.vue';

const sessions = ref([]);
const selectedSession = ref('');
const seats = ref([]);
const selectedSeats = ref([]);
const reservations = ref([]);
const cartOpen = ref(false);
const showConfirmationForm = ref(false);
const reservaId = ref(null); // 👈 Asegúrate de que exista



const confirmationData = ref({
  sessionId: '',
  reservaId: '',
  seatIds: [],
  name: '',
  lastName: '',
  email: ''
});

const toggleCart = () => {
  cartOpen.value = !cartOpen.value;
};
const fetchSessions = async () => {
  try {
    // Necesitas pasar el ID de la película
    const movieId = 1; // (Ejemplo, reemplaza con el ID real)
    const response = await CommunicationManager.fetchSessionsByMovie(movieId);
    sessions.value = response;
  } catch (error) {
    console.error('❌ Error al cargar las sesiones:', error.message);
  }
};

const fetchSeats = async (sessionId) => {
  try {
    if (!sessionId) throw new Error('ID de sesión no proporcionado');
    const response = await CommunicationManager.fetchSeatsBySession(sessionId);
    seats.value = response;
  } catch (error) {
    console.error('❌ Error al cargar las butacas:', error.message);
  }
};

const reserveSeats = async () => {
  if (!selectedSession.value) {
    alert('⚠️ Primero debes seleccionar una sesión.');
    return;
  }

  if (!selectedSeats.value.length) {
    alert('⚠️ No hay butacas seleccionadas.');
    return;
  }

  try {
    await CommunicationManager.reserveSeats(selectedSeats.value, selectedSession.value);

    alert('✅ ¡Reserva completada con éxito!');

    // 👉 Obtener detalles de la película y butacas
    const session = sessions.value.find(s => s.session_id === selectedSession.value);
    const selectedSeatsDetails = selectedSeats.value.map(seatId => {
      const seat = seats.value.find(s => s.seat_id === seatId);
      return {
        row: seat.row,
        seatNum: seat.seat_num,
        price: seat.row === 'F' ? 8 : 6 // Precio basado en la fila
      };
    });

    confirmationData.value = {
      sessionId: selectedSession.value,
      reservaId: Math.floor(Math.random() * 1000),
      seatIds: [...selectedSeats.value],
      movieTitle: session.movie.title,
      sessionTime: session.session_time,
      sessionDate: session.session_date,
      seats: selectedSeatsDetails,
      name: '',
      lastName: '',
      email: ''
    };

    showConfirmationForm.value = true;

    // 👉 Limpiar la selección y actualizar las butacas
    selectedSeats.value = [];
    await fetchSeats(selectedSession.value);
    await fetchReservations();

  } catch (error) {
    console.error('❌ Error al reservar las butacas:', error);
    alert(`❌ Error: ${error.message}`);
  }
};

const handleConfirmReservation = async () => {
  try {
    if (
      !confirmationData.value.name ||
      !confirmationData.value.lastName ||
      !confirmationData.value.email
    ) {
      throw new Error('⚠️ Faltan datos para confirmar la reserva');
    }

    await CommunicationManager.confirmReservation(
      confirmationData.value.sessionId,
      confirmationData.value.reservaId,
      confirmationData.value.seatIds,
      confirmationData.value.name,
      confirmationData.value.lastName,
      confirmationData.value.email
    );

    alert('✅ Reserva confirmada correctamente');

    // 👉 Limpiar el formulario después de confirmar
    confirmationData.value = {
      sessionId: '',
      reservaId: '',
      seatIds: [],
      name: '',
      lastName: '',
      email: ''
    };

    showConfirmationForm.value = false;

    // 🔄 Actualiza las reservas después de confirmar
    await fetchReservations();

  } catch (error) {
    console.error('❌ Error al confirmar la reserva:', error);
    alert(error.message);
  }
};

const fetchReservations = async () => {
  try {
    const response = await CommunicationManager.fetchReservationsByUser();
    reservations.value = response;
  } catch (error) {
    console.error('❌ Error al cargar las reservas:', error.message);
  }
};

const cancelReservation = async (seatId) => {
  if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) return;
  try {
    await CommunicationManager.cancelReservation(seatId);
    reservations.value = reservations.value.filter(r => r.seat_id !== seatId);
  } catch (error) {
    console.error('❌ Error al cancelar la reserva:', error.message);
  }
};

const toggleSeat = (seat) => {
  if (seat.status === 'reservada') return;

  if (selectedSeats.value.includes(seat.seat_id)) {
    selectedSeats.value = selectedSeats.value.filter(id => id !== seat.seat_id);
  } else {
    selectedSeats.value.push(seat.seat_id);
  }
};

// ✅ Ahora el evento `@change` controla la carga de butacas
const onSessionChange = () => {
  if (selectedSession.value) {
    console.log('➡️ Cargando butacas para sesión:', selectedSession.value);
    fetchSeats(selectedSession.value);
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
  fetchSessions(); // ✅ Carga inicial de sesiones
  fetchReservations(); // ✅ Carga inicial de reservas
});
</script>

<style scoped>
.navbar {
  background-color: #1e3a8a;
  padding: 1rem;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.title {
  font-size: 1.5rem;
  font-weight: bold;
}

.btn-cart {
  background-color: #3b82f6;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  color: white;
  font-size: 1rem;
  cursor: pointer;
}

.btn-cart:hover {
  background-color: #2563eb;
}
.seats-container {
  display: grid;
  grid-template-columns: repeat(10, 1fr); /* 10 columnas */
  gap: 20px; /* Más espacio entre las butacas */
  justify-content: center; /* Centrar horizontalmente */
  align-items: center; /* Centrar verticalmente */
  margin: 0 auto; /* Asegurar que esté centrado */
  max-width: calc(10 * 40px + 9 * 4px);
  margin-bottom: 50px; 

}

.seat {
  width: 40px;
  height: 40px;
  background-color: #f3f4f6;
  border: 1px solid #d1d5db;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 6px;
  font-size: 14px;
  transition: all 0.2s ease;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
}


.seat:hover {
  background-color: #86efac;
  transform: scale(1.1);
}

.bg-green-400 {
  background-color: #34d399;
}

.bg-red-500 {
  background-color: #ef4444;
  color: white;
}

.border-blue-400 {
  border-color: #3b82f6;
}

.btn-reserve {
  background-color: #3b82f6;
  padding: 0.75rem 1.5rem;
  color: white;
  font-size: 1rem;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.1s ease;
  box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.15);
  align-items: center;
  right: 200px;
}

.btn-reserve:hover {
  background-color: #2563eb;
  transform: scale(1.05);
}

/* ✅ Fondo oscuro semi-transparente */
.modal-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro con opacidad */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
  animation: fade-in 0.3s ease;
}

/* ✅ Contenido del popup */
.modal-content {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
  position: relative;
  animation: scale-up 0.3s ease;
}

/* ✅ Botón de cerrar */
.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #888;
  transition: color 0.2s ease;
}

.close-btn:hover {
  color: #000;
}

/* ✅ Campos de entrada */
.input-field {
  width: 100%;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  outline: none;
  transition: border 0.2s ease;
  font-size: 1rem;
}

.input-field:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
}

/* ✅ Botón de confirmar */
.btn-confirm {
  background-color: #3b82f6;
  padding: 0.75rem;
  width: 100%;
  color: white;
  font-size: 1rem;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s ease;
}

.btn-confirm:hover {
  background-color: #2563eb;
}

/* ✅ Animación de apertura */
@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes scale-up {
  from {
    transform: scale(0.9);
  }
  to {
    transform: scale(1);
  }
}

</style>
