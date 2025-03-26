<template>
    <div class="main-container">
   
    <!-- ✅ Navbar -->
    <nav class="navbar">
       <!-- ✅ Botón de volver atrás -->
    <!-- Botón de volver -->
    <button class="back-button" @click="$router.push('/')">
        ⬅️
      </button>
      <img src="/images/logoo.png" alt="Logo" class="logo" />

      <h1>C𝕚𝕟𝕖Y𝕒</h1>
      <button @click="toggleCart" class="btn-cart">
        <img src="/images/ticket.svg" alt="Carrito" class="icon-button" />
        <span class="cart-count">{{ reservations.length }}</span>
      </button>
    </nav>

    <!-- ✅ Sidebar como componente externo -->
    <Cart 
      :reservations="reservations"
      :cartOpen="cartOpen"
      @toggle-cart="toggleCart"
      @cancel-reservation="cancelReservation"
    />

    <div class="content-wrapper">
      <!-- ✅ Select para elegir sesión -->
      <div class="session-selector">
        <h2 class="section-title">Selecciona tu sesión</h2>
        <select 
          v-model="selectedSession" 
          @change="onSessionChange"
          class="custom-select"
        >
          <option value="" disabled>Elige una sesión</option>
          <option 
            v-for="session in sessions" 
            :key="session.session_id" 
            :value="session.session_id"
          >
            🎬 {{ session.movie.title }} - 🕒 {{ session.session_time }} - 📅 {{ formatDate(session.session_date) }}
            <span v-if="session.special_day" class="special-day">🌟 Especial</span>
          </option>
        </select>
      </div>

      <!-- ✅ Mostrar butacas disponibles -->
      <div v-if="seats.length" class="seats-section">
        <h3 class="section-title">Butacas disponibles</h3>
        
        <div class="info-box">
          <ul class="info-list">
            <li>🎯 Día del Espectador: Descuento de 2€ en todas las entradas</li>
            <li>💺 Fila VIP (F): Precio de 8€</li>
            <li>🎟️ Otras filas: Precio de 6€</li>
          </ul>
        </div>

        <div class="seats-container">
          <div 
            v-for="seat in seats" 
            :key="seat.seat_id"
            :class="[
              'seat',
              seat.status === 'reservada' ? 'reserved' : 'available',
              seat.row === 'F' ? 'vip' : '',
              selectedSeats.includes(seat.seat_id) ? 'selected' : ''
            ]"
            @click="toggleSeat(seat)"
          >
            {{ seat.row }}{{ seat.seat_num }}
          </div>
        </div>

        <!-- ✅ Botón de reservar -->
        <button 
          v-if="selectedSeats.length" 
          @click="reserveSeats" 
          class="reserve-btn"
        >
          Reservar {{ selectedSeats.length }} butaca(s)
        </button>
      </div>
    </div>

    <!-- ✅ Formulario de confirmación como popup -->
    <div v-if="showConfirmationForm" class="modal-overlay">
      <div class="modal-content">
        <button @click="closeConfirmation" class="close-btn">
          <img src="/images/close.png" alt="Cerrar" class="icon-button" />
        </button>

        <h2 class="modal-title">Confirmar Reserva</h2>

        <div class="reservation-details">
          <div class="detail-item">
            <span class="detail-label">Película:</span>
            <span class="detail-value">{{ confirmationData.movieTitle }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Hora:</span>
            <span class="detail-value">{{ confirmationData.sessionTime }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Fecha:</span>
            <span class="detail-value">{{ formatDate(confirmationData.sessionDate) }}</span>
          </div>
          
          <div class="seats-summary">
            <h4>Butacas seleccionadas:</h4>
            <div 
              v-for="seat in confirmationData.seats" 
              :key="seat.seatNum"
              class="seat-item"
            >
              Fila {{ seat.row }}, Asiento {{ seat.seatNum }} - {{ seat.price }}€
            </div>
          </div>
          
          <div class="total-price">
            Total: {{ confirmationData.totalPrice.toFixed(2) }}€
          </div>
        </div>

        <form @submit.prevent="handleConfirmReservation" class="reservation-form">
          <div class="form-group">
            <input 
              type="text"
              v-model="confirmationData.name"
              placeholder="Nombre"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <input 
              type="text"
              v-model="confirmationData.lastName"
              placeholder="Apellidos"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <input 
              type="email"
              v-model="confirmationData.email"
              placeholder="Correo electrónico"
              required
              class="form-input"
            />
          </div>

          <button type="submit" class="confirm-btn">
            Confirmar Reserva
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

const confirmationData = ref({
  sessionId: '',
  reservaId: '',
  seatIds: [],
  movieTitle: '',
  sessionTime: '',
  sessionDate: '',
  seats: [],
  totalPrice: 0,
  name: '',
  lastName: '',
  email: ''
});

const toggleCart = () => {
  cartOpen.value = !cartOpen.value;
};

const fetchSessions = async () => {
  try {
    const movieId = 1; // Reemplaza con el ID real
    const response = await CommunicationManager.fetchSessionsByMovie(movieId);
    sessions.value = response;
  } catch (error) {
    console.error('Error al cargar las sesiones:', error.message);
  }
};

const fetchSeats = async (sessionId) => {
  try {
    if (!sessionId) throw new Error('ID de sesión no proporcionado');
    const response = await CommunicationManager.fetchSeatsBySession(sessionId);
    seats.value = response;
  } catch (error) {
    console.error('Error al cargar las butacas:', error.message);
  }
};

const reserveSeats = async () => {
  if (!selectedSession.value) {
    alert('Primero debes seleccionar una sesión.');
    return;
  }

  if (!selectedSeats.value.length) {
    alert('No hay butacas seleccionadas.');
    return;
  }

  try {
    await CommunicationManager.reserveSeats(selectedSeats.value, selectedSession.value);

    const session = sessions.value.find(s => s.session_id === selectedSession.value);
    const isSpecialDay = session.special_day;

    const selectedSeatsDetails = selectedSeats.value.map(seatId => {
      const seat = seats.value.find(s => s.seat_id === seatId);
      let basePrice = seat.row === 'F' ? 8 : 6;
      let finalPrice = isSpecialDay ? basePrice - 2 : basePrice;

      return {
        row: seat.row,
        seatNum: seat.seat_num,
        price: finalPrice
      };
    });

    const totalPrice = selectedSeatsDetails.reduce((sum, seat) => sum + seat.price, 0);

    confirmationData.value = {
      sessionId: selectedSession.value,
      reservaId: Math.floor(Math.random() * 1000),
      seatIds: [...selectedSeats.value],
      movieTitle: session.movie.title,
      sessionTime: session.session_time,
      sessionDate: session.session_date,
      seats: selectedSeatsDetails,
      totalPrice,
      name: '',
      lastName: '',
      email: ''
    };

    showConfirmationForm.value = true;
    selectedSeats.value = [];

    await fetchSeats(selectedSession.value);
    await fetchReservations();

  } catch (error) {
    console.error('Error al reservar las butacas:', error);
    alert(`Error: ${error.message}`);
  }
};

const handleConfirmReservation = async () => {
  try {
    if (!confirmationData.value.name || !confirmationData.value.lastName || !confirmationData.value.email) {
      throw new Error('Faltan datos para confirmar la reserva');
    }

    await CommunicationManager.confirmReservation(
      confirmationData.value.sessionId,
      confirmationData.value.reservaId,
      confirmationData.value.seatIds,
      confirmationData.value.name,
      confirmationData.value.lastName,
      confirmationData.value.email
    );

    alert('Reserva confirmada correctamente');

    confirmationData.value = {
      sessionId: '',
      reservaId: '',
      seatIds: [],
      name: '',
      lastName: '',
      email: ''
    };

    showConfirmationForm.value = false;
    await fetchReservations();

  } catch (error) {
    console.error('Error al confirmar la reserva:', error);
    alert(error.message);
  }
};

const fetchReservations = async () => {
  try {
    const response = await CommunicationManager.fetchReservationsByUser();
    reservations.value = response;
  } catch (error) {
    console.error('Error al cargar las reservas:', error.message);
  }
};

const cancelReservation = async (seatId) => {
  if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) return;
  try {
    await CommunicationManager.cancelReservation(seatId);
    reservations.value = reservations.value.filter(r => r.seat_id !== seatId);
  } catch (error) {
    console.error('Error al cancelar la reserva:', error.message);
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

const onSessionChange = () => {
  if (selectedSession.value) {
    fetchSeats(selectedSession.value);
  }
};

const closeConfirmation = () => {
  showConfirmationForm.value = false;
  if (confirmationData.value.seats.length) {
    confirmationData.value.seatIds.forEach(seatId => cancelReservation(seatId));
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
  fetchSessions();
  fetchReservations();
});
</script>

<style scoped>
/* ✅ Estilos base */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.main-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  color: #e6f1ff;
  padding-bottom: 2rem;
}

/* ✅ Barra de navegación */
.navbar {
  background: rgba(10, 25, 47, 0.8);
  backdrop-filter: blur(10px);
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  border-bottom: 1px solid rgba(100, 255, 218, 0.1);
  padding-left: 4rem; 

}
.logo {
  width: 50px;
  height: 50px;
  object-fit: contain;
  transition: transform 0.3s ease;
}
img.logo{
  margin-left: 50px;
}
.logo:hover {
  transform: scale(1.1);
}
.navbar h1 {
  font-size: 1.8rem;
  font-weight: 700;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin: 0;
}
.title {
  font-size: 1.8rem;
  font-weight: 700;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin: 0;
}
/* ✅ Botón de volver */
.back-button {
  position: absolute;
  top: 1.5rem;
  left: 1.5rem;
  background: rgba(10, 25, 47, 0.7);
  color: #64ffda;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  transition: all 0.3s ease;
  border: 1px solid rgba(100, 255, 218, 0.3);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.back-button:hover {
  background: rgba(30, 144, 255, 0.8);
  color: white;
  transform: translateX(-3px);
}
.btn-cart {
  background: rgba(100, 255, 218, 0.1);
  border: 1px solid rgba(100, 255, 218, 0.2);
  border-radius: 8px;
  padding: 0.5rem 1rem;
  color: #64ffda;
  font-size: 20px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-cart:hover {
  background: rgba(100, 255, 218, 0.2);
  transform: translateY(-2px);
}

.icon-button {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.cart-count {
  background: rgba(255, 255, 255, 0.1);
  padding: 0.2rem 0.5rem;
  border-radius: 50%;
}

/* ✅ Contenido principal */
.content-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #64ffda;
  margin-bottom: 1rem;
  position: relative;
  display: inline-block;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 60px;
  height: 2px;
  background: linear-gradient(90deg, #64ffda, #1e90ff);
  border-radius: 2px;
}

/* ✅ Selector de sesión */
.session-selector {
  margin-bottom: 2rem;
}

.custom-select {
  width: 100%;
  padding: 0.9rem 1.2rem;
  font-size: 1rem;
  background: rgba(10, 25, 47, 0.8);
  border: 1px solid rgba(100, 255, 218, 0.3);
  border-radius: 8px;
  color: #e6f1ff;
  transition: all 0.3s ease;
}

.custom-select:focus {
  outline: none;
  border-color: #64ffda;
  box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2);
}

.special-day {
  color: #ffd700;
  font-weight: 600;
}

/* ✅ Sección de butacas */
.seats-section {
  margin-top: 2rem;
}

.info-box {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(5px);
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(100, 255, 218, 0.1);
}

.info-list {
  list-style: none;
  padding-left: 1rem;
}

.info-list li {
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.8);
  position: relative;
}

.info-list li::before {
  content: '•';
  color: #64ffda;
  font-weight: bold;
  display: inline-block;
  width: 1em;
  margin-left: -1em;
}

.seats-container {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  gap: 0.8rem;
  margin: 1.5rem 0;
}

.seat {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.2s ease;
  cursor: pointer;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #e6f1ff;
}

.seat:hover {
  transform: scale(1.1);
}

.seat.available:hover {
  background: rgba(100, 255, 218, 0.3);
}

.seat.selected {
  background: rgba(52, 211, 153, 0.7);
  color: #011721;
  font-weight: 600;
}

.seat.reserved {
  background: rgba(239, 68, 68, 0.7);
  cursor: not-allowed;
}

.seat.vip {
  border: 2px solid #3b82f6;
}

/* ✅ Botón de reservar */
.reserve-btn {
  background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
  padding: 0.9rem 1.8rem;
  color: white;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: block;
  margin: 2rem auto 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.reserve-btn:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

/* ✅ Modal de confirmación */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: rgba(10, 25, 47, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 2rem;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(100, 255, 218, 0.2);
  animation: fadeIn 0.3s ease;
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.close-btn:hover {
  transform: rotate(90deg);
}

.modal-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #64ffda;
  margin-bottom: 1.5rem;
  text-align: center;
}

.reservation-details {
  margin-bottom: 1.5rem;
}

.detail-item {
  display: flex;
  margin-bottom: 0.8rem;
}

.detail-label {
  font-weight: 600;
  color: #64ffda;
  min-width: 100px;
}

.detail-value {
  color: #e6f1ff;
}

.seats-summary {
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.seats-summary h4 {
  font-size: 1.1rem;
  margin-bottom: 0.8rem;
  color: #64ffda;
}

.seat-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.total-price {
  font-size: 1.2rem;
  font-weight: 600;
  text-align: right;
  margin-top: 1rem;
  color: #64ffda;
}

.reservation-form {
  margin-top: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-input {
  width: 100%;
  padding: 0.9rem 1.2rem;
  font-size: 1rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(100, 255, 218, 0.3);
  border-radius: 8px;
  color: #e6f1ff;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #64ffda;
  box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2);
}

.form-input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.confirm-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
  color: white;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.confirm-btn:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* ✅ Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ✅ Responsive */
@media (max-width: 1024px) {
  .seats-container {
    grid-template-columns: repeat(8, 1fr);
  }
  
  .content-wrapper {
    padding: 0 1.5rem;
  }
}

@media (max-width: 768px) {
  .seats-container {
    grid-template-columns: repeat(6, 1fr);
  }
  
  .navbar {
    padding: 1rem;
  }
  
  .title {
    font-size: 1.5rem;
  }
  
  .modal-content {
    padding: 1.5rem;
    margin: 1rem;
  }
}

@media (max-width: 576px) {
  .seats-container {
    grid-template-columns: repeat(5, 1fr);
    gap: 0.6rem;
  }
  
  .seat {
    width: 35px;
    height: 35px;
    font-size: 0.8rem;
  }
  
  .section-title {
    font-size: 1.3rem;
  }
  
  .reserve-btn {
    padding: 0.8rem 1.5rem;
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .seats-container {
    grid-template-columns: repeat(4, 1fr);
  }
  
  .content-wrapper {
    padding: 0 1rem;
  }
  
  .navbar {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .btn-cart {
    width: 100%;
    justify-content: center;
  }
  
  .seat {
    width: 30px;
    height: 30px;
    font-size: 0.7rem;
  }
}
</style>