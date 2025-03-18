<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { X } from 'lucide-vue-next';

const activeTab = ref('register');

const switchToLogin = () => {
    router.visit(route('login'));
};

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'student', 
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Regisztráció - Triviaverse" />

    <div class="flex items-center justify-center min-h-screen bg-gray-900 text-white relative">
        <div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-full max-w-md relative">
            
            <!-- "X" ikon a jobb felső sarokban -->
            <Link :href="route('welcome')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-200 transition">
                <X class="w-6 h-6 cursor-pointer" />
            </Link>

            <!-- Tab menü -->
            <div class="flex border-b border-gray-700 mb-6">
                <button 
                    @click="switchToLogin"
                    class="w-1/2 py-2 text-lg font-semibold text-center border-b-2 transition text-gray-400"
                >
                    Bejelentkezés
                </button>
                <button 
                    @click="activeTab = 'register'"
                    :class="activeTab === 'register' ? 'border-blue-500 text-blue-400' : 'text-gray-400'"
                    class="w-1/2 py-2 text-lg font-semibold text-center border-b-2 transition"
                >
                    Regisztráció
                </button>
            </div>

            <!-- Regisztrációs űrlap -->
            <div v-if="activeTab === 'register'">
                <h2 class="text-2xl font-bold text-center mb-6">Csatlakozz a <span class="text-blue-400">Triviaverse</span>-hez!</h2>

                <form @submit.prevent="submit">
                    <div>
                        <label for="name" class="text-gray-300">Név</label>
                        <input 
                            id="name" type="text"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.name" required autofocus autocomplete="name"
                        />
                    </div>

                    <div class="mt-4">
                        <label for="email" class="text-gray-300">Email</label>
                        <input 
                            id="email" type="email"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.email" required autocomplete="username"
                        />
                    </div>

                    <div class="mt-4">
                        <label for="password" class="text-gray-300">Jelszó</label>
                        <input 
                            id="password" type="password"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.password" required autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation" class="text-gray-300">Jelszó megerősítése</label>
                        <input 
                            id="password_confirmation" type="password"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.password_confirmation" required autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <label for="role" class="text-gray-300">Szerepkör kiválasztása</label>
                        <select 
                            id="role" v-model="form.role" 
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                        >
                            <option value="student">Diák</option>
                            <option value="teacher">Tanár</option>
                        </select>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <Link 
                            :href="route('login')"
                            class="text-sm text-blue-400 hover:underline cursor-pointer"
                        >
                            Már van fiókod?
                        </Link>
                        <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105">
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