<template>
  <div class="login-form">
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
import CommunicationManager from '@/services/CommunicationManager'

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await CommunicationManager.login(this.email, this.password);

        // Guardamos el token en localStorage
        const token = response.token;
        if (token) {
          localStorage.setItem('authToken', token);
          this.$emit('login-success'); // Emitir el evento de éxito para cerrar el popup
        } else {
          alert('Credenciales incorrectas');
        }
      } catch (error) {
        alert('Login fallido');
        console.error(error);
      }
    }
  }
};
</script>

