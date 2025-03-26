import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

// ✅ Configurar axios para usar el token si está disponible
if (typeof window !== 'undefined') {
  const token = localStorage.getItem('auth_token');
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }
}

const CommunicationManager = {
 
async login(email, password) {
  try {
      const response = await axios.post(`${API_URL}/login`, { email, password });
      const token = response.data.token;
      const user = response.data.user;

      if (typeof window !== 'undefined' && token) {
          // Guardar el token en localStorage para futuras solicitudes
          localStorage.setItem('auth_token', token);
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }

      if (typeof window !== 'undefined' && user) {
          // ✅ Guardar datos importantes en localStorage
          localStorage.setItem('user', JSON.stringify(user));
          localStorage.setItem('user_id', user.id); // 🏆 Guardar el user_id directamente
          localStorage.setItem('user_name', user.name);
          localStorage.setItem('user_email', user.email);
      }

      console.log('✅ Login exitoso. Usuario almacenado:', user);

      return response.data;
  } catch (error) {
      console.error('❌ Error al iniciar sesión:', error.response?.data?.message || error.message);
      throw new Error(error.response?.data?.message || 'Error al iniciar sesión');
  }
},


  // ✅ Registrar usuario
  async register(userData) {
    try {
      const response = await axios.post(`${API_URL}/register`, userData);
      const token = response.data.token;

      if (typeof window !== 'undefined' && token) {
        localStorage.setItem('auth_token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }

      if (typeof window !== 'undefined') {
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }

      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Error al registrar usuario');
    }
  },

  // ✅ Verificar autenticación
  async checkAuth() {
    if (typeof window === 'undefined') return false;

    const token = localStorage.getItem('auth_token');
    if (!token) return false;

    try {
      const response = await axios.get(`${API_URL}/auth/check`);
      return response.data.status === 'authenticated';
    } catch (error) {
      console.error('❌ Error de autenticación:', error.message);
      this.logout();
      return false;
    }
  },

  // ✅ Cierre de sesión
  logout() {
    if (typeof window !== 'undefined') {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      delete axios.defaults.headers.common['Authorization'];
    }
  },

  // ✅ Obtener listado de películas
  async fetchMovies() {
    const response = await axios.get(`${API_URL}/movies`);
    return response.data;
  },

  // ✅ Obtener detalles de una película
  async fetchMovieDetails(movieId) {
    const response = await axios.get(`${API_URL}/movies/${movieId}`);
    return response.data;
  },

  // ✅ Crear película (solo admin)
  async createMovie(movieData) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para crear películas.');
    }
    const response = await axios.post(`${API_URL}/movies`, movieData);
    return response.data;
  },

  // ✅ Actualizar película (solo admin)
  async updateMovie(movieId, movieData) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para actualizar películas.');
    }
    const response = await axios.put(`${API_URL}/movies/${movieId}`, movieData);
    return response.data;
  },

  // ✅ Eliminar película (solo admin)
  async deleteMovie(movieId) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para eliminar películas.');
    }
    const response = await axios.delete(`${API_URL}/movies/${movieId}`);
    return response.data;
  },

  // ✅ Obtener sesiones por ID de película
  async fetchSessionsByMovie(movieId) {
    const response = await axios.get(`${API_URL}/sessions/movie/${movieId}`);
    return response.data;
  },

  // ✅ Obtener butacas por ID de sesión
  async fetchSeatsBySession(sessionId) {
    const response = await axios.get(`${API_URL}/seats/session/${sessionId}`);
    return response.data;
  },

  // ✅ Reservar asiento (solo si hay token)
  async reserveSeat(seatId, sessionId) {
    if (!(await this.checkAuth())) {
      alert('⚠️ Debes iniciar sesión para reservar una butaca.');
      window.location.href = '/login';
      return;
    }

    try {
      const response = await axios.post(`${API_URL}/reserve-seat`, {
        seat_id: seatId,
        session_id: sessionId,
      });

      return response.data;
    } catch (error) {
      console.error('❌ Error al reservar la butaca:', error);
      throw new Error(error.response?.data?.message || 'Error al reservar la butaca');
    }
  },
