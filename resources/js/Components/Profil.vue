<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900 text-white">
      <div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-6">Profil beállítások</h2>
  
        <!-- Profilkép feltöltés -->
        <div class="flex flex-col items-center mb-6">
          <label for="profilePicture" class="cursor-pointer">
            <img 
              :src="previewImage || user.profilePicture || defaultProfileImage" 
              alt="Profilkép"
              class="w-24 h-24 rounded-full border-4 border-blue-500 shadow-lg"
            />
          </label>
          <input type="file" id="profilePicture" @change="handleFileUpload" class="hidden">
          <p class="text-gray-400 text-sm mt-2">Kattints a képre a módosításhoz</p>
        </div>
  
        <!-- Profil adatok szerkesztése -->
        <form @submit.prevent="updateProfile">
          <div class="mb-4">
            <label class="block text-gray-300">Felhasználónév</label>
            <input 
              v-model="form.name"
              type="text"
              class="w-full bg-gray-700 text-white rounded-md p-3 mt-1 border border-gray-600 focus:ring focus:ring-blue-400"
              required
            >
          </div>
  
          <div class="mb-4">
            <label class="block text-gray-300">Email</label>
            <input 
              v-model="form.email"
              type="email"
              class="w-full bg-gray-700 text-white rounded-md p-3 mt-1 border border-gray-600 focus:ring focus:ring-blue-400"
              required
            >
          </div>
  
          <div class="mb-4">
            <label class="block text-gray-300">Új jelszó</label>
            <input 
              v-model="form.password"
              type="password"
              class="w-full bg-gray-700 text-white rounded-md p-3 mt-1 border border-gray-600 focus:ring focus:ring-blue-400"
            >
            <p class="text-sm text-gray-400 mt-1">Hagyd üresen, ha nem szeretnéd módosítani</p>
          </div>
  
          <button 
            type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-5 py-3 rounded-lg shadow-md transition transform hover:scale-105"
          >
            Mentés
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, router } from '@inertiajs/vue3';
  
  // Alapértelmezett profilkép, ha nincs feltöltve
  const defaultProfileImage = 'https://via.placeholder.com/100/blue/ffffff?text=Profil';
  
  // Felhasználói adatok (ezeket az adatokat az API biztosítja)
  const user = ref({
    name: "John Doe",
    email: "johndoe@example.com",
    profilePicture: null,
  });
  
  // Profil szerkesztés űrlap
  const form = useForm({
    name: user.value.name,
    email: user.value.email,
    password: "",
  });
  
  // Profilkép feltöltése
  const previewImage = ref(null);
  const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImage.value = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  };
  
  // Profil frissítés
  const updateProfile = () => {
    console.log("Mentett adatok:", form);
    router.post(route('profile.update'), form);
  };
  </script>
  