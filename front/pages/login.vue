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
/* ✅ Fondo azul oscuro con gradiente */
.login-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

/* ✅ Tarjeta de login con efecto de vidrio (glassmorphism) */
.login-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  padding: 2.5rem;
  width: 100%;
  max-width: 420px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
}

/* ✅ Título con color claro */
.login-title {
  font-size: 2rem;
  font-weight: 600;
  color: #ffffff;
  margin-bottom: 2rem;
  text-align: center;
  letter-spacing: 0.5px;
}

/* ✅ Campos de formulario con estilo moderno */
.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 0.5rem;
  display: block;
  font-weight: 500;
}

.form-input {
  width: 380px;
  padding: 0.9rem 1.2rem;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.1);
  color: #ffffff;
}

.form-input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.form-input:focus {
  border-color: rgba(255, 255, 255, 0.4);
  box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.15);
  outline: none;
}

/* ✅ Botón de login con gradiente */
.login-button {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 0.5rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.login-button:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.login-button:active {
  transform: translateY(0);
}

.login-button:disabled {
  background: #cccccc;
  transform: none;
  box-shadow: none;
  cursor: not-allowed;
}

/* ✅ Mensaje de error */
.error-message {
  color: #ff6b6b;
  font-size: 0.9rem;
  margin-top: 1rem;
  text-align: center;
  background: rgba(255, 107, 107, 0.1);
  padding: 0.7rem;
  border-radius: 6px;
  border-left: 3px solid #ff6b6b;
}

/* ✅ Texto para registro */
.register-text {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.7);
  text-align: center;
  margin-top: 1.5rem;
}

.register-link {
  color: #4facfe;
  font-weight: 600;
  cursor: pointer;
  transition: color 0.3s ease;
  text-decoration: none;
}

.register-link:hover {
  color: #3a92d8;
  text-decoration: underline;
}

/* ✅ Modal con estilo de vidrio */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
  padding: 1rem;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  padding: 2.5rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  text-align: center;
  width: 100%;
  max-width: 450px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  animation: fadeIn 0.4s ease;
}

.modal-content h2 {
  color: #ffffff;
  margin-bottom: 1rem;
  font-size: 1.8rem;
}

.modal-content p {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.1rem;
  margin-bottom: 2rem;
}

.modal-button {
  background: linear-gradient(to right, #0a2d4b 0%, #00f2fe 100%);
  color: white;
  padding: 0.9rem 2rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.modal-button:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* ✅ Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ✅ Responsive para tablets */
@media (max-width: 768px) {
  .login-card {
    padding: 2rem;
    max-width: 380px;
  }
  
  .login-title {
    font-size: 1.7rem;
  }
}

/* ✅ Responsive para móviles */
@media (max-width: 480px) {
  .login-card {
    padding: 1.5rem;
    max-width: 100%;
  }
  
  .login-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .form-input {
    padding: 0.8rem 1rem;
  }
  
  .modal-content {
    padding: 1.8rem;
  }
  
  .modal-content h2 {
    font-size: 1.5rem;
  }
  
  .modal-content p {
    font-size: 1rem;
  }
}
</style>