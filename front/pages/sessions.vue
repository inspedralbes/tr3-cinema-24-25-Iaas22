<template>
    <div>
      <h1>Sesiones</h1>
  
      <!-- Cargando sesiones -->
      <div v-if="loading" class="loading">
        <p>Cargando sesiones...</p>
      </div>
  
      <!-- Error al obtener las sesiones -->
      <div v-if="error" class="error">
        <p>Hubo un error al obtener las sesiones: {{ error }}</p>
      </div>
  
      <!-- Mostrar tabla de sesiones -->
      <div v-if="sessions.length > 0">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Pelicula</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Especial</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="session in sessions" :key="session.session_id">
              <td>{{ session.session_id }}</td>
              <td>{{ session.movie ? session.movie.title : 'Desconocida' }}</td> <!-- Verificación de movie -->
              <td>{{ session.session_date.split('T')[0] }}</td> <!-- Formato de fecha: YYYY-MM-DD -->
              <td>{{ session.session_time }}</td>
              <td>{{ session.special_day ? 'Sí' : 'No' }}</td> <!-- Condición para "Sí" o "No" -->
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Formulario para crear una nueva sesión -->
      <h2>Crear nueva sesión</h2>
      <form @submit.prevent="handleSubmit">
        <div>
          <label for="movie_id">Película:</label>
          <input type="number" v-model="newSession.movie_id" required />
        </div>
  
        <div>
          <label for="session_date">Fecha:</label>
          <input type="date" v-model="newSession.session_date" required />
        </div>
  
        <div>
          <label for="session_time">Hora:</label>
          <input type="time" v-model="newSession.session_time" required />
        </div>
  
        <div>
          <label for="special_day">¿Día especial?</label>
          <select v-model="newSession.special_day" required>
            <option value="0">No</option>
            <option value="1">Sí</option>
          </select>
        </div>
  
        <button type="submit">Crear sesión</button>
      </form>
  
      <!-- Mensajes de éxito y error -->
      <div v-if="success" class="success">
        <p>{{ success }}</p>
      </div>
  
      <div v-if="createError" class="error">
        <p>{{ createError }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import CommunicationManager from '@/services/CommunicationManager'; // Asegúrate de que la ruta sea correcta
  
  export default {
    data() {
      return {
        sessions: [], // Almacena las sesiones obtenidas
        loading: false, // Estado para mostrar "cargando"
        error: null, // Estado para manejar errores
        newSession: { // Datos del formulario para crear una nueva sesión
          movie_id: '',
          session_date: '',
          session_time: '',
          special_day: 0, // '0' por defecto para "No"
        },
        success: null, // Mensaje de éxito al crear la sesión
        createError: null, // Error al crear la sesión
      };
    },
  
    async mounted() {
      this.loading = true;
      try {
        // Llamada a la función fetchAllSessions para obtener las sesiones
        const fetchedSessions = await CommunicationManager.fetchAllSessions();
        this.sessions = fetchedSessions;
      } catch (error) {
        this.error = error.message || 'Error al obtener las sesiones';
      } finally {
        this.loading = false;
      }
    },
  
    methods: {
      async handleSubmit() {
        this.createError = null;
        this.success = null;
  
        try {
          // Validación de los datos antes de enviarlos
          if (!this.newSession.movie_id || !this.newSession.session_date || !this.newSession.session_time) {
            this.createError = 'Por favor, complete todos los campos.';
            return;
          }
  
          // Llamada a la función de CommunicationManager para crear una nueva sesión
          const createdSession = await CommunicationManager.createSession(this.newSession);
  
          // Si la sesión se crea con éxito, mostramos el mensaje y limpiamos el formulario
          this.success = `Sesión creada con éxito. ID: ${createdSession.session_id}`;
          this.newSession = { movie_id: '', session_date: '', session_time: '', special_day: 0 }; // Limpiar el formulario
          this.sessions.push(createdSession); // Agregar la nueva sesión a la lista
        } catch (error) {
          this.createError = error.message || 'Hubo un error al crear la sesión';
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .loading {
    text-align: center;
  }
  
  .error {
    color: red;
    text-align: center;
  }
  
  .success {
    color: green;
    text-align: center;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
  }
  
  th {
    background-color: #f4f4f4;
  }
  
  tr:nth-child(even) {
    background-color: #f9f9f9;
  }
  
  form {
    margin-top: 20px;
  }
  
  div {
    margin-bottom: 10px;
  }
  </style>
  