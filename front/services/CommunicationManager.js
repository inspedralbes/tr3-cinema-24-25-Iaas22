import axios from 'axios';

const API_URL = 'http://localhost:8000/api';
// const API_URL = "http://cineyaback.daw.inspedralbes.cat/api";

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
          localStorage.setItem('auth_token', token);
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }

      if (typeof window !== 'undefined' && user) {
          localStorage.setItem('user', JSON.stringify(user));
          localStorage.setItem('user_id', user.id); 
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

  logout() {
    if (typeof window !== 'undefined') {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      delete axios.defaults.headers.common['Authorization'];
    }
  },

  async fetchMovies() {
    const response = await axios.get(`${API_URL}/movies`);
    return response.data;
  },

  async fetchMovieDetails(movieId) {
    const response = await axios.get(`${API_URL}/movies/${movieId}`);
    return response.data;
  },

  async createMovie(movieData) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para crear películas.');
    }
    const response = await axios.post(`${API_URL}/movies`, movieData);
    return response.data;
  },

  async updateMovie(movieId, movieData) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para actualizar películas.');
    }
    const response = await axios.put(`${API_URL}/movies/${movieId}`, movieData);
    return response.data;
  },

  async deleteMovie(movieId) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user?.role !== 'admin') {
      throw new Error('No tienes permisos para eliminar películas.');
    }
    const response = await axios.delete(`${API_URL}/movies/${movieId}`);
    return response.data;
  },

 async fetchSessionsByMovie(movieId) {
  const response = await axios.get(`${API_URL}/sessions/movie/${movieId}`);
  console.log('🔍 Sesiones obtenidas:', response.data); // 👀 Para depurar
  return response.data;
}
,

  async fetchSeatsBySession(sessionId) {
    const response = await axios.get(`${API_URL}/seats/session/${sessionId}`);
    return response.data;
  },

async reserveSeat(seatId, sessionId) {
  if (!(await this.checkAuth())) {
    return { status: 'UNAUTHENTICATED' };
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

async buySeatManually(seatId, movieId, name, lastName, email) {
  try {
    const seatResponse = await axios.get(`${API_URL}/seats/${seatId}`);
    const seat = seatResponse.data;

    const movieResponse = await axios.get(`${API_URL}/movies/${movieId}`);
    const movie = movieResponse.data;

    const user = JSON.parse(localStorage.getItem('user'));
    if (!user || !user.id) {
      throw new Error('Usuario no autenticado');
    }

    const purchaseData = {
      seat_id: seatId,
      movie_id: movieId,
      user_id: user.id, 
      name: name, 
      apellidos: lastName, 
      email: email, 
      precio: seat.price, 
    };

    const response = await axios.post(`${API_URL}/buy-seat`, purchaseData);

    console.log('✅ Compra realizada con éxito:', response.data);
    return response.data;
  } catch (error) {
    console.error('❌ Error al comprar la reserva:', error);
    throw new Error(error.response?.data?.message || 'Error al comprar la reserva');
  }
},
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

  async getSeatPriceById(seatId) {
    const response = await axios.get(`${API_URL}/seat/price/${seatId}`);
    return response.data;
  },

  async getTotalPriceByUser(userId) {
    const response = await axios.get(`${API_URL}/compras/total/${userId}`);
    return response.data;
  },

  async getSessionDateByMovieId(movieId) {
    const response = await axios.get(`${API_URL}/sessions/date/${movieId}`);
    return response.data;
  },

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
        compra_dia: new Date().toISOString().split('T')[0], 
        compra_hora: new Date().toTimeString().split(' ')[0], 
        status: 'confirmada'
      };
  
      console.log('📤 Enviando solicitud:', requestBody);
  
      const token = localStorage.getItem('auth_token');
      if (!token) throw new Error('⚠️ No hay token disponible');
      
      const response = await axios.post(`${API_URL}/confirmar-reserva`, requestBody, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` 
        },
      });
      
      console.log('✅ Reserva confirmada:', response.data);
      return response.data;
  
    } catch (error) {
      console.error('❌ Error al confirmar la reserva:', error);
      throw new Error(error.response?.data?.message || 'Error al confirmar la reserva');
    }
  },  
 
   async fetchAvailableDates() {
    try {
      const response = await axios.get(`${API_URL}/admin/reservations/available-dates`);
      return response.data.dates; 
    } catch (error) {
      console.error('Error al obtener las fechas disponibles:', error);
      throw new Error(error.response?.data?.message || 'Error al obtener las fechas disponibles');
    }
  },

  async fetchConfirmedReservations(date) {
    try {
      const response = await axios.get(`${API_URL}/admin/reservations/confirmed`, {
          params: {
              date: date,  
          }
      });
  
      if (!response.data.reservations || !response.data.countByType) {
        throw new Error('Los datos de las reservas no están disponibles');
      }
  
      return {
        reservations: response.data.reservations,
        countByType: response.data.countByType 
      };
    } catch (error) {
      console.error('❌ Error al obtener las reservas confirmadas:', error);
      const errorMessage = error.response?.data?.message || error.message || 'Error al obtener las reservas confirmadas';
      throw new Error(errorMessage);
    }
  },
  

  async fetchDatesAndReservations() {
    try {
      const availableDates = await this.fetchAvailableDates();
      if (availableDates && availableDates.length > 0) {
        const date = availableDates[0]; 
        console.log('Fecha seleccionada:', date);

        const confirmedReservations = await this.fetchConfirmedReservations(date);
        console.log('Reservas confirmadas:', confirmedReservations);
        return confirmedReservations; 
      } else {
        console.error('No hay fechas disponibles');
        throw new Error('No hay fechas disponibles');
      }
    } catch (error) {
      console.error('Error al obtener las fechas y reservas:', error);
      throw new Error(error.message);
    }
  },
 async fetchAllSessions() {
  try {
    const response = await axios.get(`${API_URL}/sessions`);
    console.log('🔍 Todas las sesiones obtenidas:', response.data); 
    return response.data;
  } catch (error) {
    console.error('❌ Error al obtener las sesiones:', error);
    throw new Error(error.response?.data?.message || 'Error al obtener las sesiones');
  }
},
async createSession(sessionData) {
  if (!sessionData.movie_id || !sessionData.session_date || !sessionData.session_time) {
    throw new Error('Los datos de la sesión están incompletos.');
  }

  try {
    const response = await axios.post(`${API_URL}/sessions`, sessionData);
    
    if (response && response.data) {
      return response.data;  
    } else {
      throw new Error('No se recibió una respuesta válida del servidor');
    }
  } catch (error) {
    console.error('Error al crear la sesión:', error);

    if (error.response && error.response.data) {
      throw new Error(error.response.data.message || 'Error desconocido al crear la sesión');
    } else {
      throw new Error('Error al crear la sesión: ' + (error.message || 'Desconocido'));
    }
  }
},
async deleteSession(sessionId) {
  try {
    const response = await axios.delete(`${API_URL}/sessions/${sessionId}`);
    console.log('✅ Sesión eliminada con éxito:', response.data);
    return response.data;
  } catch (error) {
    console.error('❌ Error al eliminar la sesión:', error.response?.data?.message || error.message);
    throw new Error(error.response?.data?.message || 'Error al eliminar la sesión');
  }
},


  getApiBase() {
    return API_URL;
  },

  getAuthHeaders() {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('auth_token');
      return token ? { Authorization: `Bearer ${token}` } : {};
    }
    return {};
  },
};

export default CommunicationManager;
