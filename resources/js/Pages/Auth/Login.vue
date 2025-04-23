<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Mail, Key, Eye, EyeOff, X } from 'lucide-vue-next'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const activeTab = ref('login')
const switchToRegister = () => router.visit(route('register'))

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const showPassword = ref(false)
const togglePassword = () => showPassword.value = !showPassword.value

// Módosított submit: onSuccess átdob a dashboardra
const submit = () => {
  form.post(route('login'), {
    onSuccess: () => {
      router.visit(route('dashboard'))
    },
    onFinish: () => {
      form.reset('password')
    },
  })
}
</script>

<template>
  <Head title="Bejelentkezés – Triviaverse" />

  <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4">
    <div class="bg-gray-800 text-white rounded-xl shadow-xl w-full max-w-md relative overflow-hidden">
      <!-- bezáró X -->
      <Link :href="route('welcome')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-200">
        <X class="w-6 h-6" />
      </Link>

      <!-- Tabok -->
      <div class="flex border-b border-gray-700">
        <button
          @click="activeTab = 'login'"
          :class="activeTab==='login' ? 'border-b-2 border-blue-500 text-blue-400' : 'text-gray-400'"
          class="w-1/2 py-3 text-center font-medium hover:text-gray-200"
        >
          Bejelentkezés
        </button>
        <button
          @click="switchToRegister"
          class="w-1/2 py-3 text-center font-medium text-gray-400 hover:text-gray-200"
        >
          Regisztráció
        </button>
      </div>

      <!-- Login form -->
      <div class="p-6">
        <h2 class="text-2xl font-bold text-center mb-6">
          Jelentkezz be a <span class="text-blue-400">Triviaverse</span>-be
        </h2>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-400">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Email -->
          <div class="relative">
            <label for="email" class="sr-only">Email</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Mail class="w-5 h-5 text-gray-400" />
            </div>
            <input
              id="email" type="email"
              v-model="form.email"
              required autofocus autocomplete="username"
              placeholder="Email"
              class="w-full bg-gray-700 pl-10 pr-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
          </div>

          <!-- Password -->
          <div class="relative">
            <label for="password" class="sr-only">Jelszó</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Key class="w-5 h-5 text-gray-400" />
            </div>
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="form.password"
              required autocomplete="current-password"
              placeholder="Jelszó"
              class="w-full bg-gray-700 pl-10 pr-10 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            <button
              type="button"
              @click="togglePassword"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              <component :is="showPassword ? EyeOff : Eye" class="w-5 h-5" />
            </button>
            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
          </div>

          <!-- Remember me -->
          <div class="flex items-center">
            <label class="flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="form.remember"
                class="h-4 w-4 text-blue-500 bg-gray-700 border-gray-600 rounded focus:ring-blue-400"
              />
              <span class="ml-2 text-gray-300">Emlékezz rám</span>
            </label>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between mt-6">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="text-sm text-blue-400 hover:underline"
            >
              Elfelejtetted a jelszavad?
            </Link>
            <button
              type="submit"
              class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                     text-white px-6 py-2 rounded-xl shadow-lg transform hover:scale-105 transition"
            >
              Bejelentkezés
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
body {
  font-family: 'Arial', sans-serif;
}
</style>
