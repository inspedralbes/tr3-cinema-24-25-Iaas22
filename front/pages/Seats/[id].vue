<template>
    <div>
      <h1 class="text-3xl font-bold mb-4">🎟️ Selecciona tu butaca</h1>
  
      <div v-if="seats.length">
        <div 
          v-for="seat in seats" 
          :key="seat.seat_id"
          class="inline-block m-2 p-4 border rounded-lg shadow-md"
          :class="{
            'bg-green-400': seat.status === 'disponible',
            'bg-red-400': seat.status === 'reservado'
          }"
        >
          Fila {{ seat.row }} - Asiento {{ seat.seat_num }} 
        </div>
      </div>
  
      <div v-else class="text-center text-gray-500">
        No hay butacas disponibles para esta sesión.
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'nuxt/app'
  import axios from 'axios'
  
  const seats = ref([])
  const route = useRoute()
  
  const fetchSeats = async () => {
    try {
      console.log(`🎯 Fetching seats for session: ${route.params.id}`)
      const response = await axios.get(`${useRuntimeConfig().public.apiBase}/Seats/${route.params.id}`)
      seats.value = response.data
      console.log('🎯 Seats data:', seats.value)
    } catch (error) {
      console.error('❌ Error al obtener las butacas:', error)
    }
  }
  
  onMounted(fetchSeats)
  </script>
  
  <style scoped>
  h1 {
    color: #1a202c;
  }
  </style>
  