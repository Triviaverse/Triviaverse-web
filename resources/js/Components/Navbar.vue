<template>
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        
        <!-- Márkanév -->
        <RouterLink to="/" class="navbar-brand">Triviaverse</RouterLink>
  
        <!-- Mobil menü gomb -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
  
        <!-- Menüelemek középen -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <RouterLink to="/" class="nav-link">Főoldal</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/kerdesek" class="nav-link">Kérdések</RouterLink>
            </li>
          </ul>
  
          <!-- Bejelentkezés / Profilom gomb -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item" v-if="!isLoggedIn">
              <RouterLink to="/bejelentkezes" class="nav-link login-btn">Bejelentkezés</RouterLink>
            </li>
            <li class="nav-item" v-if="isLoggedIn">
              <RouterLink to="/profil" class="nav-link">Profilom</RouterLink>
            </li>
          </ul>
  
        </div>
        
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    data() {
      return {
        // Bejelentkezett felhasználó állapotának kezelése
        isLoggedIn: false
      };
    },
    created() {
      // Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
      this.isLoggedIn = !!localStorage.getItem("userLoggedIn");
    }
  };
  </script>
  
  
  <style scoped>
  /* Navbar stílusok */
  .navbar {
    background: linear-gradient(135deg, #2b5876, #4e4376); /* Modern kék-lila gradient */
    padding: 15px 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
  
  /* Márkanév */
  .navbar-brand {
    color: #ffffff;
    font-size: 1.8rem;
    font-weight: bold;
    transition: transform 0.3s ease;
  }
  
  .navbar-brand:hover {
    transform: scale(1.1);
  }
  
  /* Navigációs linkek */
  .nav-link {
    font-size: larger;
    font-weight: 500;
    color: #ffffff;
    position: relative;
    transition: color 0.3s ease;
  }
  
  /* Hover effekt */
  .nav-link:hover {
    color: #ffcc00;
  }
  
  /* Animált underline */
  .nav-link::after {
    content: "";
    display: block;
    height: 3px;
    width: 0;
    background: #ffcc00;
    transition: width 0.3s ease-in-out;
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
  }
  
  .nav-link:hover::after {
    width: 80%;
  }
  
  /* Bejelentkezés gomb jobbra */
  .login-btn {
    background-color: #ffcc00;
    color: #333;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
  }
  
  .login-btn:hover {
    background-color: #ffd633;
    color: #000;
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
  }
  
  /* Mobilbarát hamburger ikon */
  .navbar-toggler {
    border: none;
    outline: none;
  }
  
  .navbar-toggler-icon {
    filter: invert(1);
  }
  </style>
  