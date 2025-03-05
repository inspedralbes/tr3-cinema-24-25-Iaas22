<template>
    <div class="auth-container">
      <h1>Iniciar sesión</h1>
      <form @submit.prevent="loginUser">
        <div>
          <label for="email">Correo Electrónico</label>
          <input type="email" v-model="form.email" required />
        </div>
        <div>
          <label for="password">Contraseña</label>
          <input type="password" v-model="form.password" required />
        </div>
        <button type="submit">Iniciar sesión</button>
      </form>
      <p>¿No tienes cuenta? <router-link to="/register">Regístrate</router-link></p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
      };
    },
    methods: {
      async loginUser() {
        try {
          const response = await fetch('http://localhost:8000/api/login', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.form),
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Error al iniciar sesión');
          }
  
          alert(data.message);
          localStorage.setItem('token', data.token); // Guarda el token en el localStorage
          this.$router.push('/'); // Redirige al index.vue (ruta raíz)
        } catch (error) {
          alert(error.message);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .auth-container {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  form div {
    margin-bottom: 15px;
  }
  
  form input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
  }
  
  button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  </style>
  