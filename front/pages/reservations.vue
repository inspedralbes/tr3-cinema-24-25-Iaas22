<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const config = useRuntimeConfig();

// Estado para los asientos
const seats = ref([]);
const pending = ref(true);
const error = ref(null);

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
</script>

<template>
  <div class="container">
    <div v-if="pending" class="text-center">Cargando asientos...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>

    <div v-else class="seats">
      <h1>Selecciona tu asiento</h1>
      <div v-if="seats.length">
        <div v-for="seat in seats" :key="seat.id" :class="['seat', seat.status === 'reserved' ? 'reserved' : '']">
          <span>{{ seat.name }}</span>
        </div>
      </div>
      <p v-else>No hay asientos disponibles.</p>
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
</style>
