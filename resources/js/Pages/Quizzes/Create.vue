<template>
  <Navbar v-if="user" :user="user" />
  <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
    <div class=" w-full bg-gray-800 p-6 rounded-xl shadow-lg">
      <h1 class="text-3xl font-bold mb-6 text-blue-400">Új kvíz létrehozása</h1>
      <p v-if="errorMessage" class="text-red-500 text-sm mb-4">{{ errorMessage }}</p>

      <form @submit.prevent="submitQuiz">
        <!-- Kvíz címe -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-300">Kvíz címe</label>
          <input 
            v-model="quiz.title" 
            type="text" 
            class="w-full bg-gray-900 text-white rounded-lg p-3 mt-2 border border-gray-600 focus:ring focus:ring-blue-500" 
            required
          >
        </div>

        <!-- Kvíz leírása -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-300">Leírás</label>
          <textarea 
            v-model="quiz.description" 
            class="w-full bg-gray-900 text-white rounded-lg p-3 mt-2 border border-gray-600 focus:ring focus:ring-blue-500"
          ></textarea>
        </div>

        <!-- Időlimit -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-300">Időlimit (percben, opcionális)</label>
          <input 
            v-model="quiz.time_limit" 
            type="number" 
            min="1" 
            class="w-full bg-gray-900 text-white rounded-lg p-3 mt-2 border border-gray-600 focus:ring focus:ring-blue-500"
          >
        </div>

        <!-- Kérdések -->
        <div class="mb-5">
          <h2 class="text-2xl font-semibold text-blue-400">Kérdések</h2>

          <div 
            v-for="(question, index) in quiz.questions" 
            :key="index" 
            class="bg-gray-900 p-5 rounded-xl shadow-md mt-5"
          >
            <label class="block text-sm font-semibold text-gray-300">
              Kérdés {{ index + 1 }}
            </label>
            <input 
              v-model="question.question_text" 
              type="text" 
              class="w-full bg-gray-800 text-white rounded-lg p-3 mt-2 border border-gray-600 focus:ring focus:ring-blue-500"
              required
            >

            <!-- Kérdés típusa -->
            <div class="mt-3">
              <label class="text-sm font-semibold text-gray-300">Típus:</label>
              <select 
                v-model="question.type" 
                class="w-full bg-gray-800 text-white p-3 rounded-lg border border-gray-600 focus:ring focus:ring-blue-500"
              >
                <option value="multiple_choice">Feleletválasztós</option>
                <option value="text">Beírós</option>
              </select>
            </div>

            <!-- Feleletválasztós opciók -->
            <div v-if="question.type === 'multiple_choice'" class="mt-4">
              <h3 class="text-sm font-semibold text-gray-300">Válaszlehetőségek</h3>
              
              <div 
                v-for="(option, optIndex) in question.options" 
                :key="optIndex" 
                class="flex items-center gap-3 mt-2"
              >
                <!-- Válasz beviteli mező -->
                <input 
                  v-model="question.options[optIndex]" 
                  type="text" 
                  class="w-full bg-gray-800 text-white rounded-lg p-3 border border-gray-600 focus:ring focus:ring-blue-500"
                >

                <!-- Helyes válasz checkbox -->
                <input 
                  type="checkbox" 
                  v-model="question.correctAnswers" 
                  :value="optIndex" 
                  class="accent-blue-500"
                >

                <!-- 🛑 Törlés gomb (ha legalább 2 opció van) -->
                <button 
                  v-if="question.options.length > 2"
                  @click.prevent="removeOption(index, optIndex)" 
                  class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                >
                  -
                </button>
              </div>

              <!-- Új válasz hozzáadása -->
              <button 
                @click.prevent="addOption(index)" 
                class="mt-3 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition"
              >
                + Új válasz
              </button>
            </div>

            <!-- Kérdés törlése -->
            <button 
              @click.prevent="removeQuestion(index)" 
              class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
            >
              Törlés
            </button>
          </div>
        </div>

        <!-- Kérdés hozzáadása -->
        <button 
          @click.prevent="addQuestion" 
          class="w-4xl bg-blue-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
        >
          + Kérdés hozzáadása
        </button>

        <!-- Beküldés -->
        <button 
          type="submit" 
          class="mt-6 w-full bg-green-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-green-600 transition"
        >
          Kvíz létrehozása
        </button>
      </form>
    </div>
  </div>
</template>

---

## **✅ Frissített Vue metódusok**
```vue
<script>
import { router, Link } from "@inertiajs/vue3"; 
import Navbar from '@/Components/Navbar.vue';

export default {
  components: {
    Navbar,
    Link
  },
  data() {
    return {
      quiz: {
        title: "",
        description: "",
        time_limit: null,
        questions: [],
      },
      errorMessage: "",
    };
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  methods: {
    addQuestion() {
      if (this.quiz.questions.length < 50) {
        this.quiz.questions.push({
          question_text: "", 
          type: "multiple_choice",
          options: ["", ""], // 🛑 Legalább két válaszopció kell
          correctAnswers: [],
        });
      }
    },
    addOption(questionIndex) {
      this.quiz.questions[questionIndex].options.push("");
    },
    removeOption(questionIndex, optIndex) {
      // 🛑 Csak akkor törölhető, ha legalább 2 opció marad
      if (this.quiz.questions[questionIndex].options.length > 2) {
        this.quiz.questions[questionIndex].options.splice(optIndex, 1);
      }
    },
    removeQuestion(index) {
      this.quiz.questions.splice(index, 1);
    },
    submitQuiz() {
      if (this.quiz.questions.length === 0) {
        this.errorMessage = "Legalább egy kérdés szükséges a kvíz létrehozásához!";
        return;
      }
      router.post(route("quizzes.store"), this.quiz);
    },
  },
};
</script>
