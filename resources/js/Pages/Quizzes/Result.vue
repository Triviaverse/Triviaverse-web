<template>
    <Navbar v-if="user" :user="user" />
    <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
      <div class="w-full max-w-3xl bg-gray-800 p-6 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-blue-400">Eredmények</h1>
        <p class="text-xl mb-6">Teljesítményed: <span class="font-semibold">{{ quizResult.score_percentage }}%</span></p>
  
        <div v-for="(question, i) in quiz.questions" :key="question.id || i" class="mb-6">
          <p class="font-semibold text-gray-200 mb-1">{{ i + 1 }}. {{ question.question_text }}</p>
          <div class="flex items-center mb-2">
            <span class="mr-2">Válaszod:</span>
            <span class="italic">{{ displayAnswer(question, userAnswers[i]) }}</span>
            <span
              v-if="isCorrect(question, userAnswers[i])"
              class="ml-3 text-green-400 text-2xl"
            >✓</span>
            <span
              v-else
              class="ml-3 text-red-500 text-2xl"
            >✗</span>
          </div>
          <p v-if="!isCorrect(question, userAnswers[i])" class="text-gray-400">
            Helyes válasz: <span class="font-semibold">{{ displayCorrect(question) }}</span>
          </p>
        </div>
  
        <div class="text-center mt-8">
          <Link
            href="/quizzes"
            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition"
          >Vissza a kvízekhez</Link>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import Navbar from '@/Components/Navbar.vue';
  import { Link } from '@inertiajs/vue3';
  
  export default {
    components: { Navbar, Link },
    props: {
      user: Object,
      quiz: Object,
      quizAttempt: Object,
      quizResult: Object,
    },
    setup(props) {
      // Alapértelmezett válaszok inicializálása
      const raw = props.quizAttempt && props.quizAttempt.answers
        ? JSON.parse(props.quizAttempt.answers)
        : [];
      const userAnswers = ref(
        props.quiz.questions.map((q, i) => {
          if (raw[i] !== undefined) return raw[i];
          if (q.type === 'multiple_choice') return [];
          if (q.type === 'single_choice') return null;
          return '';
        })
      );
  
      // Helyesség ellenőrzése
      function isCorrect(question, answer) {
        if (question.type === 'multiple_choice' || question.type === 'single_choice') {
          const correct = Array.isArray(question.correctAnswers)
            ? question.correctAnswers
            : [];
          if (question.type === 'multiple_choice') {
            // Mindegyik megfelelő választ tartalmazza-e
            return (
              Array.isArray(answer) &&
              answer.length === correct.length &&
              answer.every(val => correct.includes(val))
            );
          }
          // Single choice esetén
          return answer === (correct[0] ?? null);
        }
        if (question.type === 'text') {
          const userVal = (answer || '').trim().toLowerCase();
          const def = (question.default_answer || '').trim().toLowerCase();
          return userVal === def;
        }
        return false;
      }
  
      // Felhasználó válaszának megjelenítése
      function displayAnswer(question, answer) {
        if (question.type === 'multiple_choice') {
          return (Array.isArray(answer) ? answer : [])
            .map(idx => question.options[idx] || '')
            .join(', ');
        }
        if (question.type === 'single_choice') {
          return question.options[answer] ?? '';
        }
        return answer;
      }
  
      // Helyes válasz megjelenítése
      function displayCorrect(question) {
        if (question.type === 'multiple_choice') {
          const correct = Array.isArray(question.correctAnswers)
            ? question.correctAnswers
            : [];
          return correct.map(idx => question.options[idx] || '').join(', ');
        }
        if (question.type === 'single_choice') {
          const correct = Array.isArray(question.correctAnswers)
            ? question.correctAnswers
            : [];
          return question.options[correct[0]] || '';
        }
        return question.default_answer || '';
      }
  
      return { userAnswers, isCorrect, displayAnswer, displayCorrect };
    },
  };
  </script>
  
  <style scoped>
  /* Zöld pipa és piros X stílusai már be vannak építve a Tailwind osztályokkal */
  </style>