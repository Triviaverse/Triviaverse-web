<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white">
    <div class="max-w-5xl mx-auto">

      <!-- Üdvözlés + Frissítés -->
      <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-semibold">Eredményeim, {{ user.name }}!</h1>
          <p class="text-gray-400">
            Szerepkör: <span class="font-bold text-blue-400">{{ user.role }}</span>
          </p>
        </div>
        <button
          @click="reload"
          class="bg-blue-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
        >🔄 Frissítés</button>
      </div>

      <!-- Statisztikák -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 text-center">
        <div class="bg-blue-600 p-6 rounded-xl shadow-md">
          <h2 class="text-lg font-semibold">Összes kvíz</h2>
          <p class="text-4xl font-bold">{{ stats.totalQuizzes }}</p>
        </div>
        <div class="bg-green-600 p-6 rounded-xl shadow-md">
          <h2 class="text-lg font-semibold">Kitöltött kvízek</h2>
          <p class="text-4xl font-bold">{{ stats.completedQuizzes }}</p>
        </div>
        <div class="bg-yellow-600 p-6 rounded-xl shadow-md">
          <h2 class="text-lg font-semibold">Függőben lévő kvízek</h2>
          <p class="text-4xl font-bold">{{ stats.pendingQuizzes }}</p>
        </div>
      </div>

      <!-- Eredményeim kártyái -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="attempt in attempts"
          :key="attempt.id"
          :class="['p-6 rounded-xl shadow-md flex justify-between items-center', bgClass(attempt.quizResult.score_percentage)]"
        >
          <div>
            <h2 class="text-xl font-semibold">{{ attempt.quiz.title }}</h2>
            <p class="mt-2">
              Eredmény:
              <span class="font-bold">{{ attempt.quizResult.score_percentage }}%</span>
            </p>
          </div>
          <button
            @click="viewResult(attempt.quiz.id)"
            class="bg-white text-gray-900 px-4 py-2 rounded hover:bg-gray-200 transition"
          >Megtekintés</button>
        </div>

        <div v-if="!attempts.length" class="col-span-full text-center text-gray-400">
          Nincsenek még kitöltött teszteredményeid.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import { router } from '@inertiajs/vue3';

export default {
  components: { Navbar },
  props: {
    user:     { type: Object, required: true },
    stats:    { type: Object, required: true },
    attempts: { type: Array,  required: true },
  },
  methods: {
    reload() {
      router.reload({ only: ['stats','attempts'] });
    },
    viewResult(quizId) {
      router.get(`/quizzes/${quizId}/result`);
    },
    bgClass(score) {
      if (score <= 25) return 'bg-red-600';
      if (score <= 50) return 'bg-orange-500';
      if (score <= 75) return 'bg-yellow-500';
      return 'bg-green-600';
    },
  },
};
</script>

<style scoped>
/* A reszponzív rács és kártya-színek Tailwind-del kezelve */
</style>
