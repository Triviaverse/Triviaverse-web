<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { X } from 'lucide-vue-next'; 

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// Aktív tab követése
const activeTab = ref('login');

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Bejelentkezés - Triviaverse" />

    <div class="flex items-center justify-center min-h-screen bg-gray-900 text-white">
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

            <!-- Bejelentkezés -->
            <div v-if="activeTab === 'login'">
                <h2 class="text-2xl font-bold text-center mb-6">Bejelentkezés a <span class="text-blue-400">Triviaverse</span>-be</h2>
                
                <div v-if="status" class="mb-4 text-sm font-medium text-green-400">
                    {{ status }}
                </div>
                
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" class="text-gray-300" />
                        <TextInput 
                            id="email" type="email"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.email" required autofocus autocomplete="username" 
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Jelszó" class="text-gray-300" />
                        <TextInput 
                            id="password" type="password"
                            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                            v-model="form.password" required autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" class="text-blue-400" />
                            <span class="ml-2 text-sm text-gray-300">Emlékezz rám</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-400 hover:underline">
                            Elfelejtetted a jelszavad?
                        </Link>
                        <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105">
                            Bejelentkezés
                        </button>
                    </div>
                </form>
            </div>

            <!-- Regisztráció (átirányítás a regisztrációs oldalra) -->
            <div v-if="activeTab === 'register'" class="text-center">
                <h2 class="text-2xl font-bold text-center mb-6">Regisztráció a <span class="text-blue-400">Triviaverse</span>-re</h2>
                <p class="text-gray-300 mb-6">Még nincs fiókod? Kattints az alábbi gombra, és regisztrálj most!</p>
                <Link 
                    :href="route('register')"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105"
                >
                    Regisztráció
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
