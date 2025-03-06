<script>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next'; // Lucide ikonok
import Navbar from '@/Components/Navbar.vue';

export default {
    components: {
        Navbar
    },
};

const showingNavigationDropdown = ref(false);
</script>

<template>

  <div class="min-h-screen bg-gray-900 text-white">
    <Navbar/>
      <div class="container mx-auto flex justify-between items-center px-6">
        <!-- Profil + Dropdown Menü -->
        <div class="flex items-center space-x-6">
          <div class="relative">
            
            
            <!-- Lenyíló menü -->
            <div v-if="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-gray-700 text-white shadow-lg rounded-lg overflow-hidden z-50">
              <Link :href="route('profile.edit')" class="block px-4 py-2 hover:bg-gray-600">Profil</Link>
              <button @click="$inertia.post(route('logout'))" class="block w-full text-left px-4 py-2 hover:bg-gray-600">Kijelentkezés</button>
            </div>
          </div>
        </div>
      </div>
    <!-- Oldaltartalom -->
    <main class="container mx-auto py-6 px-6">
      <slot />
    </main>
  </div>
</template>
