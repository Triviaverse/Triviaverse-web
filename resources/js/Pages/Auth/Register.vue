<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { X } from 'lucide-vue-next';

// Aktív tab követése
const activeTab = ref('register');

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
                    @click="activeTab = 'login'"
                    :class="activeTab === 'login' ? 'border-blue-500 text-blue-400' : 'text-gray-400'"
                    class="w-1/2 py-2 text-lg font-semibold text-center border-b-2 transition"
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
                        <InputLabel for="name" value="Név" class="text-gray-300" />
                        <TextInput 
                            id="name" type="text"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.name" required autofocus autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" class="text-gray-300" />
                        <TextInput 
                            id="email" type="email"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.email" required autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Jelszó" class="text-gray-300" />
                        <TextInput 
                            id="password" type="password"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.password" required autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Jelszó megerősítése" class="text-gray-300" />
                        <TextInput 
                            id="password_confirmation" type="password"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.password_confirmation" required autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="role" value="Szerepkör kiválasztása" class="text-gray-300" />
                        <select 
                            id="role" v-model="form.role" 
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                        >
                            <option value="student">Diák</option>
                            <option value="teacher">Tanár</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
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

            <!-- Bejelentkezés (átirányítás a bejelentkezési oldalra) -->
            <div v-if="activeTab === 'login'" class="text-center">
                <h2 class="text-2xl font-bold text-center mb-6">Bejelentkezés a <span class="text-blue-400">Triviaverse</span>-be</h2>
                <p class="text-gray-300 mb-6">Már van fiókod? Kattints az alábbi gombra a bejelentkezéshez!</p>
                <Link 
                    :href="route('login')"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105"
                >
                    Bejelentkezés
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
body {
  font-family: 'Arial', sans-serif;
}
</style>
