<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const config = useRuntimeConfig();

// Estado para los asientos, el pop-up y el mensaje de error
const seats = ref([]);
const pending = ref(true);
const error = ref(null);
const reservedSeat = ref(null);  // Para almacenar el asiento que se ha reservado
const showPopup = ref(false);  // Para mostrar el pop-up

// Obtener los asientos de la película seleccionada
onMounted(async () => {
  try {
    const movieId = route.query.movie_id;
    const response = await fetch(`${config.public.apiBase}/seats/${movieId}`);
    const data = await response.json();
    
    if (data.seats) {
      seats.value = data.seats;
    } else {
      error.value = 'No hay asientos disponibles para esta película.';
    }
  } catch (err) {
    error.value = 'Error al cargar los asientos.';
  } finally {
    pending.value = false;
  }
});

// Función para manejar la reserva de un asiento
const reserveSeat = (seat) => {
  if (seat.status !== 'reserved') {
    seat.status = 'reserved';  // Cambiar el estado del asiento a reservado
    reservedSeat.value = seat;  // Guardar el asiento reservado
    showPopup.value = true;  // Mostrar el pop-up
  }
};

// Función para cerrar el pop-up
const closePopup = () => {
  showPopup.value = false;
  reservedSeat.value = null;
};
</script>

<template>
  <div class="container">
    <div v-if="pending" class="text-center">Cargando asientos...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>

    <div v-else class="seats">
      <h1>Selecciona tu asiento</h1>
      <div v-if="seats.length">
        <div 
          v-for="seat in seats" 
          :key="seat.id" 
          :class="['seat', seat.status === 'reserved' ? 'reserved' : '']"
          @click="reserveSeat(seat)"
        >
          <span>{{ seat.name }}</span>
        </div>
      </div>
      <p v-else>No hay asientos disponibles.</p>
    </div>

    <!-- Pop-up de confirmación -->
    <div v-if="showPopup" class="popup">
      <div class="popup-content">
        <h2>¡Asiento reservado!</h2>
        <p>El asiento {{ reservedSeat.name }} ha sido reservado con éxito.</p>
        <button @click="closePopup">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  text-align: center;
}

.seats {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.seat {
  display: inline-block;
  padding: 10px;
  margin: 5px;
  background-color: #007BFF;
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

.seat.reserved {
  background-color: #f44336;
  cursor: not-allowed;
}

h1 {
  color: #007BFF;
}

/* Estilos del pop-up */
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.popup-content h2 {
  color: #007BFF;
}

button {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
