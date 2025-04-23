<template>
  <Navbar v-if="user" :user="user" />
  <div class="relative p-8 bg-gray-900 min-h-screen text-white flex justify-center">
    <!-- Custom Alert Popup -->
    <transition name="slide-fade">
      <div v-show="alert.visible" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 bg-red-600 text-white p-6 rounded-lg shadow-xl z-50 flex flex-col items-center">
        <span class="mb-4 text-center">{{ alert.message }}</span>
        <button @click="alert.visible = false" class="mt-2 bg-white text-red-600 font-bold px-4 py-2 rounded hover:bg-gray-100">Bezár</button>
      </div>
    </transition>

    <div class="w-full max-w-3xl bg-gray-800 p-6 rounded-xl shadow-lg mt-16">
      <h1 class="text-3xl font-bold mb-6 text-blue-400">Új kvíz létrehozása</h1>

      <form @submit.prevent="submitQuiz">
        <!-- Kvíz címe -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-300">Kvíz címe</label>
          <input
            v-model="quiz.title"
            type="text"
            class="w-full bg-gray-900 text-white rounded-lg p-3 mt-2 border border-gray-600 focus:ring focus:ring-blue-500"
            required
          />
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
          />
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
            />

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
                <input
                  v-model="question.options[optIndex]"
                  type="text"
                  class="w-full bg-gray-800 text-white rounded-lg p-3 border border-gray-600 focus:ring focus:ring-blue-500"
                />
                <input
                  type="checkbox"
                  v-model="question.correctAnswers"
                  :value="optIndex"
                  class="accent-blue-500"
                />
                <button
                  v-if="question.options.length > 2"
                  @click.prevent="removeOption(index, optIndex)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                >
                  -
                </button>
              </div>

              <button
                @click.prevent="addOption(index)"
                class="mt-3 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition"
              >
                + Új válasz
              </button>
            </div>

            <!-- Beírós kérdés alapválasz -->
            <div v-if="question.type === 'text'" class="mt-4">
              <h3 class="text-sm font-semibold text-gray-300">Alapválasz</h3>
              <input
                v-model="question.defaultAnswer"
                type="text"
                class="w-full bg-gray-800 text-white rounded-lg p-3 border border-gray-600 focus:ring focus:ring-blue-500"
                placeholder="Írd be a helyes választ"
              />
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
          class="w-full bg-blue-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
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

<script>
import { router, Link } from "@inertiajs/vue3";
import Navbar from '@/Components/Navbar.vue';

export default {
  components: { Navbar, Link },
  props: { user: { type: Object, required: true } },
  data() {
    return {
      quiz: {
        title: '',
        description: '',
        time_limit: null,
        questions: [],
      },
      alert: { visible: false, message: '' },
    };
  },
  methods: {
    addQuestion() {
      if (this.quiz.questions.length < 50) {
        this.quiz.questions.push({
          question_text: '',
          type: 'multiple_choice',
          options: ['', ''],
          correctAnswers: [],
          defaultAnswer: '',
        });
      }
    },
    addOption(i) {
      this.quiz.questions[i].options.push('');
    },
    removeOption(i, j) {
      if (this.quiz.questions[i].options.length > 2) {
        this.quiz.questions[i].options.splice(j, 1);
      }
    },
    removeQuestion(i) {
      this.quiz.questions.splice(i, 1);
    },
    submitQuiz() {
      // Validation: ensure correct answers / default answer
      for (let i = 0; i < this.quiz.questions.length; i++) {
        const q = this.quiz.questions[i];
        if (q.type === 'multiple_choice' && (!q.correctAnswers || q.correctAnswers.length === 0)) {
          this.alert.message = `A(z) ${i + 1}. kérdéshez legalább egy helyes választ meg kell jelölni.`;
          this.alert.visible = true;
          return;
        }
        if (q.type === 'text' && (!q.defaultAnswer || q.defaultAnswer.trim() === '')) {
          this.alert.message = `A(z) ${i + 1}. beírós kérdéshez meg kell adni az alapválaszt.`;
          this.alert.visible = true;
          return;
        }
      }
      this.alert.visible = false;
      router.post(route('quizzes.store'), this.quiz);
    },
  },
};
</script>

<style>
.slide-fade-enter-active,
.slide-fade-leave-active { transition: all 0.3s ease; }
.slide-fade-enter-from,
.slide-fade-leave-to { opacity: 0; transform: translateY(-10px); }
.slide-fade-enter-to,
.slide-fade-leave-from { opacity: 1; transform: translateY(0); }
</style>