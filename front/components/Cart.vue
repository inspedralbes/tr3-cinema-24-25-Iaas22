<template>
  <div :class="['sidebar', cartOpen ? 'translate-x-0' : 'translate-x-full']">
    <div class="sidebar-content">
      <div class="sidebar-header">
        <button @click="$emit('toggle-cart')" class="close-btn">
          <img src="/images/cancelar.svg" alt="Cerrar" class="icon-button" />
        </button>
        <h3 class="sidebar-title">MIS ENTRADAS </h3>
      </div>
      
      <div v-if="reservations.length === 0" class="empty-cart">
        <img src="/images/cart.jpg" alt="Carrito vacío" class="empty-icon" />
        <p>No hay entradas confirmadas. </p>
      </div>
      
      <ul v-else class="reservations-list">
        <li 
          v-for="reservation in reservations" 
          :key="reservation.seat_id" 
          class="reservation-item"
        >
          <div class="reservation-details">
            <div class="movie-title">
              <img src="/images/login.png" alt="Película" class="detail-icon" />
              <span>{{ reservation.movie }}</span>
            </div>
            <div class="session-info">
              <img src="/images/login.png" alt="Fecha" class="detail-icon" />
              <span>{{ formatDate(reservation.date) }}</span>
              <img src="/images/login.png" alt="Hora" class="detail-icon" />
              <span>{{ reservation.time }}</span>
            </div>
            <div class="seat-info">
              <img src="/images/login.png" alt="Butaca" class="detail-icon" />
              <span>{{ reservation.row }}{{ reservation.seat_num }} ({{ reservation.type }})</span>
            </div>
          </div>
          
          <button 
            @click="$emit('cancel-reservation', reservation.seat_id)"
            class="cancel-btn"
          >
            <img src="/images/delete.svg" alt="Eliminar" class="icon-button" />
          </button>
        </li>
      </ul>
      
      <div v-if="reservations.length > 0" class="sidebar-footer">
      
      </div>
    </div>
  </div>
</template>

<script setup>
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

defineEmits(['toggle-cart', 'cancel-reservation']);

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
/* ✅ Estilos base */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.sidebar {
  position: fixed;
  top: 0;
  right: 0;
  width: 350px;
  height: 100vh;
  background: rgba(10, 25, 47, 0.95);
  backdrop-filter: blur(10px);
  border-left: 1px solid rgba(100, 255, 218, 0.2);
  padding: 1.5rem;
  overflow-y: auto;
  box-shadow: -8px 0 24px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease-in-out;
  z-index: 1000;
}

.translate-x-full {
  transform: translateX(100%);
}

.translate-x-0 {
  transform: translateX(0);
}

.sidebar-content {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #64ffda;
  margin: 0;
}

.close-btn {
  background: rgba(239, 68, 68, 0.2);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: 8px;
  padding: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: rgba(239, 68, 68, 0.3);
  transform: rotate(90deg);
}

.icon-button {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

.empty-cart {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
  color: rgba(255, 255, 255, 0.6);
  text-align: center;
  padding: 2rem 0;
}

.empty-icon {
  width: 80px;
  height: 80px;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.reservations-list {
  flex-grow: 1;
  list-style: none;
  padding: 0;
  margin: 0;
  overflow-y: auto;
}

.reservation-item {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.reservation-item:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateY(-2px);
}

.reservation-details {
  flex-grow: 1;
}

.movie-title {
  font-weight: 600;
  color: #e6f1ff;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.session-info, .seat-info {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.3rem;
}

.detail-icon {
  width: 16px;
  height: 16px;
  opacity: 0.7;
}

.cancel-btn {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  border-radius: 6px;
  padding: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-left: 1rem;
}

.cancel-btn:hover {
  background: rgba(239, 68, 68, 0.2);
  transform: scale(1.1);
}

.sidebar-footer {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.checkout-btn {
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
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.checkout-btn:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* ✅ Responsive */
@media (max-width: 480px) {
  .sidebar {
    width: 100%;
    max-width: 320px;
  }
  
  .sidebar-title {
    font-size: 1.3rem;
  }
  
  .reservation-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .cancel-btn {
    align-self: flex-end;
  }
}
</style>