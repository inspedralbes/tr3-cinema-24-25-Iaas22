<template>
  <div>
    <form @submit.prevent="register">
      <input v-model="name" type="text" placeholder="Name" />
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <input v-model="birthday" type="date" placeholder="Birthday" />
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
import CommunicationManager from '@/services/CommunicationManager';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      birthday: '',
    };
  },
  methods: {
    async register() {
      try {
        const response = await CommunicationManager.register(
          this.name,
          this.email,
          this.password,
          this.birthday
        );
        console.log('Registro exitoso:', response);
        this.$router.push('/');  // Redirigir al dashboard u otra página
      } catch (error) {
        console.error('Error de registro:', error);
        alert('Registro fallido');
      }
    }
  }
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
  