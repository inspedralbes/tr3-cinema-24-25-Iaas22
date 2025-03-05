<template>
  <div>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
import CommunicationManager from '@/services/CommunicationManager';

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

    // Asegúrate de que el token esté presente en la respuesta
    const token = response.token;
    if (token) {
      // Guardamos el token en localStorage
      localStorage.setItem('authToken', token);
      console.log('Token guardado en localStorage:', token);  // Verifica en la consola si el token se guardó correctamente

      // Redirigir a la vista index.vue
      this.$router.push('/');
    } else {
      console.error('Token no disponible en la respuesta del servidor');
    }
  } catch (error) {
    console.error('Error de login:', error);
    alert('Login fallido');
  }
}

  }
};
</script>