// Función para comprar un asiento manualmente (nombre, apellidos y email introducidos por el usuario)
async buySeatManually(seatId, movieId, name, lastName, email) {
  try {
    // Obtener los detalles del asiento con seatId
    const seatResponse = await axios.get(`${API_URL}/seats/${seatId}`);
    const seat = seatResponse.data;

    // Obtener los detalles de la película con movieId
    const movieResponse = await axios.get(`${API_URL}/movies/${movieId}`);
    const movie = movieResponse.data;

    // Obtener el usuario autenticado desde localStorage
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user || !user.id) {
      throw new Error('Usuario no autenticado');
    }

    // Crear el objeto con la información para la compra
    const purchaseData = {
      seat_id: seatId,
      movie_id: movieId,
      user_id: user.id, // Obtener el user_id desde localStorage
      name: name, // El nombre que el usuario introduce manualmente
      apellidos: lastName, // Los apellidos que el usuario introduce manualmente
      email: email, // El email que el usuario introduce manualmente
      precio: seat.price, // El precio del asiento
    };

    // Realizar la compra llamando al backend
    const response = await axios.post(`${API_URL}/buy-seat`, purchaseData);

    console.log('✅ Compra realizada con éxito:', response.data);
    return response.data;
  } catch (error) {
    console.error('❌ Error al comprar la reserva:', error);
    throw new Error(error.response?.data?.message || 'Error al comprar la reserva');
  }
},
  // ✅ Reservar múltiples asientos
  async reserveSeats(seatIds, sessionId) {
    if (!(await this.checkAuth())) {
      alert('⚠️ Debes iniciar sesión para reservar butacas.');
      window.location.href = '/login';
      return;
    }

    try {
      const response = await axios.post(`${API_URL}/reservar-butacas`, {
        seat_ids: seatIds,
        session_id: sessionId,
      });

      return response.data;
    } catch (error) {
      console.error('❌ Error al reservar las butacas:', error);
      throw new Error(error.response?.data?.message || 'Error al reservar las butacas');
    }
  },

  async cancelReservation(seatId) {
    try {
      const response = await axios.delete(`${API_URL}/cancel-reservation/${seatId}`);
      return response.data;
    } catch (error) {
      console.error('Error al cancelar la reserva:', error);
      throw error;
    }
  },   

  async cancelReservations(seatIds)  {
    try {
      const response = await axios.delete(`${API_URL}/reservas/cancelar-multiples`, {
        data: seatIds,
        headers: {
          'Content-Type': 'application/json'
        }
      });
      return response.data;
    } catch (error) {
      console.error('❌ Error al cancelar las reservas:', error);
      throw error;
    }
  },
  
   
  // ✅ Obtener historial de reservas
  async fetchReservations() {
    const response = await axios.get(`${API_URL}/reservations`);
    return response.data;
  },
  
  async fetchReservationsByUser() {
    try {
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user || !user.id) {
        throw new Error('No hay usuario autenticado');
      }
  
      const response = await axios.get(`${API_URL}/reservations/user/${user.id}`);
      return response.data;
    } catch (error) {
      console.error('Error al obtener las reservas:', error);
      throw error;
    }
  },  

  // ✅ Obtener precio de butaca por ID
  async getSeatPriceById(seatId) {
    const response = await axios.get(`${API_URL}/seat/price/${seatId}`);
    return response.data;
  },

  // ✅ Obtener total de compra por usuario
  async getTotalPriceByUser(userId) {
    const response = await axios.get(`${API_URL}/compras/total/${userId}`);
    return response.data;
  },

  // ✅ Obtener fecha de sesión por ID de película
  async getSessionDateByMovieId(movieId) {
    const response = await axios.get(`${API_URL}/sessions/date/${movieId}`);
    return response.data;
  },

  // ✅ Completar reserva
  async completeReservation(reservationData) {
    const response = await axios.post(`${API_URL}/reservar-completa`, reservationData);
    return response.data;
  },

  async confirmReservation(sessionId, reservaId, seatIds, name, lastName, email) {
    try {
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user || !user.id) {
        throw new Error('Usuario no autenticado');
      }
  
      if (!name || !lastName || !email || !sessionId || !seatIds.length) {
        console.error('🚨 Faltan datos en la solicitud:', { name, lastName, email, sessionId, seatIds });
        throw new Error('Faltan datos para confirmar la reserva');
      }
  
      const requestBody = {
        session_id: Number(sessionId),
        reserva_id: Number(reservaId),
        seat_ids: seatIds.map(id => Number(id)),
        user_id: user.id, 
        name,
        apellidos: lastName,
        email,
        precio: seatIds.length * 6,
        compra_dia: new Date().toISOString().split('T')[0], // YYYY-MM-DD
        compra_hora: new Date().toTimeString().split(' ')[0], // HH:MM:SS
        status: 'confirmada'
      };
  
      console.log('📤 Enviando solicitud:', requestBody);
  
      // 🔥 Toma el token correcto del localStorage
      const token = localStorage.getItem('auth_token');
      if (!token) throw new Error('⚠️ No hay token disponible');
      
      const response = await axios.post(`${API_URL}/confirmar-reserva`, requestBody, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` // 👈 Usa solo `auth_token`
        },
      });
      
      console.log('✅ Reserva confirmada:', response.data);
      return response.data;
  
    } catch (error) {
      console.error('❌ Error al confirmar la reserva:', error);
      throw new Error(error.response?.data?.message || 'Error al confirmar la reserva');
    }
  },  
 
   // ✅ Obtener las fechas disponibles
   async fetchAvailableDates() {
    try {
      const response = await axios.get(`${API_URL}/admin/reservations/available-dates`);
      return response.data.dates; // Devuelve el arreglo de fechas disponibles
    } catch (error) {
      console.error('Error al obtener las fechas disponibles:', error);
      throw new Error(error.response?.data?.message || 'Error al obtener las fechas disponibles');
    }
  },

  async fetchConfirmedReservations(date) {
    try {
      const response = await axios.get(`${API_URL}/admin/reservations/confirmed`, {
          params: {
              date: date,  // Pasamos la fecha como parámetro en la URL
          }
      });
  
      // Asegurarnos de que la respuesta contiene los datos que necesitamos
      if (!response.data.reservations || !response.data.countByType) {
        throw new Error('Los datos de las reservas no están disponibles');
      }
  
      return {
        reservations: response.data.reservations,
        countByType: response.data.countByType // Añadimos esta parte
      };
    } catch (error) {
      console.error('❌ Error al obtener las reservas confirmadas:', error);
      // Mostrar el error de una forma más clara
      const errorMessage = error.response?.data?.message || error.message || 'Error al obtener las reservas confirmadas';
      throw new Error(errorMessage);
    }
  },
  

  // Lógica combinada para obtener fechas y luego reservas confirmadas
  async fetchDatesAndReservations() {
    try {
      // Paso 1: Obtener fechas disponibles
      const availableDates = await this.fetchAvailableDates();
      if (availableDates && availableDates.length > 0) {
        const date = availableDates[0]; // Selecciona la primera fecha disponible
        console.log('Fecha seleccionada:', date);

        // Paso 2: Obtener reservas confirmadas para esa fecha
        const confirmedReservations = await this.fetchConfirmedReservations(date);
        console.log('Reservas confirmadas:', confirmedReservations);
        return confirmedReservations; // Devuelve las reservas confirmadas
      } else {
        console.error('No hay fechas disponibles');
        throw new Error('No hay fechas disponibles');
      }
    } catch (error) {
      console.error('Error al obtener las fechas y reservas:', error);
      throw new Error(error.message);
    }
  },


  // ✅ Obtener configuración base de la API
  getApiBase() {
    return API_URL;
  },

  // ✅ Obtener headers de autorización
  getAuthHeaders() {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('auth_token');
      return token ? { Authorization: `Bearer ${token}` } : {};
    }
    return {};
  },
};

export default CommunicationManager;
