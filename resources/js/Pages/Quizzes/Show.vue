<template>
    <Navbar v-if="user" :user="user" />
    <div class="relative p-8 bg-gray-900 min-h-screen text-white">
      <div class="max-w-3xl mx-auto bg-gray-800 p-6 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-blue-400">{{ quiz.title }}</h1>
        <p class="mb-6 text-gray-300">{{ quiz.description }}</p>
  
        <form v-if="!showResultPopup && canAttempt" @submit.prevent="submitQuiz">
          <div v-for="(question, i) in quiz.questions" :key="question.id || i" class="mb-6">
            <p class="font-semibold text-gray-200">{{ i + 1 }}. {{ question.question_text }}</p>
  
            <div v-if="question.type === 'multiple_choice'" class="mt-2 space-y-2">
              <label v-for="(opt, idx) in question.options" :key="idx" class="flex items-center gap-2">
                <input
                  type="checkbox"
                  :value="idx"
                  v-model="answers[i]"
                  class="accent-blue-500"
                />
                <span>{{ opt }}</span>
              </label>
            </div>
  
            <div v-if="question.type === 'single_choice'" class="mt-2 space-y-2">
              <label v-for="(opt, idx) in question.options" :key="idx" class="flex items-center gap-2">
                <input
                  type="radio"
                  :value="idx"
                  v-model="answers[i]"
                  class="accent-blue-500"
                />
                <span>{{ opt }}</span>
              </label>
            </div>
  
            <div v-if="question.type === 'text'" class="mt-2">
              <input
                type="text"
                v-model="answers[i]"
                class="w-full bg-gray-700 text-white p-2 rounded border border-gray-600 focus:ring focus:ring-blue-500"
              />
            </div>
          </div>
  
          <div class="mt-6">
            <button
              type="submit"
              class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition"
            >Beküldés</button>
          </div>
        </form>
  
        <div v-if="showResultPopup" class="text-center">
          <button
            @click="goToResult"
            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition"
          >Eredményem megtekintése</button>
        </div>
      </div>
  
      <div class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg">
        <span v-if="timeLeft === null">Nincs időkorlát</span>
        <span v-else>{{ formattedTime }}</span>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  import { router } from '@inertiajs/vue3';
  import Navbar from '@/Components/Navbar.vue';
  
  export default {
    components: { Navbar },
    props: {
      user: Object,
      quiz: Object,
      quizAttempt: { type: Object, default: null },
    },
    setup(props) {
      const isStudent = props.user.role === 'student';
      const hasAttempt = props.quizAttempt !== null;
      const canAttempt = !(isStudent && hasAttempt);
      const showResultPopup = ref(false);
  
      const answers = ref(
        props.quizAttempt
          ? JSON.parse(props.quizAttempt.answers)
          : props.quiz.questions.map(q => (q.type === 'multiple_choice' ? [] : q.type === 'single_choice' ? null : ''))
      );
  
      const timeLeft = ref(props.quiz.time_limit ? props.quiz.time_limit * 60 : null);
      let timer;
  
      const formattedTime = computed(() => {
        const m = Math.floor(timeLeft.value / 60);
        const s = timeLeft.value % 60;
        return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
      });
  
      function tick() {
        if (timeLeft.value > 0) {
          timeLeft.value--;
        } else {
          clearInterval(timer);
          if (canAttempt) submitQuiz();
        }
      }
  
      onMounted(() => {
        if (timeLeft.value !== null && canAttempt) {
          timer = setInterval(tick, 1000);
        }
      });
      onBeforeUnmount(() => clearInterval(timer));
  
      function submitQuiz() {
        clearInterval(timer);
        router.post(
          route('quizzes.submitAnswer', { id: props.quiz.id }),
          { answers: answers.value },
          {
            onSuccess: () => { showResultPopup.value = true; },
          }
        );
      }
  
      function goToResult() {
        router.get(route('quizzes.result', { quiz: props.quiz.id }), {}, { preserveState:false, preserveScroll:true });
      }
  
      return { canAttempt, answers, timeLeft, formattedTime, showResultPopup, submitQuiz, goToResult };
    },
  };
  </script>
  
  <style scoped>
  .slide-fade-enter-active,
  .slide-fade-leave-active { transition: all 0.3s ease; }
  .slide-fade-enter-from,
  .slide-fade-leave-to { opacity:0; transform: translateY(-10px); }
  .slide-fade-enter-to,
  .slide-fade-leave-from { opacity:1; transform: translateY(0); }
  </style>