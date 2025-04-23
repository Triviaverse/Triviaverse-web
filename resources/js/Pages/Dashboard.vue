<template>
  <Navbar :user="user" />

  <div class="p-8 bg-gray-900 min-h-screen text-white">
    <div class="container mx-auto space-y-8">

      <!-- Fejlődés grafikon -->
      <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-blue-400">
          Fejlődésed a kitöltött kvízek alapján
        </h2>
        <Line
          :data="chartData"
          :options="chartOptions"
          class="max-h-60"
        />
      </div>

      <!-- Statisztikák -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="bg-blue-600 p-6 rounded-xl shadow-md">
          <h3 class="text-lg font-semibold">Összes kvíz</h3>
          <p class="text-4xl font-bold">{{ stats.totalQuizzes }}</p>
        </div>
        <div class="bg-green-600 p-6 rounded-xl shadow-md">
          <h3 class="text-lg font-semibold">Kitöltött kvízek</h3>
          <p class="text-4xl font-bold">{{ stats.completedQuizzes }}</p>
        </div>
        <div class="bg-yellow-600 p-6 rounded-xl shadow-md">
          <h3 class="text-lg font-semibold">Függőben lévő kvízek</h3>
          <p class="text-4xl font-bold">{{ stats.pendingQuizzes }}</p>
        </div>
      </div>

      <!-- Üdvözlés + Frissítés -->
      <div class="bg-gray-800 p-6 rounded-xl shadow-lg flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-semibold">Eredményeim, {{ user.name }}!</h1>
          <p class="text-gray-400">
            Szerepkör: <span class="font-bold text-blue-400">{{ user.role }}</span>
          </p>
        </div>
        <button @click="reload" class="bg-blue-500 px-5 py-3 rounded-lg hover:bg-blue-600 transition">
          🔄 Frissítés
        </button>
      </div>

      <!-- Saját eredmények -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div
          v-for="att in attempts"
          :key="att.id"
          :class="['p-6 rounded-xl shadow-md flex justify-between items-center', bgClass(att.quizResult.score_percentage)]"
        >
          <div>
            <h4 class="text-xl font-semibold">{{ att.quiz.title }}</h4>
            <p class="mt-2">
              Eredmény: <span class="font-bold">{{ att.quizResult.score_percentage }}%</span>
              <span
                v-if="att.quizResult.is_overridden"
                class="text-sm text-yellow-300"
              >(Felülvizsgált)</span>
            </p>
          </div>
          <button
            @click="viewResult(att.quiz.id, att.id)"
            class="bg-white text-gray-900 px-4 py-2 rounded hover:bg-gray-200 transition"
          >Megtekintés</button>
        </div>
      </div>

      <!-- Diákok eredményei -->
      <div v-if="studentResults.length" class="space-y-4">
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg flex justify-between items-center">
          <h2 class="text-2xl font-semibold">Diákok eredményei</h2>
          <button @click="reload" class="bg-blue-500 px-5 py-3 rounded-lg hover:bg-blue-600 transition">
            🔄 Frissítés
          </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div
            v-for="sr in studentResults"
            :key="sr.id"
            :class="['p-6 rounded-xl shadow-md flex justify-between items-center', bgClass(sr.quizResult.score_percentage)]"
          >
            <div>
              <h4 class="text-xl font-semibold">{{ sr.quiz.title }}</h4>
              <p class="text-gray-200">
                Diák: <span class="font-bold">{{ sr.student.name }}</span>
              </p>
              <p class="mt-1">
                <span class="font-bold">{{ sr.quizResult.score_percentage }}%</span>
                <span
                  v-if="sr.quizResult.is_overridden"
                  class="text-sm text-yellow-300"
                >(Felülvizsgált)</span>
              </p>
            </div>
            <button
              @click="viewResult(sr.quiz.id, sr.id)"
              class="bg-white text-gray-900 px-4 py-2 rounded hover:bg-gray-200 transition"
            >Megtekintés</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import { Line } from 'vue-chartjs'
import { Chart, registerables } from 'chart.js'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

Chart.register(...registerables)

const props = defineProps({
  user:           Object,
  stats:          Object,
  attempts:       Array,
  studentResults: { type: Array, default: () => [] },
})

function reload() {
  router.reload({ only: ['stats', 'attempts', 'studentResults'] })
}

function viewResult(quizId, attemptId) {
  router.get(route('quizzes.result', { quiz: quizId, attempt: attemptId }))
}

function bgClass(score) {
  if (score <= 25) return 'bg-red-600'
  if (score <= 50) return 'bg-orange-500'
  if (score <= 75) return 'bg-yellow-500'
  return 'bg-green-600'
}

// Grafikon adatok
const labels = computed(() => props.attempts.map(a => a.date))
const dataPoints = computed(() => props.attempts.map(a => a.quizResult.score_percentage))

const chartData = computed(() => ({
  labels: labels.value,
  datasets: [{
    label: 'Pontszámod (%)',
    data: dataPoints.value,
    tension: 0.4,
    borderWidth: 2,
    fill: false,
    borderColor: '#60A5FA', 
  }]
}))

const chartOptions = {
  responsive: true,
  scales: {
    y: { min: 0, max: 100 }
  },
}
</script>

<style scoped>

</style>
