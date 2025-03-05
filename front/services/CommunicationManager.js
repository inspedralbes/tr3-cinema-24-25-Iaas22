export default {
  // Configuración base para la API
  apiBaseUrl: 'http://localhost:8000/api/',

  // Método para hacer peticiones POST
  async post(url, data, headers = {}) {
    try {
      const token = localStorage.getItem('token'); // Obtén el token de localStorage

      if (token) {
        headers['Authorization'] = `Bearer ${token}`; // Añades el token al header
      }

      const response = await fetch(`${this.apiBaseUrl}${url}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...headers, // Si ya hay headers, se combinan con el Authorization
        },
        body: JSON.stringify(data),
      });

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || `Error en la petición POST: ${response.statusText}`);
      }

      return responseData;
    } catch (error) {
      throw new Error(error.message);
    }
  },

  // Método para hacer peticiones GET
  async get(url, headers = {}) {
    try {
      const token = localStorage.getItem('token');

      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }

      const response = await fetch(`${this.apiBaseUrl}${url}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          ...headers,
        },
      });

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || `Error en la petición GET: ${response.statusText}`);
      }

      return responseData;
    } catch (error) {
      throw new Error(error.message);
    }
  },

  // Verifica si el usuario está autenticado
  isAuthenticated() {
    const token = localStorage.getItem('token');
    return token !== null;
  },

  // Método para obtener los datos del usuario autenticado
  async getUser() {
    try {
      const response = await this.get('me'); // Llama a la ruta que te devuelve el usuario
      return response;
    } catch (error) {
      throw new Error('Error al obtener los datos del usuario');
    }
  },

  // Método para hacer peticiones PUT (opcional)
  async put(url, data, headers = {}) {
    try {
      const token = localStorage.getItem('token');
      
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }

      const response = await fetch(`${this.apiBaseUrl}${url}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          ...headers,
        },
        body: JSON.stringify(data),
      });

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || `Error en la petición PUT: ${response.statusText}`);
      }

      return responseData;
    } catch (error) {
      throw new Error(error.message);
    }
  },

  // Método para hacer peticiones DELETE (opcional)
  async delete(url, headers = {}) {
    try {
      const token = localStorage.getItem('token');
      
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }

      const response = await fetch(`${this.apiBaseUrl}${url}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          ...headers,
        },
      });

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || `Error en la petición DELETE: ${response.statusText}`);
      }

      return responseData;
    } catch (error) {
      throw new Error(error.message);
    }
  },
};
