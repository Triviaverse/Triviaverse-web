<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
    <div class="container mx-auto">

      <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <h1 class="text-3xl font-bold text-blue-400 flex-shrink-0">Elérhető kvízek</h1>
        
        <div class="relative flex-1 max-w-sm">
          <svg
            class="absolute left-3 top-1/2 w-5 h-5 text-gray-400 pointer-events-none -translate-y-1/2"
            xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z" />
          </svg>
          <input
            v-model="search"
            type="text"
            placeholder="Kvíz keresése..."
            class="w-full bg-gray-700 text-white py-2 rounded shadow-inner focus:outline-none pl-10 pr-4"
          />
        </div>

        <div v-if="user.role === 'teacher'">
          <Link
            :href="route('quizzes.create')"
            class="bg-green-500 px-5 py-3 rounded-lg text-white hover:bg-green-600 shadow-md transition"
          >
            + Új kvíz létrehozása
          </Link>
        </div>
      </div>

      <p v-if="quizzes.length === 0" class="text-gray-400 text-center text-lg">
        Még nincs elérhető kvíz.
      </p>

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
            <button
              @click="startQuiz(quiz.id)"
              class="bg-blue-500 px-5 py-2 rounded-lg text-white hover:bg-blue-600 transition shadow-md"
            >
              Kvíz kitöltése
            </button>
            
            <template v-if="(user.role === 'teacher' && quiz.created_by === user.id) || user.role === 'admin'">
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
import Navbar from '@/Components/Navbar.vue'
import { Link, router } from '@inertiajs/vue3'

export default {
  components: { Navbar, Link },
  props: {
    user:    { type: Object, required: true },
    quizzes: { type: Array,  required: true },
    filters: { type: Object, required: true },
  },
  data() {
    return {
      search: this.filters.search || '',
    }
  },
  watch: {
    search(val) {
      router.get(
        route('quizzes.index'),
        { search: val },
        { preserveState: true, replace: true }
      )
    }
  },
  methods: {
    startQuiz(quizId) {
      router.visit(route('quizzes.start', { id: quizId }))
    },
    deleteQuiz(quizId) {
      if (confirm("Biztosan törlöd ezt a kvízt?")) {
        router.delete(route('quizzes.destroy', { id: quizId }), {
          onSuccess: () => this.$inertia.reload(),
        })
      }
    },
  },
}
</script>

<style scoped>
button, a {
  transition: background-color 0.3s ease, transform 0.2s ease;
}
</style>
