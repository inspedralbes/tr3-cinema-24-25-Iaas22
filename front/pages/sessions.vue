<template>
    <div class="sessions-container">
      <button class="back-button" @click="$router.push('/')">
        ⬅️
      </button>
      
      <div class="sessions-card">
        <h2 class="sessions-title">Gestión de Sesiones</h2>
        
        <!-- Mensajes de estado -->
        <div v-if="loading" class="loading-message">
          <p>Cargando sesiones...</p>
        </div>
        
        <div v-if="error" class="error-message">
          <p>❌ {{ error }}</p>
        </div>
        
        <div v-if="success" class="success-message">
          <p>✅ {{ success }}</p>
        </div>
        
        <!-- Tabla de sesiones -->
        <div class="table-container" v-if="sessions.length > 0">
          <table class="sessions-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Película</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Especial</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="session in sessions" :key="session.session_id">
                <td>{{ session.session_id }}</td>
                <td>{{ session.movie ? session.movie.title : 'Desconocida' }}</td>
                <td>{{ formatDate(session.session_date) }}</td>
                <td>{{ formatTime(session.session_time) }}</td>
                <td>
                  <span :class="['badge', session.special_day ? 'special-yes' : 'special-no']">
                    {{ session.special_day ? 'Sí' : 'No' }}
                  </span>
                </td>
                <td>
                  <button 
                    @click="handleDelete(session.session_id)" 
                    class="delete-button"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div v-else-if="!loading" class="empty-message">
          <p>No hay sesiones programadas</p>
        </div>
        
        <!-- Formulario para crear nueva sesión -->
        <div class="form-container">
          <h3 class="form-title">Crear Nueva Sesión</h3>
          
          <form @submit.prevent="handleSubmit" class="session-form">
            <div class="form-group">
              <label for="movie_id">ID de Película:</label>
              <input 
                type="number" 
                v-model="newSession.movie_id" 
                required 
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="session_date">Fecha:</label>
              <input 
                type="date" 
                v-model="newSession.session_date" 
                required 
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="session_time">Hora:</label>
              <input 
                type="time" 
                v-model="newSession.session_time" 
                required 
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="special_day">Día Especial:</label>
              <select 
                v-model="newSession.special_day" 
                required 
                class="form-select"
              >
                <option value="0">No</option>
                <option value="1">Sí</option>
              </select>
            </div>
            
            <button type="submit" class="submit-button">
              Crear Sesión
            </button>
            
            <div v-if="createError" class="error-message">
              <p>❌ {{ createError }}</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import CommunicationManager from '@/services/CommunicationManager';
  
  export default {
    data() {
      return {
        sessions: [],
        loading: false,
        error: null,
        newSession: {
          movie_id: '',
          session_date: '',
          session_time: '',
          special_day: 0,
        },
        success: null,
        createError: null,
      };
    },
  
    async mounted() {
      await this.fetchSessions();
    },
  
    methods: {
      async fetchSessions() {
        this.loading = true;
        try {
          this.sessions = await CommunicationManager.fetchAllSessions();
        } catch (error) {
          this.error = error.message || 'Error al obtener las sesiones';
        } finally {
          this.loading = false;
        }
      },
  
      formatDate(dateString) {
        if (!dateString) return '--/--/----';
        return dateString.split('T')[0].split('-').reverse().join('/');
      },
  
      formatTime(timeString) {
        if (!timeString) return '--:--';
        return timeString.substring(0, 5);
      },
  
      async handleSubmit() {
        this.createError = null;
        this.success = null;
  
        try {
          if (!this.newSession.movie_id || !this.newSession.session_date || !this.newSession.session_time) {
            throw new Error('Por favor complete todos los campos');
          }
  
          const createdSession = await CommunicationManager.createSession(this.newSession);
          
          this.success = `Sesión creada con éxito (ID: ${createdSession.session_id})`;
          this.newSession = { movie_id: '', session_date: '', session_time: '', special_day: 0 };
          this.sessions.push(createdSession);
        } catch (error) {
          this.createError = error.message || 'Error al crear la sesión';
        }
      },
  
      async handleDelete(sessionId) {
        if (!confirm('¿Está seguro de eliminar esta sesión?')) return;
        
        try {
          await CommunicationManager.deleteSession(sessionId);
          this.sessions = this.sessions.filter(s => s.session_id !== sessionId);
          this.success = `Sesión eliminada con éxito (ID: ${sessionId})`;
        } catch (error) {
          this.error = error.message || 'Error al eliminar la sesión';
        }
      },
    },
  };
  </script>
  
  <style scoped>
  /* ✅ Estilos base consistentes con el tema */
  .sessions-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #081e27, #02465d, #011721);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 2rem 1rem;
    color: #e6f1ff;
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
  
  /* ✅ Tarjeta principal */
  .sessions-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    padding: 2.5rem;
    width: 100%;
    max-width: 1000px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .sessions-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
  }
  
  .sessions-title {
    font-size: 2rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 2rem;
    text-align: center;
    letter-spacing: 0.5px;
    position: relative;
  }
  
  .sessions-title::after {
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
  
  /* ✅ Mensajes de estado */
  .loading-message,
  .error-message,
  .success-message {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    text-align: center;
    font-weight: 500;
  }
  
  .loading-message {
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.8);
  }
  
  .error-message {
    background: rgba(231, 76, 60, 0.2);
    color: #e74c3c;
    border: 1px solid rgba(231, 76, 60, 0.3);
  }
  
  .success-message {
    background: rgba(46, 204, 113, 0.2);
    color: #2ecc71;
    border: 1px solid rgba(46, 204, 113, 0.3);
  }
  
  /* ✅ Tabla de sesiones */
  .table-container {
    overflow-x: auto;
    margin-bottom: 2.5rem;
  }
  
  .sessions-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    overflow: hidden;
  }
  
  .sessions-table th,
  .sessions-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .sessions-table th {
    background: rgba(10, 25, 47, 0.7);
    color: #64ffda;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
  }
  
  .sessions-table tr:hover {
    background: rgba(255, 255, 255, 0.08);
  }
  
  /* ✅ Badges para días especiales */
  .badge {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
  }
  
  .special-yes {
    background: rgba(46, 204, 113, 0.2);
    color: #2ecc71;
    border: 1px solid rgba(46, 204, 113, 0.3);
  }
  
  .special-no {
    background: rgba(149, 165, 166, 0.2);
    color: #95a5a6;
    border: 1px solid rgba(149, 165, 166, 0.3);
  }
  
  /* ✅ Botones */
  .delete-button {
    background: rgba(231, 76, 60, 0.2);
    color: #e74c3c;
    border: 1px solid rgba(231, 76, 60, 0.3);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
  }
  
  .delete-button:hover {
    background: rgba(231, 76, 60, 0.3);
    transform: translateY(-2px);
  }
  
  /* ✅ Formulario */
  .form-container {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .form-title {
    font-size: 1.3rem;
    color: #ffffff;
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .session-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
  }
  
  .form-group label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
    font-weight: 500;
  }
  
  .form-input,
  .form-select {
    padding: 0.8rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  
  .form-input:focus,
  .form-select:focus {
    outline: none;
    border-color: #64ffda;
    box-shadow: 0 0 0 3px rgba(100, 255, 218, 0.2);
  }
  
  .submit-button {
    grid-column: 1 / -1;
    background: rgba(100, 255, 218, 0.1);
    color: #64ffda;
    border: 1px solid rgba(100, 255, 218, 0.3);
    padding: 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 1rem;
  }
  
  .submit-button:hover {
    background: rgba(100, 255, 218, 0.2);
    transform: translateY(-2px);
  }
  
  /* ✅ Mensaje cuando no hay sesiones */
  .empty-message {
    text-align: center;
    padding: 2rem;
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px dashed rgba(255, 255, 255, 0.1);
    margin-bottom: 2rem;
  }
  
  /* ✅ Diseño responsive */
  @media (max-width: 768px) {
    .sessions-card {
      padding: 1.5rem;
    }
    
    .sessions-title {
      font-size: 1.7rem;
    }
    
    .session-form {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 480px) {
    .sessions-table th,
    .sessions-table td {
      padding: 0.8rem;
      font-size: 0.9rem;
    }
    
    .back-button {
      width: 40px;
      height: 40px;
      font-size: 1.2rem;
    }
  }
  </style>