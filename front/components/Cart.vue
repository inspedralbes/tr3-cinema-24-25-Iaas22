<template>
    <div :class="['sidebar', cartOpen ? 'translate-x-0' : 'translate-x-full']">
      <div class="sidebar-content">
        <button @click="$emit('toggle-cart')" class="close-btn">❌</button>
        <h3 class="text-lg font-semibold mb-2">🛒 Cesta de reservas:</h3>
        <ul class="space-y-2">
          <li 
            v-for="reservation in reservations" 
            :key="reservation.seat_id" 
            class="flex justify-between items-center border-b py-2"
          >
            <div>
              🎬 <strong>{{ reservation.movie }}</strong>
              <br>
              📅 {{ formatDate(reservation.date) }} - 🕒 {{ reservation.time }}
              <br>
              🎯 Butaca: {{ reservation.row }}{{ reservation.seat_num }} ({{ reservation.type }})
            </div>
            <div class="flex items-center space-x-2">
              <!-- Botón cancelar -->
              <button 
                @click="$emit('cancel-reservation', reservation.seat_id)"
                class="text-red-500 hover:text-red-700 font-bold"
              >
                ❌
              </button>
              <!-- Cuadro de selección -->
              <div 
                class="select-box" 
                @click="toggleSelection(reservation.seat_id)"
              >
                <span v-if="selectedReservations.includes(reservation.seat_id)">✔️</span>
              </div>
            </div>
          </li>
        </ul>
        <!-- Botón comprar al final -->
        <button 
          @click="$emit('purchase', selectedReservations)" 
          class="purchase-btn"
        >
          🛒 Comprar
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  defineProps({
    reservations: {
      type: Array,
      required: true
    },
    cartOpen: {
      type: Boolean,
      required: true
    }
  });
  
  defineEmits(['toggle-cart', 'cancel-reservation', 'purchase']);
  
  // Estado para las reservas seleccionadas
  const selectedReservations = ref([]);
  
  const toggleSelection = (seatId) => {
    if (selectedReservations.value.includes(seatId)) {
      // Si ya está seleccionado, lo quitamos
      selectedReservations.value = selectedReservations.value.filter(id => id !== seatId);
    } else {
      // Si no está seleccionado, lo añadimos
      selectedReservations.value.push(seatId);
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
  </script>
  
  <style scoped>
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
  
  /* Cuadro de selección */
  .select-box {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #10b981;
    border-radius: 4px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.2s ease;
    background-color: #ffffff;
  }
  
  .select-box:hover {
    background-color: #d1fae5;
  }
  
  .select-box span {
    font-size: 1rem;
    color: #10b981;
  }
  
  /* Si está seleccionado */
  .select-box.selected {
    background-color: #10b981;
    color: white;
  }
  
  /* Botón de compra */
  .purchase-btn {
    width: 120px;
    background-color: #3b82f6;
    color: white;
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: bold;
    margin-top: 0;
    cursor: pointer;
    transition: background 0.2s ease;
    margin-bottom: 50px;
  }
  
  .purchase-btn:hover {
    background-color: #2563eb;
  }
  </style>
  