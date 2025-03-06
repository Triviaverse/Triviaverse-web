<script>
import { Link } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next'; 

export default {
  components: { Link, User },
  props: {
    user: Object,
  },
  data() {
    return {
      showDropdown: false,
    };
  },
  computed: {
    userRole() {
      return this.user?.role || "guest";
    },
    userProfileImage() {
      return this.user?.profilePicture || null;
    }
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    logout() {
      this.$inertia.post(route('logout'));
    }
  }
};
</script>

<template>
  <nav class="bg-gray-900 text-white py-4 shadow-lg border-b-4 border-blue-500">
    <div class="container mx-auto flex justify-between items-center px-6">
      
      <!-- Logo -->
      <Link :href="route('dashboard')" class="flex items-center space-x-3">
        <img src="@/Layouts/logo.jpg" alt="Triviaverse Logo" class="h-10 rounded-full shadow-md">
        <span class="text-2xl font-bold text-blue-400 hover:text-blue-500 transition">
          Triviaverse
        </span>
      </Link>

      <!-- Menü -->
      <ul class="hidden md:flex space-x-6 text-lg">
        <li v-if="userRole !== 'student'">
          <Link 
            :href="route('quizzes.create')" 
            class="hover:text-blue-400 transition duration-300"
          >
            Kvíz létrehozása
          </Link>
        </li>
        <li>
          <Link 
            :href="route('quizzes.index')" 
            class="hover:text-blue-400 transition duration-300"
          >
            Kvízek
          </Link>
        </li>
        <li v-if="userRole === 'admin'">
          <Link 
            :href="route('quizzes.manage')" 
            class="hover:text-blue-400 transition duration-300"
          >
            Kvízek kezelése
          </Link>
        </li>
      </ul>

      <!-- Felhasználói menü -->
      <div class="relative">
        <button @click="toggleDropdown" class="flex items-center focus:outline-none">
          <img 
            v-if="userProfileImage"
            :src="userProfileImage"
            alt="Profilkép"
            class="w-10 h-10 rounded-full border-2 border-blue-500 shadow-lg cursor-pointer">
          <User v-else class="w-10 h-10 text-gray-400 border-2 border-blue-500 rounded-full p-1 shadow-lg cursor-pointer" />
        </button>
        
        <!-- Dropdown Menü -->
        <div v-if="showDropdown" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white shadow-lg rounded-lg overflow-hidden z-50">
          <Link :href="route('profile.edit')" class="block px-4 py-2 hover:bg-gray-600">Profilom</Link>
          <button @click="logout" class="block w-full text-left px-4 py-2 bg-red-600 hover:bg-red-400">Kijelentkezés</button>
        </div>
      </div>
    </div>
  </nav>
</template>