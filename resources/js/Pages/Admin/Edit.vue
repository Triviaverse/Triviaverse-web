<script>
import { Head, Link } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'

export default {
  name: 'AdminEdit',
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  components: {
    Head,
    Link,
    Navbar,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.user.name,
      }),
    }
  },
  methods: {
    submit() {
      this.form.put(route('admin.update', this.user.id), {
        onSuccess: () => {
            
        },
      })
    },
  },
}
</script>

<template>
  <Navbar :user="user" />
  <Head title="Felhasználó szerkesztése — Triviaverse" />

  <div class="p-8 bg-gray-900 min-h-screen text-white flex justify-center">
    <div class="w-full max-w-md bg-gray-800 rounded-xl shadow-lg p-6 space-y-6">
      <h1 class="text-2xl font-bold text-blue-400">Felhasználó szerkesztése</h1>
      <p class="text-gray-300">ID: {{ user.id }} — {{ user.email }}</p>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-gray-300 mb-1">Név</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
          />
          <div v-if="form.errors.name" class="text-red-400 text-sm mt-1">
            {{ form.errors.name }}
          </div>
        </div>

        <div class="flex justify-between gap-4 pt-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2 rounded-lg shadow-md transition"
          >
            Mentés
          </button>
          <Link
            :href="route('admin.index')"
            class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-2 rounded-lg shadow-md transition text-center"
          >
            Mégse
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>

</style>
