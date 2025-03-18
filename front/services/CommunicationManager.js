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
  // ✅ Iniciar sesión
  async login(email, password) {
    const response = await axios.post(`${API_URL}/login`, { email, password });
    const token = response.data.token;
    const user = response.data.user;

    if (typeof window !== 'undefined' && token) {
      localStorage.setItem('auth_token', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    if (typeof window !== 'undefined' && user) {
      localStorage.setItem('user', JSON.stringify(user));
    }

    return response.data;
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

  // ✅ Eliminar una reserva
  async cancelReservation(reservationId) {
    const response = await axios.delete(`${API_URL}/reservations/${reservationId}`);
    return response.data;
  },

  // ✅ Obtener historial de reservas
  async fetchReservations() {
    const response = await axios.get(`${API_URL}/reservations`);
    return response.data;
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
