<template>
    <div class="register-container">
      <div class="register-card">
        <h2 class="register-title">Registro</h2>
        <form @submit.prevent="register" class="register-form">
          <div class="form-group">
            <label>Nombre</label>
            <input
              type="text"
              v-model="form.name"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label>Correo electrónico</label>
            <input
              type="email"
              v-model="form.email"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label>Contraseña</label>
            <input
              type="password"
              v-model="form.password"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label>Confirmar contraseña</label>
            <input
              type="password"
              v-model="form.password_confirmation"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input
              type="date"
              v-model="form.birthday"
              required
              class="form-input"
            />
          </div>
          <button
            type="submit"
            class="register-button"
            :disabled="loading"
          >
            {{ loading ? 'Registrando...' : 'Registrarse' }}
          </button>
          <p v-if="error" class="error-message">{{ error }}</p>
  
          <!-- ✅ Texto para redirigir a login -->
          <p class="login-text">
            ¿Ya tienes cuenta?  
            <span @click="goToLogin" class="login-link">Inicia sesión aquí</span>
          </p>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    birthday: ''
  })
  
  const error = ref('')
  const loading = ref(false)
  
  const register = async () => {
    error.value = ''
    loading.value = true
  
    try {
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(form.value)
      })
  
      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || 'Error al registrar usuario')
      }
  
      const data = await response.json()
  
      // ✅ Guardar token e información del usuario en localStorage
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
  
      // ✅ Redirigir al login
      router.push('/login')
    } catch (err) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }
  
  // ✅ Función para redirigir a la página de login
  const goToLogin = () => {
    router.push('/login')
  }
  </script>
  
  <style scoped>
  /* ✅ Fondo blanco */
  .register-container {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  /* ✅ Tarjeta con sombra elegante */
  .register-card {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .register-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  }
  
  /* ✅ Título centrado */
  .register-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  /* ✅ Campos de formulario */
  .form-group {
    margin-bottom: 1.2rem;
  }
  
  .form-group label {
    font-size: 0.95rem;
    color: #555;
    margin-bottom: 0.3rem;
    display: block;
  }
  
  .form-input {
    width: 100%;
    padding: 0.8rem;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: border-color 0.3s ease;
    outline: none;
    background-color: #fafafa;
  }
  
  .form-input:focus {
    border-color: #333;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
  }
  
  /* ✅ Botón de registro */
  .register-button {
    width: 100%;
    padding: 0.8rem;
    background-color: #333;
    color: #ffffff;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .register-button:hover {
    background-color: #555;
  }
  
  .register-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  
  /* ✅ Mensaje de error */
  .error-message {
    color: #ff4d4d;
    font-size: 0.9rem;
    margin-top: 0.8rem;
    text-align: center;
  }
  
  /* ✅ Texto para ir al login */
  .login-text {
    font-size: 0.9rem;
    color: #777;
    text-align: center;
    margin-top: 1rem;
  }
  
  .login-link {
    color: #333;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
    text-decoration: underline;
  }
  
  .login-link:hover {
    color: #555;
  }
  
  /* ✅ Responsive para pantallas pequeñas */
  @media (max-width: 400px) {
    .register-card {
      padding: 1.5rem;
    }
  }
  </style>
  