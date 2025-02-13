<template>
    <div class="auth-container mt-5">
      <h2>Regisztráció</h2>
      <form @submit.prevent="handleRegister">
        <input v-model="email" type="email" placeholder="Email" required />
        <input v-model="password" type="password" placeholder="Jelszó" required />
        <input v-model="password_confirmation" type="password" placeholder="Jelszó megerősítése" required />
        <button type="submit">Regisztrálás</button>
      </form>
      <p>Már van fiókod? <RouterLink to="/bejelentkezes" class="login-link">Bejelentkezés</RouterLink></p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return { email: '', password: '', password_confirmation: '' };
    },
    methods: {
      async handleRegister() {
        try {
          const response = await axios.post('/api/register', {
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          });
          this.$router.push('/bejelentkezes'); 
        } catch (error) {
          alert('Hiba történt a regisztráció során.');
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
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  text-align: center; 
}

h2 {
  color: #2b5876; 
  margin-bottom: 20px;
}

input {
  display: block;
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s ease;
}

input:focus {
  border-color: #ffcc00; 
  outline: none;
}

button {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #2b5876, #4e4376); 
  border: none;
  border-radius: 5px;
  color: #ffffff;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.3s ease;
}

button:hover {
  background: #4e4376; 
  transform: scale(1.05); 
}

.register-link {
  color: #2b5876; 
  text-decoration: none;
  transition: color 0.3s ease;
}

.register-link:hover {
  color: #ffcc00; 
}
</style>
  