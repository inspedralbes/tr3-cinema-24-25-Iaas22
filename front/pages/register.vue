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

        <p class="login-text">
          ¿Ya tienes cuenta?  
          <span @click="goToLogin" class="login-link">Inicia sesión aquí</span>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import communicationManager from '@/services/communicationManager';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  birthday: ''
});

const error = ref('');
const loading = ref(false);

const register = async () => {
  error.value = '';
  loading.value = true;

  try {
    await communicationManager.register(form.value);
    router.push('/login');
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};

const goToLogin = () => {
  router.push('/login');
};
</script>

<style scoped>
/* ✅ Fondo azul oscuro con gradiente */
.register-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #081e27, #02465d, #011721);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

/* ✅ Tarjeta de registro con efecto de vidrio (glassmorphism) */
.register-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  padding: 2.5rem;
  width: 100%;
  max-width: 450px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.register-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
}

/* ✅ Título con color claro */
.register-title {
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
  width: 410px;
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

/* ✅ Botón de registro con gradiente */
.register-button {
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

.register-button:hover {
  background: linear-gradient(to right, #3a92d8 0%, #00d9e6 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.register-button:active {
  transform: translateY(0);
}

.register-button:disabled {
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

/* ✅ Texto para login */
.login-text {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.7);
  text-align: center;
  margin-top: 1.5rem;
}

.login-link {
  color: #4facfe;
  font-weight: 600;
  cursor: pointer;
  transition: color 0.3s ease;
  text-decoration: none;
}

.login-link:hover {
  color: #3a92d8;
  text-decoration: underline;
}

/* ✅ Responsive para tablets */
@media (max-width: 768px) {
  .register-card {
    padding: 2rem;
    max-width: 380px;
  }
  
  .register-title {
    font-size: 1.7rem;
  }
}

/* ✅ Responsive para móviles */
@media (max-width: 480px) {
  .register-card {
    padding: 1.5rem;
    max-width: 100%;
  }
  
  .register-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .form-input {
    padding: 0.8rem 1rem;
  }
}
</style>