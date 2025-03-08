<template>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Butacas</h1>
      <div v-if="loading" class="text-center">Cargando...</div>
      <div v-else class="grid gap-4">
        <div v-for="(row, index) in seatRows" :key="index" class="flex gap-2">
          <div v-for="seat in row" :key="seat.id" 
               :class="['p-4 border rounded text-center cursor-pointer', seat.status === 'reservado' ? 'bg-red-500 text-white' : 'bg-green-500 text-white']">
            {{ seat.code }}
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const seats = ref([]);
  const loading = ref(true);
  
  onMounted(async () => {
    try {
      const response = await fetch('http://localhost/api/seats');
      seats.value = await response.json();
    } catch (error) {
      console.error('Error al obtener las butacas:', error);
    } finally {
      loading.value = false;
    }
  });
  
  const seatRows = computed(() => {
    const rows = [];
    for (let i = 0; i < seats.value.length; i += 10) {
      rows.push(seats.value.slice(i, i + 10));
    }
    return rows;
  });
  </script>
  
  <style>
  .container {
    max-width: 600px;
  }
  </style>
  