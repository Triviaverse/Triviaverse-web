<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
    <div class="w-full">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-blue-400">Elérhető kvízek</h1>

        <!-- Ha tanár, akkor megjelenik a gomb -->
        <div v-if="user.role === 'teacher'">
          <Link 
            :href="route('quizzes.create')" 
            class="bg-green-500 px-5 py-3 rounded-lg text-white hover:bg-green-600 shadow-md transition"
          >
            + Új kvíz létrehozása
          </Link>
        </div>
      </div>

      <!-- Ha nincs kvíz -->
      <p v-if="quizzes.length === 0" class="text-gray-400 text-center text-lg">
        Még nincs elérhető kvíz.
      </p>

      <!-- Kvíz lista -->
      <div class="space-y-6">
        <div 
          v-for="quiz in quizzes" 
          :key="quiz.id" 
          class="bg-gray-800 p-6 rounded-xl shadow-lg transition transform hover:scale-[1.02]"
        >
          <h2 class="text-2xl font-semibold text-blue-300">{{ quiz.title }}</h2>
          <p class="text-gray-400 mt-1">{{ quiz.description }}</p>
          <p class="text-sm text-gray-500 mt-2">
            ⏳ Időlimit: {{ quiz.time_limit ? quiz.time_limit + ' perc' : 'Nincs időkorlát' }}
          </p>

          <div class="mt-5 flex flex-wrap gap-3">
            <!-- Kitöltés gomb -->
            <button 
              @click="startQuiz(quiz.id)" 
              class="bg-blue-500 px-5 py-2 rounded-lg text-white hover:bg-blue-600 transition shadow-md"
            >
              Kvíz kitöltése
            </button>

            <!-- Szerkesztés és törlés csak tanároknak -->
            <template v-if="user.role === 'teacher' && quiz.created_by === user.id">
              <Link 
                :href="route('quizzes.edit', { id: quiz.id })" 
                class="bg-yellow-500 px-5 py-2 rounded-lg text-white hover:bg-yellow-600 transition shadow-md"
              >
                Szerkesztés
              </Link>
              <button 
                @click="deleteQuiz(quiz.id)" 
                class="bg-red-500 px-5 py-2 rounded-lg text-white hover:bg-red-600 transition shadow-md"
              >
                Törlés
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router, Link } from "@inertiajs/vue3"; 
import Navbar from '@/Components/Navbar.vue';

export default {
  components: {
    Navbar,
    Link
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    quizzes: {
      type: Array,
      required: true
    }
  },
  methods: {
    startQuiz(quizId) {
      router.visit(route('quizzes.start', { id: quizId }));
    },
    deleteQuiz(quizId) {
      if (confirm("Biztosan törlöd ezt a kvízt?")) {
        router.delete(route('quizzes.destroy', { id: quizId }), {
          onSuccess: () => {
            this.$inertia.reload();
          }
        });
      }
    }
  }
};
</script>

<style scoped>
button, a {
  transition: background-color 0.3s ease, transform 0.2s ease;
}
</style>
