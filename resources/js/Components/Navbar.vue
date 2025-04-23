<template>
  <nav class="bg-gray-900 text-white shadow-lg border-b-4 border-blue-500">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo -->
      <Link :href="route('dashboard')" class="flex items-center space-x-3">
      <img src="@/Layouts/logo.jpg" alt="Triviaverse Logo" class="h-10 rounded-full shadow-md">
      <span class="text-2xl font-bold text-blue-400 hover:text-blue-500 transition">Triviaverse</span>
      </Link>

      <!-- Asztali menü -->
      <ul class="hidden md:flex space-x-6 text-lg items-center">
        <li v-if="userRole !== 'student'">
          <Link :href="route('quizzes.create')" class="hover:text-blue-400 transition">Kvíz létrehozása</Link>
        </li>
        <li>
          <Link :href="route('quizzes.index')" class="hover:text-blue-400 transition">Kvízek</Link>
        </li>
        <li v-if="userRole === 'admin'">
          <Link :href="route('admin.index')" class="hover:text-blue-400 transition">Admin felület</Link>
        </li>
        <!-- Profil ikon asztali nézetben -->
        <li>
          <div class="relative" v-outside="closeDropdown">
            <button @click="toggleDropdown" class="focus:outline-none">
              <img v-if="userProfileImage" :src="userProfileImage" alt="Profilkép"
                class="w-10 h-10 rounded-full border-2 border-blue-500 shadow-lg" />
              <UserIcon v-else class="w-10 h-10 text-gray-400 border-2 border-blue-500 rounded-full p-1 shadow-lg" />
            </button>
            <div v-if="showDropdown"
              class="absolute right-0 mt-2 w-48 bg-gray-800 text-white shadow-lg rounded-lg overflow-hidden z-50">
              <Link :href="route('profile.edit')" class="block px-4 py-2 hover:bg-gray-600">Profilom</Link>
              <button @click="logout"
                class="w-full text-left px-4 py-2 hover:bg-red-500 bg-red-600">Kijelentkezés</button>
            </div>
          </div>
        </li>
      </ul>

      <!-- Mobil hamburger -->
      <button class="md:hidden focus:outline-none" @click="mobileOpen = !mobileOpen">
        <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Mobil menü -->
    <div v-if="mobileOpen" class="md:hidden bg-gray-800 border-t border-gray-700">
      <ul class="px-6 py-4 space-y-4">
        <li v-if="userRole !== 'student'">
          <Link @click="mobileOpen = false" :href="route('quizzes.create')"
            class="block hover:text-blue-400 transition">Kvíz létrehozása</Link>
        </li>
        <li>
          <Link @click="mobileOpen = false" :href="route('quizzes.index')" class="block hover:text-blue-400 transition">
          Kvízek</Link>
        </li>
        <li v-if="userRole === 'admin'">
          <Link @click="mobileOpen = false" :href="route('admin.index')" class="block hover:text-blue-400 transition">
          Admin felület</Link>
        </li>
        <li>
          <Link @click="mobileOpen = false" :href="route('profile.edit')" class="block hover:text-blue-400 transition">
          Profilom</Link>
        </li>
        <li>
          <button @click="logout"
            class="w-full text-left text-red-400 hover:text-red-700 transition">Kijelentkezés</button>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { User as UserIcon } from 'lucide-vue-next';

export default {
  components: { Link, UserIcon },
  props: {
    user: { type: Object, required: true },
  },
  setup(props) {
    const mobileOpen = ref(false);
    const showDropdown = ref(false);

    const userRole = computed(() => props.user?.role || 'guest');
    const userProfileImage = computed(() => props.user?.profilePicture || null);

    function toggleDropdown() {
      showDropdown.value = !showDropdown.value;
    }
    function closeDropdown() {
      showDropdown.value = false;
    }
    function logout() {
      router.post(route('logout'));
    }

    return {
      mobileOpen,
      showDropdown,
      userRole,
      userProfileImage,
      toggleDropdown,
      closeDropdown,
      logout,
    };
  },
  directives: {
    // Bezárja a dropdown-t, ha a komponenselemen kívül kattintanak
    outside: {
      beforeMount(el, binding) {
        el.clickOutsideEvent = e => {
          if (!(el === e.target || el.contains(e.target))) {
            binding.value();
          }
        };
        document.addEventListener('click', el.clickOutsideEvent);
      },
      unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
      },
    },
  },
};
</script>
