<template>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Gestión de Sesiones</h1>
  
      <!-- Botón para crear nueva sesión -->
      <button 
        @click="showCreateModal = true"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4"
      >
        Crear Nueva Sesión
      </button>
        
      <!-- Tabla de sesiones -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Película</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día Especial</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="session in sessions" :key="session.session_id">
              <td class="px-6 py-4 whitespace-nowrap">{{ session.session_id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                {{ session.movie ? session.movie.title : 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(session.session_date) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ formatTime(session.session_time) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{'bg-green-100 text-green-800': session.special_day, 'bg-gray-100 text-gray-800': !session.special_day}" 
                      class="px-2 py-1 rounded-full text-xs">
                  {{ session.special_day ? 'Sí' : 'No' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                <button 
                  @click="confirmDelete(session.session_id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Modal para crear sesión -->
      <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h2 class="text-xl font-bold mb-4">Crear Nueva Sesión</h2>
          
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="movie_id">
              ID de Película
            </label>
            <input 
              v-model="newSession.movie_id" 
              type="number" 
              id="movie_id"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="session_date">
              Fecha
            </label>
            <input 
              v-model="newSession.session_date" 
              type="date" 
              id="session_date"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
          </div>
          
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="session_time">
              Hora
            </label>
            <input 
              v-model="newSession.session_time" 
              type="time" 
              id="session_time"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
          </div>
          
          <div class="mb-4 flex items-center">
            <input 
              v-model="newSession.special_day" 
              type="checkbox" 
              id="special_day"
              class="mr-2"
            >
            <label class="text-gray-700 text-sm font-bold" for="special_day">
              Día Especial
            </label>
          </div>
          
          <div class="flex justify-end space-x-4">
            <button 
              @click="showCreateModal = false"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Cancelar
            </button>
            <button 
              @click="createSession"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Crear
            </button>
          </div>
        </div>
      </div>
  
      <!-- Modal para confirmar eliminación -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h2 class="text-xl font-bold mb-4">Confirmar Eliminación</h2>
          <p class="mb-6">¿Estás seguro de que deseas eliminar esta sesión?</p>
          
          <div class="flex justify-end space-x-4">
            <button 
              @click="showDeleteModal = false"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Cancelar
            </button>
            <button 
              @click="deleteSession"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
  
      <!-- Notificación -->
      <div v-if="notification.show" 
           :class="{'bg-green-500': notification.type === 'success', 'bg-red-500': notification.type === 'error'}"
           class="fixed bottom-4 right-4 text-white px-4 py-2 rounded shadow-lg">
        {{ notification.message }}
      </div>
    </div>
  </template>
  
  <script>
  import CommunicationManager from '@/services/CommunicationManager';
  
  export default {
    data() {
      return {
        sessions: [],
        newSession: {
          movie_id: '',
          session_date: '',
          session_time: '',
          special_day: false
        },
        showCreateModal: false,
        showDeleteModal: false,
        selectedSessionId: null,
        notification: {
          show: false,
          message: '',
          type: 'success'
        }
      };
    },
    async mounted() {
      await this.fetchSessions();
    },
    methods: {
      async fetchSessions() {
        try {
          const response = await CommunicationManager.fetchAllSessions();
          this.sessions = response;
        } catch (error) {
          this.showNotification('Error al cargar las sesiones', 'error');
          console.error(error);
        }
      },
      formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-ES', options);
      },
      formatTime(timeString) {
        return timeString.substring(0, 5); // Mostrar solo HH:MM
      },
      async createSession() {
        try {
          // Validación básica
          if (!this.newSession.movie_id || !this.newSession.session_date || !this.newSession.session_time) {
            throw new Error('Todos los campos son obligatorios');
          }
          
          // Formatear los datos para la API
          const sessionData = {
            movie_id: parseInt(this.newSession.movie_id),
            session_date: this.newSession.session_date,
            session_time: this.newSession.session_time + ':00', // Asegurar formato HH:MM:SS
            special_day: this.newSession.special_day ? 1 : 0
          };
          
          await CommunicationManager.createSession(sessionData);
          this.showCreateModal = false;
          this.resetNewSession();
          await this.fetchSessions();
          this.showNotification('Sesión creada con éxito', 'success');
        } catch (error) {
          this.showNotification(error.message || 'Error al crear la sesión', 'error');
          console.error(error);
        }
      },
      confirmDelete(sessionId) {
        this.selectedSessionId = sessionId;
        this.showDeleteModal = true;
      },
      async deleteSession() {
        try {
          await CommunicationManager.deleteSession(this.selectedSessionId);
          this.showDeleteModal = false;
          await this.fetchSessions();
          this.showNotification('Sesión eliminada con éxito', 'success');
        } catch (error) {
          this.showNotification(error.message || 'Error al eliminar la sesión', 'error');
          console.error(error);
        }
      },
      resetNewSession() {
        this.newSession = {
          movie_id: '',
          session_date: '',
          session_time: '',
          special_day: false
        };
      },
      showNotification(message, type = 'success') {
        this.notification = {
          show: true,
          message,
          type
        };
        setTimeout(() => {
          this.notification.show = false;
        }, 3000);
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos adicionales si son necesarios */
  </style>