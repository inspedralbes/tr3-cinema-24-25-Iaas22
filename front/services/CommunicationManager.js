import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

// ✅ Configurar axios para usar automáticamente el token si existe (solo en el navegador)
if (typeof window !== 'undefined') {
  const token = localStorage.getItem('auth_token');
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }
}

const CommunicationManager = {
  // ✅ Guardar token después de login (solo en el navegador)
  async login(email, password) {
    const response = await axios.post(`${API_URL}/login`, { email, password });
    const token = response.data.token;
    if (typeof window !== 'undefined' && token) {
      localStorage.setItem('auth_token', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
    return response.data;
  },

  // ✅ Registro de usuario
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

  // ✅ Hacer logout (solo si es navegador)
  logout() {
    if (typeof window !== 'undefined') {
      localStorage.removeItem('auth_token');
      delete axios.defaults.headers.common['Authorization'];
    }
  },

  // ✅ Obtener listado de películas
  async fetchMovies() {
    const response = await axios.get(`${API_URL}/movies`);
    return response.data;
  },

  // ✅ Obtener detalles de una película
  async fetchMovieDetails(movie_id) {
    const response = await axios.get(`${API_URL}/movies/${movie_id}`);
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
