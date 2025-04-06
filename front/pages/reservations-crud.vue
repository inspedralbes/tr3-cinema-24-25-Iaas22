<template>
    <div class="reservations-container">
      <button class="back-button" @click="$router.push('/')">
        ⬅️
      </button>
      <div class="reservations-card">
        <h2 class="reservations-title">Gestió d' Entrades</h2>
        
        <div class="date-selector">
          <label for="reservation-date">Selecciona una data:</label>
          <select 
            v-model="selectedDate" 
            @change="fetchConfirmedReservations"
            class="date-select"
          >
            <option v-for="date in availableDates" :key="date" :value="date">
              {{ date }}
            </option>
          </select>
        </div>
    
        <div v-if="countByType" class="stats-container">
          <div class="stat-card normal">
            <h3>Butaques Normals</h3>
            <p class="stat-value">{{ countByType.normal }}</p>
          </div>
          <div class="stat-card vip">
            <h3>Butaques VIP</h3>
            <p class="stat-value">{{ countByType.vip }}</p>
          </div>
          <div class="stat-card total">
            <h3>Total Reservas</h3>
            <p class="stat-value">{{ countByType.normal + countByType.vip }}</p>
          </div>
          <div class="stat-card revenue">
            <h3>Total recaudat</h3>
            <p class="stat-value">${{ totalRevenue.toFixed(2) }}</p>
          </div>
        </div>
    
        <div class="reservations-list">
          <h3 class="list-title">Reservas Confirmades <span v-if="selectedDate">para {{ selectedDate }}</span></h3>
          
          <div v-if="reservations.length > 0" class="reservation-items">
            <div v-for="reservation in reservations" :key="reservation.reserva_id" class="reservation-item">
              <div class="reservation-info">
                <div class="reservation-header">
                  <h4>{{ reservation.user.name }}</h4>
                  <span class="user-email">{{ reservation.user.email }}</span>
                </div>
                
                <div class="reservation-details">
                  <div class="detail-group">
                    <span class="detail-label">ID de Sessió:</span>
                    <span class="detail-value">{{ reservation.session.id }}</span>
                  </div>
                  
                  <div class="detail-group">
                    <span class="detail-label">Pel·lícula:</span>
                    <span class="detail-value">{{ reservation.session.movie }}</span>
                  </div>
                  
                  <div class="detail-group">
                    <span class="detail-label">Hora:</span>
                    <span class="detail-value">
                     {{ formatTime(reservation.session.time) }}
                    </span>
                  </div>
                  
                  <div class="detail-group">
                    <span class="detail-label">Butaca:</span>
                    <span class="detail-value">
                      {{ reservation.seat.row }}{{ reservation.seat.number }}
                      <span :class="['seat-type', reservation.seat.type]">{{ reservation.seat.type }}</span>
                    </span>
                  </div>
                  
                  <div class="detail-group">
                    <span class="detail-label">Preu:</span>
                    <span class="detail-value price">${{ reservation.reservation_details.price }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="empty-message">
            <p>No hi han entrades per aquesta pel·lícula.</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import CommunicationManager from '@/services/CommunicationManager'
  
  const availableDates = ref([]);
  const reservations = ref([]);
  const countByType = ref({ normal: 0, vip: 0 });
  const selectedDate = ref('');
  
  const totalRevenue = computed(() => {
    return reservations.value.reduce((total, reservation) => {
      return total + parseFloat(reservation.reservation_details.price || 0);
    }, 0);
  });
  
  const fetchAvailableDates = async () => {
    try {
      availableDates.value = await CommunicationManager.fetchAvailableDates();
      if (availableDates.value.length > 0) {
        selectedDate.value = availableDates.value[0];
        fetchConfirmedReservations();
      }
    } catch (error) {
      console.error('Error al obtener las fechas disponibles:', error);
    }
  }
  
  const fetchConfirmedReservations = async () => {
    if (!selectedDate.value) return;
    try {
      const data = await CommunicationManager.fetchConfirmedReservations(selectedDate.value);
      reservations.value = data.reservations;
      countByType.value = data.countByType;
    } catch (error) {
      console.error('Error al obtener las reservas confirmadas:', error);
    }
  }

  const formatTime = (timeString) => {
    if (!timeString) return '--:--';
   
    return timeString.substring(0, 5);
  }

  
  
  onMounted(fetchAvailableDates)
  </script>
  
  <style scoped>

  .reservations-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #081e27, #02465d, #011721);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 2rem 1rem;
  }
  
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
  
  .reservations-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    padding: 2.5rem;
    width: 100%;
    max-width: 900px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .reservations-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
  }
  
  .reservations-title {
    font-size: 2rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 2rem;
    text-align: center;
    letter-spacing: 0.5px;
    position: relative;
  }
  
  .reservations-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #64ffda, #1e90ff);
    border-radius: 3px;
  }
  
  .list-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #ffffff;
    margin: 2rem 0 1rem;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 0.5rem;
  }
  
  .list-title span {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.7);
  }
  
  .date-selector {
    margin-bottom: 2rem;
  }
  
  .date-selector label {
    display: block;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
    font-weight: 500;
  }
  
  .date-select {
    padding: 0.9rem 1.2rem;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #ffffff;
    width: 100%;
    max-width: 300px;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  
  .date-select:focus {
    outline: none;
    border-color: #64ffda;
    box-shadow: 0 0 0 3px rgba(100, 255, 218, 0.2);
  }
  
  .stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .stat-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid transparent;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  }
  
  .stat-card h3 {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
  }
  
  .stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
  }
  
  .stat-card.normal {
    border-color: rgba(74, 144, 226, 0.3);
  }
  
  .stat-card.normal .stat-value {
    color: #4a90e2;
  }
  
  .stat-card.vip {
    border-color: rgba(155, 89, 182, 0.3);
  }
  
  .stat-card.vip .stat-value {
    color: #9b59b6;
  }
  
  .stat-card.total {
    border-color: rgba(100, 255, 218, 0.3);
  }
  
  .stat-card.total .stat-value {
    color: #64ffda;
  }
  
  .stat-card.revenue {
    border-color: rgba(46, 204, 113, 0.3);
  }
  
  .stat-card.revenue .stat-value {
    color: #2ecc71;
  }
  
  .reservations-list {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 12px;
  }
  
  .reservation-items {
    display: grid;
    gap: 1rem;
  }
  
  .reservation-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
  }
  
  .reservation-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  }
  
  .reservation-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
  }
  
  .reservation-header h4 {
    font-size: 1.2rem;
    color: #ffffff;
    margin: 0;
  }
  
  .user-email {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(255, 255, 255, 0.1);
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
  }
  
  .reservation-details {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
  }
  
  .detail-group {
    display: flex;
    flex-direction: column;
  }
  
  .detail-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.2rem;
  }
  
  .detail-value {
    font-size: 1rem;
    color: #ffffff;
    font-weight: 500;
  }
  
  .price {
    color: #64ffda;
    font-weight: 600;
  }
  
  .seat-type {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-left: 0.5rem;
    text-transform: uppercase;
  }
  
  .seat-type.normal {
    background: rgba(74, 144, 226, 0.2);
    color: #4a90e2;
    border: 1px solid rgba(74, 144, 226, 0.3);
  }
  
  .seat-type.vip {
    background: rgba(155, 89, 182, 0.2);
    color: #9b59b6;
    border: 1px solid rgba(155, 89, 182, 0.3);
  }
  
  .empty-message {
    text-align: center;
    padding: 2rem;
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px dashed rgba(255, 255, 255, 0.1);
  }
  
  @media (max-width: 768px) {
    .stats-container {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .reservation-details {
      grid-template-columns: 1fr;
    }
    
    .date-select {
      max-width: 100%;
    }
  }
  
  @media (max-width: 480px) {
    .reservations-card {
      padding: 1.5rem;
    }
    
    .reservations-title {
      font-size: 1.7rem;
      margin-bottom: 1.5rem;
    }
    
    .reservation-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
    }
    
    .stats-container {
      grid-template-columns: 1fr;
    }
  }
  </style>