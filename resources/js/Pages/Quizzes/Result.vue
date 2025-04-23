<template>
    <Navbar v-if="user" :user="user" />
    <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
      <div class="w-full max-w-3xl bg-gray-800 p-6 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-blue-400">Eredmények</h1>
  
        <!-- Eredmény fejléce -->
        <div class="flex items-baseline mb-6 gap-4">
          <p class="text-xl">
            Eredmény:
            <span class="font-semibold">{{ displayScore }}%</span>
          </p>
          <!-- ha van override, jelöljük -->
          <span v-if="quizResult.is_overridden" class="text-sm text-yellow-300">
            (Felülvizsgált)
          </span>
        </div>
  
        <!-- Kérdések listája -->
        <div
          v-for="(question, i) in quiz.questions"
          :key="question.id || i"
          class="mb-6"
        >
          <p class="font-semibold text-gray-200 mb-1">
            {{ i + 1 }}. {{ question.question_text }}
          </p>
          <div class="flex items-center mb-2 gap-2">
            <span class="mr-2">Válaszod:</span>
            <span class="italic">{{ displayAnswer(question, userAnswers[i]) }}</span>
            <span v-if="currentCorrect[i]" class="ml-3 text-green-400 text-2xl">✓</span>
            <span v-else class="ml-3 text-red-500 text-2xl">✗</span>
  
            <!-- tanár/admin: felülírható -->
            <label
              v-if="isTeacher"
              class="ml-auto flex items-center gap-1 text-sm cursor-pointer"
            >
              <input
                type="checkbox"
                v-model="currentCorrect[i]"
                class="accent-yellow-300"
              />
              Helyes
            </label>
          </div>
          <p v-if="!currentCorrect[i]" class="text-gray-400">
            Helyes válasz:
            <span class="font-semibold">{{ displayCorrect(question) }}</span>
          </p>
        </div>
  
        <!-- Műveletek gombok -->
        <div class="text-center mt-8 flex justify-center gap-4">
          <Link
            href="/quizzes"
            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition"
          >
            Vissza a kvízekhez
          </Link>
  
          <button
            v-if="isTeacher"
            @click="saveReview"
            :disabled="saving"
            class="bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg hover:bg-yellow-600 transition"
          >
            Mentés
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from '@/Components/Navbar.vue'
  import { Link, router } from '@inertiajs/vue3'
  import { ref, computed } from 'vue'
  
  export default {
    components: { Navbar, Link },
    props: {
      user: Object,
      quiz: Object,
      quizAttempt: Object,
      quizResult: Object,
    },
    setup(props) {
      const isTeacher = ['teacher', 'admin'].includes(props.user.role)
  
      // 1) felhasználói válaszok
      const raw = Array.isArray(props.quizAttempt?.answers)
        ? props.quizAttempt.answers
        : []
      const userAnswers = ref(
        props.quiz.questions.map((q, i) =>
          raw[i] != null
            ? raw[i]
            : q.type === 'multiple_choice'
            ? []
            : q.type === 'single_choice'
            ? null
            : ''
        )
      )
  
      // 2) correct override-ok
      const currentCorrect = ref(
        props.quiz.questions.map((q, i) => {
          const ans = userAnswers.value[i]
          if (q.type === 'multiple_choice') {
            const ok = q.correctAnswers || []
            return Array.isArray(ans) && ans.length === ok.length && ans.every(v => ok.includes(v))
          }
          if (q.type === 'single_choice') {
            return ans === (q.correctAnswers?.[0] ?? null)
          }
          const u = (ans || '').toString().trim().toLowerCase()
          const c = (q.default_answer || '').toString().trim().toLowerCase()
          return u !== '' && c !== '' && u === c
        })
      )
  
      // 3) új százalék az override-okból
      const displayScore = computed(() => {
        const total = props.quiz.questions.length || 1
        const correctCnt = currentCorrect.value.filter(v => v).length
        return Math.round((correctCnt / total) * 100)
      })
  
      // 4) mentés
      const saving = ref(false)
      function saveReview() {
        saving.value = true
        router.post(
          route('quizzes.review', {
            quiz: props.quiz.id,
            attempt: props.quizAttempt.id,
          }),
          { overrides: currentCorrect.value },
          { onFinish: () => (saving.value = false) }
        )
      }
  
      // segédfüggvények
      function displayAnswer(question, answer) {
        if (question.type === 'multiple_choice') {
          const arr = Array.isArray(answer) ? answer : []
          return arr.map(idx => question.options[idx] || '').join(', ')
        }
        if (question.type === 'single_choice') {
          return question.options[answer] || ''
        }
        return answer || ''
      }
      function displayCorrect(question) {
        if (question.type === 'multiple_choice') {
          return (question.correctAnswers || [])
            .map(idx => question.options[idx] || '')
            .join(', ')
        }
        if (question.type === 'single_choice') {
          return question.options[question.correctAnswers?.[0]] || ''
        }
        return question.default_answer || ''
      }
  
      return {
        isTeacher,
        userAnswers,
        currentCorrect,
        displayScore,
        saveReview,
        saving,
        displayAnswer,
        displayCorrect,
      }
    },
  }
  </script>
  
  <style scoped>
  /* a Tailwind osztályok már elegendőek */
  </style>
  