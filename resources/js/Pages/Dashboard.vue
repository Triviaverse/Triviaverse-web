<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white">
    <div class="container mx-auto">
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

      <!-- Saját eredményeim kártyái -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
        <div
          v-for="att in attempts"
          :key="att.id"
          :class="['p-6 rounded-xl shadow-md flex justify-between items-center', bgClass(att.quizResult.score_percentage)]"
        >
          <div>
            <h2 class="text-xl font-semibold">{{ att.quiz.title }}</h2>
            <p class="mt-2">
              Eredmény: <span class="font-bold">{{ att.quizResult.score_percentage }}%</span>
              <span v-if="att.quizResult.is_overridden" class="text-sm text-yellow-300">(Felülvizsgált)</span>
            </p>
          </div>
          <button
            @click="viewResult(att.quiz.id, att.id)"
            class="bg-white text-gray-900 px-4 py-2 rounded hover:bg-gray-200"
          >Megtekintés</button>
        </div>
      </div>

        <!-- Diákok eredményei (teacher/admin) -->
        <div v-if="studentResults.length" class="mt-8">
          <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-semibold">Diákok eredményei</h1>
          </div>
          <button
            @click="reload"
            class="bg-blue-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
          >🔄 Frissítés</button>
        </div>        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div
            v-for="sr in studentResults"
            :key="sr.id"
            :class="['p-6 rounded-xl shadow-md flex justify-between items-center', bgClass(sr.quizResult.score_percentage)]"
          >
            <div>
              <h2 class="text-xl font-semibold">{{ sr.quiz.title }}</h2>
              <p class="text-gray-200">Diák: <span class="font-bold">{{ sr.student.name }}</span></p>
              <p class="mt-1">
                <span class="font-bold">{{ sr.quizResult.score_percentage }}%</span>
                <span v-if="sr.quizResult.is_overridden" class="text-sm text-yellow-300">(Felülvizsgált)</span>
              </p>
            </div>
            <button
              @click="viewResult(sr.quiz.id, sr.id)"
              class="bg-white text-gray-900 px-4 py-2 rounded hover:bg-gray-200"
            >Megtekintés</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import { router } from '@inertiajs/vue3';

export default {
  props: {
    user:           { type: Object, required: true },
    stats:          { type: Object, required: true },
    attempts:       { type: Array,  required: true },
    studentResults: { type: Array,  default: () => [] },
  },
  components: { Navbar },
  methods: {
    viewResult(quizId, attemptId) {
      router.get(route('quizzes.result', { quiz: quizId, attempt: attemptId }));
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
/* Tailwind-osztályokkal minden stílus már kész */
</style>
