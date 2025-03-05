import axios from 'axios';

export default class CommunicationManager {
  static baseUrl = 'http://localhost:8000/api';  // Cambia la URL a tu backend

  static async login(email, password) {
    try {
      const response = await axios.post(`${this.baseUrl}/login`, { email, password });
      this.storeToken(response.data.token);  // Almacenar el token
      return response.data;
    } catch (error) {
      console.error("Error al hacer login", error);
      throw error;
    }
  }

  static async register(name, email, password, birthday) {
    try {
      const response = await axios.post(`${this.baseUrl}/register`, {
        name,
        email,
        password,
        birthday,
      });
      this.storeToken(response.data.token);  // Almacenar el token
      return response.data;
    } catch (error) {
      console.error("Error al hacer registro", error);
      throw error;
    }
  }

  static storeToken(token) {
    localStorage.setItem('authToken', token);  // Almacena el token en localStorage
  }

  static getToken() {
    return localStorage.getItem('authToken');  // Recupera el token desde localStorage
  }

  static async logout() {
    try {
      const token = this.getToken();
      await axios.post(`${this.baseUrl}/logout`, {}, {
        headers: { Authorization: `Bearer ${token}` }
      });
      this.clearToken();  // Elimina el token después del logout
      return true;
    } catch (error) {
      console.error("Error al hacer logout", error);
      throw error;
    }
  }

  static clearToken() {
    localStorage.removeItem('authToken');  // Elimina el token del localStorage
  }

  static async getUserDetails() {
    try {
      const token = this.getToken();
      if (!token) {
        throw new Error('No token available');
      }
      const response = await axios.get(`${this.baseUrl}/user`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      return response.data;
    } catch (error) {
      console.error("Error al obtener detalles del usuario", error);
      throw error;
    }
  }
}
