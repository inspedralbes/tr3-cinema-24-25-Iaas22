<template>
    <div class="auth-container">
      <h1>Registro</h1>
      <form @submit.prevent="registerUser">
        <div>
          <label for="name">Nombre</label>
          <input type="text" v-model="form.name" required />
        </div>
        <div>
          <label for="email">Correo Electrónico</label>
          <input type="email" v-model="form.email" required />
        </div>
        <div>
          <label for="password">Contraseña</label>
          <input type="password" v-model="form.password" required />
        </div>
        <div>
          <label for="birthday">Fecha de Nacimiento</label>
          <input type="date" v-model="form.birthday" />
        </div>
        <button type="submit">Registrarse</button>
      </form>
      <p>¿Ya tienes cuenta? <router-link to="/login">Inicia sesión</router-link></p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: '',
          birthday: '',
        },
      };
    },
    methods: {
      async registerUser() {
        try {
          const response = await fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.form),
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Error al registrar el usuario');
          }
  
          alert(data.message);
          this.$router.push('/login'); // Redirige al login después del registro
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
  