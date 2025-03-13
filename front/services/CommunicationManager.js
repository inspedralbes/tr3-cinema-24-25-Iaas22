import axios from 'axios';


export default {
  getApiBase() {
    return useRuntimeConfig().public.apiBase;
  },

  getToken() {
    return localStorage.getItem('token');
  },

  getAuthHeaders() {
    const token = this.getToken();
    return token ? { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' } : { 'Content-Type': 'application/json' };
  },

  async checkAuth() {
    const token = this.getToken();
    if (!token) return false;

    try {
      const apiBase = this.getApiBase();
      await axios.get(`${apiBase}/auth/check`, { headers: this.getAuthHeaders() });
      return true;
    } catch (error) {
      console.warn('⚠️ Token inválido o caducado:', error.message);
      this.logout();
      return false;
    }
  },

  async login(email, password) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.post(`${apiBase}/login`, { email, password });

      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Error al iniciar sesión');
    }
  },

  // ✅ Registro de usuario
  async register(userData) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.post(`${apiBase}/register`, userData);

      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Error al registrar usuario');
    }
  },

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
  },

  // ✅ Cierre de sesión
  logout() {
    console.log('🚪 Cerrando sesión...');
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
  },

  // ✅ Obtener listado de películas
  async fetchMovies() {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/movies`);
      return response.data;
    } catch (error) {
      console.error('❌ Error al cargar películas:', error);
      throw new Error('No se pudieron cargar las películas');
    }
  },

  // ✅ Obtener detalles de una película
  async fetchMovieDetails(movie_id) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/movies/${movie_id}`);
      return response.data;
    } catch (error) {
      console.error('❌ Error al cargar detalles de la película:', error);
      throw new Error('No se pudieron cargar los detalles de la película');
    }
  },

  // ✅ Obtener sesiones por ID de película
  async fetchSessionsByMovie(movieId) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/sessions/movie/${movieId}`, {
        headers: this.getAuthHeaders(),
      });
      return response.data;
    } catch (error) {
      console.error('❌ Error al obtener sesiones:', error);
      throw new Error('No se pudieron cargar las sesiones');
    }
  },

  // ✅ Obtener butacas por ID de sesión
  async fetchSeatsBySession(sessionId) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/seats/session/${sessionId}`, {
        headers: this.getAuthHeaders(),
      });
      return response.data;
    } catch (error) {
      console.error('❌ Error al obtener butacas:', error);
      throw new Error('No se pudieron cargar las butacas');
    }
  },

 
  async reserveSeat(seatId, sessionId) {
    if (!(await this.checkAuth())) {
      alert('⚠️ Debes iniciar sesión para reservar una butaca.');
      window.location.href = '/login';
      return;
    }

    try {
      const apiBase = this.getApiBase();
      const response = await axios.post(
        `${apiBase}/reserve-seat`,
        { seat_id: seatId, session_id: sessionId },
        { headers: this.getAuthHeaders() }
      );

      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Error al reservar la butaca');
    }
  },


  // ✅ Eliminar una reserva
  async cancelReservation(reservationId) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.delete(`${apiBase}/reservations/${reservationId}`, {
        headers: this.getAuthHeaders(),
      });
      console.log('✅ Reserva cancelada:', response.data);
      return response.data;
    } catch (error) {
      console.error('❌ Error al cancelar la reserva:', error);
      throw new Error('No se pudo cancelar la reserva');
    }
  },

  // ✅ Obtener historial de reservas
  async fetchReservations() {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/reservations`, {
        headers: this.getAuthHeaders(),
      });
      return response.data;
    } catch (error) {
      console.error('❌ Error al obtener las reservas:', error);
      throw new Error('No se pudieron cargar las reservas');
    }
  },
};
