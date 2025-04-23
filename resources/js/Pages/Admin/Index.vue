<script>
import { Head, Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'

export default {
  props: {
    users: {
      type: Array,
      required: true,
    },
    auth: {
      type: Object,
      required: true,
    },
  },
  components: {
    Head,
    Link,
    Navbar,
  },
  methods: {
    confirmDelete(user) {
      if (!confirm(`Biztosan törölni szeretnéd a(z) ${user.name} felhasználót?`)) {
        return
      }
      this.$inertia.delete(route('admin.destroy', user.id), {
        preserveScroll: true,
      })
    },
  },
}
</script>

<template>
  <Navbar v-if="auth.user" :user="auth.user" />
  <Head title="Felhasználók kezelése" />

  <div class="p-8 bg-gray-900 min-h-screen text-white">
    <div class="max-w-5xl mx-auto space-y-6">
      <h1 class="text-3xl font-bold text-blue-400">Felhasználók kezelése</h1>

      <table class="w-full bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <thead class="bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-gray-300">#</th>
            <th class="px-6 py-3 text-left text-gray-300">Név</th>
            <th class="px-6 py-3 text-left text-gray-300">Email</th>
            <th class="px-6 py-3 text-center text-gray-300">Műveletek</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="border-b border-gray-700 hover:bg-gray-700 transition"
          >
            <td class="px-6 py-4">{{ user.id }}</td>
            <td class="px-6 py-4">{{ user.name }}</td>
            <td class="px-6 py-4">{{ user.email }}</td>
            <td class="px-6 py-4 flex justify-center gap-3">
              <Link
                :href="route('admin.edit', user.id)"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm transition"
              >
                Szerkesztés
              </Link>
              <button
                @click="confirmDelete(user)"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm transition"
              >
                Törlés
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Ha van pagination: 
      <div class="mt-4">
        <Pagination :links="users.links" />
      </div>
      -->
    </div>
  </div>
</template>

<style scoped>
/* Az egész táblázat sötét stílusú, a header világosabb szürke, a sorok hover-effekttel */
</style>
