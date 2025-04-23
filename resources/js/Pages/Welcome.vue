<template>
  <div class="min-h-screen bg-gray-900 text-white flex flex-col">
    <!-- Hős szekció -->
    <div class="flex flex-col items-center text-center p-12 bg-gray-800 shadow-lg rounded-b-xl">
      <h1 class="text-5xl font-bold mb-4">
        Üdvözöl a <span class="text-blue-400">Triviaverse</span>!
      </h1>
      <p class="text-lg text-gray-300 max-w-2xl">
        Egy modern, interaktív kvízrendszer, amely lehetővé teszi a tanároknak kvízek létrehozását, 
        a diákoknak pedig a tudásuk próbára tételét.
      </p>
    </div>

    <!-- Funkciók szekció -->
    <div class="container mx-auto px-6 py-12 text-center">
      <h2 class="text-3xl font-bold text-blue-400 mb-8">Miért válaszd a Triviaverse-t?</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-gray-800 p-6 rounded-lg shadow-md hover:scale-105 transition">
          <h3 class="text-xl font-semibold text-blue-300 mb-2">🎯 Pontos statisztikák</h3>
          <p class="text-gray-300">Kövesd nyomon a fejlődésed, és javíts az eredményeiden.</p>
        </div>
        <div class="bg-gray-800 p-6 rounded-lg shadow-md hover:scale-105 transition">
          <h3 class="text-xl font-semibold text-blue-300 mb-2">📝 Kvíz készítés</h3>
          <p class="text-gray-300">Készíts saját kvízeket, és oszd meg a diákokkal vagy barátokkal.</p>
        </div>
        <div class="bg-gray-800 p-6 rounded-lg shadow-md hover:scale-105 transition">
          <h3 class="text-xl font-semibold text-blue-300 mb-2">📈 Grafikon</h3>
          <p class="text-gray-300">Eredményeid fejlődését grafikonon is tudod követni.</p>
        </div>
      </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center py-12 bg-gray-800 shadow-lg">
      <h2 class="text-3xl font-bold text-blue-400 mb-4">Kezdd el most!</h2>
      <p class="text-gray-300 text-lg mb-6">Válaszd ki a megfelelő opciót és csatlakozz hozzánk!</p>
      <div class="flex justify-center gap-4">
        <a 
          v-if="$page.props.auth.user"
          :href="route('dashboard')"
          class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition shadow-md transform hover:scale-105"
        >
          Dashboard
        </a>
        <template v-else>
          <a
            :href="route('login')"
            class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105"
            >
            Bejelentkezés
          </a>
          <a
            v-if="canRegister"
            :href="route('register')"
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105"
            >
            Regisztráció
          </a>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  canRegister: {
    type: Boolean,
  },
});
</script>

<style scoped>
body {
  font-family: 'Arial', sans-serif;
}
</style>
