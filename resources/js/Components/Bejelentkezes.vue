<template>
  <div class="auth-container mt-5">
      <h2>Bejelentkezés</h2>
      <form @submit.prevent="handleLogin">
          <input v-model="email" type="email" placeholder="Email" required />
          <input v-model="password" type="password" placeholder="Jelszó" required />
          <button type="submit">Bejelentkezés</button>
      </form>
      <p>Még nincs fiókod? <RouterLink to="/register" class="register-link">Regisztrálj</RouterLink></p>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
      return { email: '', password: '' };
  },
  methods: {
      async handleLogin() {
          try {
              const response = await axios.post('/api/login', { email: this.email, password: this.password });
              if (response.status === 200) {
                  this.$router.push('/'); 
              }
          } catch (error) {
              alert('Hibás email vagy jelszó');
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