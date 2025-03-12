import axios from 'axios';

export default {
  // ✅ Obtener configuración base de la API
  getApiBase() {
    return useRuntimeConfig().public.apiBase;
  },

  // ✅ Obtener token desde localStorage
  getToken() {
    return localStorage.getItem('token');
  },

  // ✅ Configurar encabezados para solicitudes autenticadas
  getAuthHeaders() {
    const token = this.getToken();
    return token
      ? {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      : { 'Content-Type': 'application/json' };
  },

  // ✅ Comprobar si el token es válido
  async checkAuth() {
    const token = this.getToken();
    if (!token) return false;

    try {
      const apiBase = this.getApiBase();
      await axios.get(`${apiBase}/auth/check`, { headers: this.getAuthHeaders() });
      return true;
    } catch (error) {
      console.warn('⚠️ Token inválido o caducado:', error.message);
      this.logout(); // Eliminar el token si no es válido
      return false;
    }
  },

  // ✅ Login de usuario
  async login(email, password) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.post(`${apiBase}/login`, { email, password });

      // ✅ Guardar token e información del usuario en localStorage
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.error || 'Error al iniciar sesión'
      );
    }
  },

  // ✅ Registro de usuario
  async register(userData) {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.post(`${apiBase}/register`, userData);

      // ✅ Guardar token e información del usuario en localStorage
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Error al registrar usuario'
      );
    }
  },

  // ✅ Cierre de sesión
  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login'; // Redirigir al login
  },

  // ✅ Obtener listado de películas
  async fetchMovies() {
    try {
      const apiBase = this.getApiBase();
      const response = await axios.get(`${apiBase}/movies`);
      return response.data;
    } catch (error) {
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
      console.error("Error cargando detalles de la película:", error);
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
      console.error('❌ Error al obtener las sesiones:', error);
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
      console.error('❌ Error al obtener las butacas:', error);
      throw new Error('No se pudieron cargar las butacas');
    }
  },

  // ✅ Reservar butaca (si está autenticado)
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
      console.error('❌ Error al reservar la butaca:', error);
      throw new Error(
        error.response?.data?.message || 'Error al reservar la butaca'
      );
    }
  }
};
