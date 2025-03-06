<template>
    <Navbar v-if="user" :user="user" />
    <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
      <div class="w-full">
        <!-- Felhasználói üdvözlő rész -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-semibold">Üdv, {{ user.name }}!</h1>
            <p class="text-gray-400">Szerepkör: <span class="font-bold text-blue-400">{{ user.role }}</span></p>
          </div>
          <button 
            @click="refreshStats" 
            class="bg-blue-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
          >
            🔄 Statisztikák frissítése
          </button>
        </div>
  
        <!-- Statisztikák -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 text-center" v-if="stats">
          <div class="bg-blue-600 p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold">Összes kvíz</h2>
            <p class="text-4xl font-bold">{{ stats.totalQuizzes ?? 0 }}</p>
          </div>
          <div class="bg-green-600 p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold">Kitöltött kvízek</h2>
            <p class="text-4xl font-bold">{{ stats.completedQuizzes ?? 0 }}</p>
          </div>
          <div class="bg-yellow-600 p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold">Függőben lévő kvízek</h2>
            <p class="text-4xl font-bold">{{ stats.pendingQuizzes ?? 0 }}</p>
          </div>
        </div>
  
        <!-- Kvíz lista -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md" v-if="quizzes.length">
          <h2 class="text-2xl font-semibold mb-4 text-blue-400">Elérhető kvízek</h2>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
              <thead>
                <tr class="bg-gray-700 text-white">
                  <th class="p-4 text-left">Cím</th>
                  <th class="p-4 text-left">Állapot</th>
                  <th class="p-4 text-left">Műveletek</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="quiz in quizzes" 
                  :key="quiz.id" 
                  class="hover:bg-gray-600 transition"
                >
                  <td class="p-4 border-t border-gray-700">{{ quiz.title }}</td>
                  <td class="p-4 border-t border-gray-700">
                    <span 
                      :class="{ 
                        'text-green-400': quiz.status === 'completed', 
                        'text-blue-400': quiz.status === 'active' 
                      }"
                    >
                      {{ quiz.status === 'completed' ? 'Befejezett' : 'Aktív' }}
                    </span>
                  </td>
                  <td class="p-4 border-t border-gray-700">
                    <button 
                      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition shadow-md"
                    >
                      Megtekintés
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { router } from '@inertiajs/vue3';
  import Navbar from '@/Components/Navbar.vue';
  
  export default {
    components: {
      Navbar
    },
    props: {
      user: {
        type: Object,
        required: true,
      },
      stats: {
        type: Object,
        required: false,
        default: () => ({ totalQuizzes: 0, completedQuizzes: 0, pendingQuizzes: 0 })
      },
      quizzes: {
        type: Array,
        required: false,
        default: () => []
      }
    },
    methods: {
      refreshStats() {
        router.reload({ only: ['stats', 'quizzes'] });
      }
    }
  };
  </script>
  
  <style scoped>
  table th, table td {
    text-align: left;
  }
  </style>
  