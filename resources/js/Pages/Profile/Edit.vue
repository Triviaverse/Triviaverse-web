<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import { ref, watch } from 'vue'
import { Camera, User, Mail, Lock, Key } from 'lucide-vue-next'

// Bejelentkezett user lekérése
const page = usePage()
const user = page.props.auth.user

// Profil adat űrlap
const profileForm = useForm({
  name:            user.name,
  email:           user.email,
  profile_picture: null,
})

// Jelszó változtató űrlap
const passwordForm = useForm({
  current_password: '',
  password:          '',
  password_confirmation: '',
})

const deleting = ref(false)

// Élő kép előnézet URL
const previewUrl = ref(user.profile_picture_url || null)
watch(() => profileForm.profile_picture, file => {
  if (file instanceof File) {
    const reader = new FileReader()
    reader.onload = e => previewUrl.value = e.target.result
    reader.readAsDataURL(file)
  }
})

// Profiladatok mentése
function submitProfile() {
  profileForm.post(route('profile.update'), {
    preserveScroll: true,
    forceFormData:  true,
    onSuccess: () => {
      profileForm.reset('profile_picture')
      previewUrl.value = page.props.auth.user.profile_picture_url || null
    },
  })
}

// Jelszó mentése
function submitPassword() {
  passwordForm.post(route('profile.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset('current_password','password','password_confirmation')
    },
  })
}

// Fiók törlése
function destroyAccount() {
  if (!confirm('Biztosan véglegesen törölni szeretnéd a fiókodat?')) return
  deleting.value = true
  router.delete(route('profile.destroy'))
}
</script>

<template>
  <Navbar v-if="user" :user="user" />
  <Head title="Profil szerkesztése — Triviaverse" />

  <div class="p-8 bg-gray-900 min-h-screen text-white">
    <div class="max-w-3xl mx-auto space-y-8">

      <!-- Profilkép -->
      <section class="bg-gray-800 border border-gray-700 p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Profilkép</h2>
        <div class="flex items-center gap-6">
          <div class="relative w-24 h-24">
            <img
              v-if="previewUrl"
              :src="previewUrl"
              alt="Profilkép"
              class="w-24 h-24 rounded-full object-cover border-2 border-blue-500"
            />
            <div
              v-else
              class="w-24 h-24 rounded-full bg-gray-700 border-2 border-blue-500 flex items-center justify-center"
            >
              <User class="w-12 h-12 text-gray-400"/>
            </div>
            <label
              class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 p-1 rounded-full cursor-pointer transition"
              title="Kép választása"
            >
              <Camera class="w-5 h-5 text-white"/>
              <input
                type="file"
                accept="image/*"
                class="hidden"
                @change="e => profileForm.profile_picture = e.target.files[0]"
              />
            </label>
          </div>
          <p class="text-gray-400">Kattints az ikonra a kép feltöltéséhez</p>
        </div>
        <p v-if="profileForm.errors.profile_picture" class="text-red-400 mt-2 text-sm">
          {{ profileForm.errors.profile_picture }}
        </p>
      </section>

      <!-- Adatok mentése -->
      <section class="bg-gray-800 border border-gray-700 p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Adatok</h2>
        <form @submit.prevent="submitProfile" class="space-y-4">
          <div class="relative">
            <User class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
            <input
              v-model="profileForm.name"
              type="text"
              placeholder="Név"
              class="w-full bg-gray-700 border border-gray-600 pl-10 pr-4 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>
          <p v-if="profileForm.errors.name" class="text-red-400 text-sm">
            {{ profileForm.errors.name }}
          </p>

          <div class="relative">
            <Mail class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
            <input
              v-model="profileForm.email"
              type="email"
              placeholder="Email"
              class="w-full bg-gray-700 border border-gray-600 pl-10 pr-4 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>
          <p v-if="profileForm.errors.email" class="text-red-400 text-sm">
            {{ profileForm.errors.email }}
          </p>

          <button
            type="submit"
            :disabled="profileForm.processing"
            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 rounded-lg shadow-md transition"
          >
            Adatok mentése
          </button>
        </form>
      </section>

      <!-- Jelszó módosítása -->
      <section class="bg-gray-800 border border-gray-700 p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Jelszó módosítása</h2>
        <form @submit.prevent="submitPassword" class="space-y-4">
          <!-- rejtett username autocomplete miatt -->
          <input
            type="text"
            name="username"
            :value="user.email"
            autocomplete="username"
            class="sr-only"
            readonly
          />

          <div class="relative">
            <Lock class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
            <input
              v-model="passwordForm.current_password"
              type="password"
              placeholder="Jelenlegi jelszó"
              autocomplete="current-password"
              class="w-full bg-gray-700 border border-gray-600 pl-10 pr-4 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>
          <p v-if="passwordForm.errors.current_password" class="text-red-400 text-sm">
            {{ passwordForm.errors.current_password }}
          </p>

          <div class="relative">
            <Key class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
            <input
              v-model="passwordForm.password"
              type="password"
              placeholder="Új jelszó"
              autocomplete="new-password"
              class="w-full bg-gray-700 border border-gray-600 pl-10 pr-4 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>
          <p v-if="passwordForm.errors.password" class="text-red-400 text-sm">
            {{ passwordForm.errors.password }}
          </p>

          <div class="relative">
            <Key class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"/>
            <input
              v-model="passwordForm.password_confirmation"
              type="password"
              placeholder="Jelszó ismét"
              autocomplete="new-password"
              class="w-full bg-gray-700 border border-gray-600 pl-10 pr-4 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
            />
          </div>
          <p v-if="passwordForm.errors.password_confirmation" class="text-red-400 text-sm">
            {{ passwordForm.errors.password_confirmation }}
          </p>

          <button
            type="submit"
            :disabled="passwordForm.processing"
            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 rounded-lg shadow-md transition"
          >
            Jelszó mentése
          </button>
        </form>
      </section>

      <!-- Fiók törlése -->
      <section class="bg-gray-800 border border-red-600 p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-red-400">Fiók törlése</h2>
        <p class="text-gray-400 mb-4">
          Végleges művelet: a fiók és minden adat törlődik.
        </p>
        <button
          @click="destroyAccount"
          :disabled="deleting"
          class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md transition"
        >
          Fiók végleges törlése
        </button>
      </section>

    </div>
  </div>
</template>

<style scoped>
</style>
