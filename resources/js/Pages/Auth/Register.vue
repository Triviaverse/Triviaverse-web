<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { User, Mail, Key, Eye, EyeOff, X } from 'lucide-vue-next'

const activeTab = ref('register')

// Ha a Bejelentkezés gombra kattintanak, átirányítjuk
const switchToLogin = () => router.visit(route('login'))

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'student',
})

// Jelszó láthatóság toggle
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

const togglePassword = () => showPassword.value = !showPassword.value
const toggleConfirm = () => showPasswordConfirm.value = !showPasswordConfirm.value

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
    <Head title="Regisztráció – Triviaverse" />

  <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4">
    <div class="bg-gray-800 text-white rounded-xl shadow-xl w-full max-w-md relative overflow-hidden">
      <!-- bezáró X -->
      <Link :href="route('welcome')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-200">
        <X class="w-6 h-6" />
      </Link>

      <!-- Tabok -->
      <div class="flex border-b border-gray-700">
        <button @click="switchToLogin"
                class="w-1/2 py-3 text-center font-medium text-gray-400 hover:text-gray-200">
          Bejelentkezés
        </button>
        <button @click="activeTab = 'register'"
                :class="activeTab==='register' ? 'border-b-2 border-blue-500 text-blue-400' : 'text-gray-400'"
                class="w-1/2 py-3 text-center font-medium hover:text-gray-200">
          Regisztráció
        </button>
      </div>

      <!-- Form -->
      <div class="p-6">
        <h2 class="text-2xl font-bold text-center mb-6">
          Csatlakozz a <span class="text-blue-400">Triviaverse</span>-hez!
        </h2>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Név -->
          <div class="relative">
            <label for="name" class="sr-only">Név</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <User class="w-5 h-5 text-gray-400" />
            </div>
            <input
              id="name" type="text"
              v-model="form.name"
              required autofocus autocomplete="name"
              placeholder="Név"
              class="w-full bg-gray-700 pl-10 pr-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>

          <!-- Email -->
          <div class="relative">
            <label for="email" class="sr-only">Email</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Mail class="w-5 h-5 text-gray-400" />
            </div>
            <input
              id="email" type="email"
              v-model="form.email"
              required autocomplete="username"
              placeholder="Email"
              class="w-full bg-gray-700 pl-10 pr-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>

          <!-- Jelszó -->
          <div class="relative">
            <label for="password" class="sr-only">Jelszó</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Key class="w-5 h-5 text-gray-400" />
            </div>
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="form.password"
              required autocomplete="new-password"
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
          </div>

          <!-- Jelszó megerősítés -->
          <div class="relative">
            <label for="password_confirmation" class="sr-only">Jelszó megerősítése</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Key class="w-5 h-5 text-gray-400" />
            </div>
            <input
              :type="showPasswordConfirm ? 'text' : 'password'"
              id="password_confirmation"
              v-model="form.password_confirmation"
              required autocomplete="new-password"
              placeholder="Jelszó megerősítése"
              class="w-full bg-gray-700 pl-10 pr-10 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            <button
              type="button"
              @click="toggleConfirm"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              <component :is="showPasswordConfirm ? EyeOff : Eye" class="w-5 h-5" />
            </button>
          </div>

          <!-- Szerepkör -->
          <div>
            <label for="role" class="block text-gray-300 mb-1">Szerepkör</label>
            <select
              id="role"
              v-model="form.role"
              class="w-full bg-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
              <option value="student">Diák</option>
              <option value="teacher">Tanár</option>
            </select>
          </div>

          <!-- Akciók -->
          <div class="flex items-center justify-between mt-6">
            <Link :href="route('login')" class="text-sm text-blue-400 hover:underline">
              Már van fiókod?
            </Link>
            <button
              type="submit"
              class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                     text-white px-6 py-2 rounded-xl shadow-lg transform hover:scale-105 transition"
            >
              Regisztráció
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
