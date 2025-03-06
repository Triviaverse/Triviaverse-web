<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-900 text-white">
        <div class="bg-gray-800 text-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <Head title="Elfelejtett jelszó - Triviaverse" />

            <h2 class="text-2xl font-bold text-center mb-4">
                Elfelejtetted a jelszavad?
            </h2>

            <p class="text-gray-300 text-center mb-6">
                Ne aggódj! Add meg az email címed, és küldünk egy hivatkozást,
                amelyen keresztül visszaállíthatod a jelszavad.
            </p>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-400 text-center">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email cím" class="text-gray-300" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring focus:ring-blue-400"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-6 flex items-center justify-center">
                    <button
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition transform hover:scale-105"                    >
                        Küldés
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
