<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Iniciar sesión</h2>
      <form @submit.prevent="login" class="login-form">
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
        <button type="submit" class="login-button" :disabled="loading">
          {{ loading ? 'Ingresando...' : 'Iniciar sesión' }}
        </button>
        <p v-if="error" class="error-message">{{ error }}</p>

        <p class="register-text">
          ¿No tienes cuenta?
          <span @click="goToRegister" class="register-link">Regístrate aquí</span>
        </p>
      </form>
    </div>

    <!-- ✅ Modal Pop-up -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h2>¡Inicio de sesión exitoso!</h2>
        <p>Bienvenido, {{ user?.name || 'Usuario' }} 🎉</p>
        <button @click="closeModal" class="modal-button">Aceptar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import communicationManager from '@/services/communicationManager';

const router = useRouter();

const form = ref({
  email: '',
  password: ''
});

const error = ref('');
const loading = ref(false);
const showModal = ref(false);
const user = ref(null);

const login = async () => {
  error.value = '';
  loading.value = true;

  try {
    const data = await communicationManager.login(
      form.value.email,
      form.value.password
    );

    // ✅ Guardar token e información en localStorage
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));

    user.value = data.user;
    
    // ✅ Mostrar modal pop-up
    showModal.value = true;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};

// ✅ Cerrar el modal y redirigir
const closeModal = () => {
  showModal.value = false;
  router.push('/');
};

// ✅ Función para redirigir a la página de registro
const goToRegister = () => {
  router.push('/register');
};
</script>

<style scoped>
/* ✅ Fondo blanco */
.login-container {
  min-height: 100vh;
  background-color: #ffffff;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

/* ✅ Tarjeta de login adaptable */
.login-card {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.login-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* ✅ Título centrado */
.login-title {
  font-size: 1.8rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 1.5rem;
  text-align: center;
}

/* ✅ Campos de formulario */
.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  font-size: 1rem;
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
  background-color: #fafafa;
}

.form-input:focus {
  border-color: #333;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
}

/* ✅ Botón de login */
.login-button {
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

.login-button:hover {
  background-color: #555;
}

.login-button:disabled {
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

/* ✅ Texto para registro */
.register-text {
  font-size: 0.9rem;
  color: #777;
  text-align: center;
  margin-top: 1rem;
}

.register-link {
  color: #333;
  font-weight: bold;
  cursor: pointer;
  transition: color 0.3s ease;
  text-decoration: underline;
}

.register-link:hover {
  color: #555;
}

/* ✅ Fondo del modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
  padding: 1rem;
}

/* ✅ Contenido del modal */
.modal-content {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
  text-align: center;
  width: 100%;
  max-width: 400px;
  animation: fadeIn 0.3s ease;
}

.modal-button {
  background-color: #333;
  color: white;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s ease;
  font-size: 1rem;
}

.modal-button:hover {
  background-color: #555;
}

/* ✅ Animación de fade-in */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ✅ Responsive para tablets (≤ 768px) */
@media (max-width: 768px) {
  .login-card {
    padding: 1.5rem;
    max-width: 320px;
  }

  .login-title {
    font-size: 1.5rem;
  }

  .form-input {
    font-size: 0.9rem;
  }

  .login-button {
    font-size: 0.9rem;
  }

  .modal-content {
    padding: 1.5rem;
    max-width: 320px;
  }
}

/* ✅ Responsive para móviles pequeños (≤ 400px) */
@media (max-width: 400px) {
  .login-card {
    padding: 1.2rem;
    max-width: 100%;
  }

  .login-title {
    font-size: 1.3rem;
  }

  .form-input {
    font-size: 0.85rem;
    padding: 0.6rem;
  }

  .login-button {
    font-size: 0.85rem;
    padding: 0.6rem;
  }

  .modal-content {
    padding: 1.2rem;
    max-width: 100%;
  }

  .modal-button {
    font-size: 0.9rem;
  }
}

  </style>
  