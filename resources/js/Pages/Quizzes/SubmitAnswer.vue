<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white flex flex-col items-center justify-center">
    <h1 class="text-3xl font-bold mb-4 text-green-400">A kvíz sikeresen beküldve!</h1>
    <p class="mb-6 text-gray-300">Az eredményed kiszámítása folyamatban...</p>
    <button
      @click="goToResult"
      class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition"
    >Eredmény megtekintése</button>
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import { router } from '@inertiajs/vue3';

export default {
  components: { Navbar },
  props: {
    user: Object,
    quiz: Object,
    quizAttempt: Object,
  },
  methods: {
    goToResult() {
      router.get(route('quizzes.result', {
        quiz: this.quiz.id,
        attempt: this.quizAttempt.id
      }));
    },
  },
};
</script>
