<template>
    <div class="reservation-crud">
      <h2>Gestión de Reservas</h2>
  
      <!-- Selector de Fechas Disponibles -->
      <div>
        <label for="reservation-date">Selecciona una fecha:</label>
        <select v-model="selectedDate" @change="fetchConfirmedReservations">
          <option v-for="date in availableDates" :key="date" :value="date">
            {{ date }}
          </option>
        </select>
      </div>
  
      <!-- Mostrar los totales por tipo de asiento -->
      <div v-if="countByType">
        <p><strong>Total de reservas confirmadas:</strong> {{ countByType.normal }} Normal / {{ countByType.vip }} VIP</p>
      </div>
  
      <!-- Lista de Reservas Confirmadas -->
      <div v-if="reservations.length > 0">
        <h3>Reservas Confirmadas para {{ selectedDate }}:</h3>
        <ul>
          <li v-for="reservation in reservations" :key="reservation.reserva_id">
            <p>Usuario: {{ reservation.user.name }} ({{ reservation.user.email }})</p>
            <p>Pelicula: {{ reservation.session.movie }}</p>
            <p>Asiento: {{ reservation.seat.row }} {{ reservation.seat.number }} - {{ reservation.seat.type }}</p>
            <p>Precio: ${{ reservation.reservation_details.price }}</p>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>No hay reservas confirmadas para esta fecha.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import CommunicationManager from '@/services/CommunicationManager'
  
  const availableDates = ref([]);
  const reservations = ref([]);
  const countByType = ref({ normal: 0, vip: 0 }); // Añadimos esta variable para los totales por tipo
  const selectedDate = ref('');
  
  // Función para cargar las fechas disponibles de las reservas
  const fetchAvailableDates = async () => {
    try {
      availableDates.value = await CommunicationManager.fetchAvailableDates();
    } catch (error) {
      console.error('Error al obtener las fechas disponibles:', error);
    }
  }
  
  // Función para cargar las reservas confirmadas de una fecha seleccionada
  const fetchConfirmedReservations = async () => {
    if (!selectedDate.value) return;
    try {
      const data = await CommunicationManager.fetchConfirmedReservations(selectedDate.value);
      reservations.value = data.reservations;
      countByType.value = data.countByType; // Asignamos los totales por tipo
    } catch (error) {
      console.error('Error al obtener las reservas confirmadas:', error);
    }
  }
  
  // Cargar fechas disponibles al montar el componente
  onMounted(fetchAvailableDates)
  </script>
  
  <style scoped>
  /* Estilos para el CRUD de reservas */
  .reservation-crud {
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
  
  h2 {
    text-align: center;
  }
  
  select {
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    width: 200px;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  li {
    background-color: #fff;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  }
  </style>
  