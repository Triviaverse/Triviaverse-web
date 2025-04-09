<template>
    <Navbar :user="user" />
    <div class="p-8 bg-gray-900 min-h-screen text-white">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-blue-400 mb-6">{{ quiz.title }}</h1>
            <p class="text-gray-400 mb-4">{{ quiz.description }}</p>
            <p class="text-sm text-gray-500 mb-6">
                ⏳ Időlimit: {{ quiz.time_limit ? quiz.time_limit + ' perc' : 'Nincs időkorlát' }}
            </p>

            <form @submit.prevent="submitQuiz">
                <div v-for="(question, index) in quiz.questions" :key="index" class="mb-6">
                    <h2 class="text-lg font-semibold text-blue-300">{{ index + 1 }}. {{ question.question_text }}</h2>
                    <div v-if="question.type === 'multiple_choice'" class="mt-2">
                        <div v-for="(option, optIndex) in question.options" :key="optIndex"
                            class="flex items-center mb-2">
                            <input type="checkbox" :id="`question-${index}-option-${optIndex}`" :value="option"
                                v-model="answers[index]" class="mr-2" />
                            <label :for="`question-${index}-option-${optIndex}`" class="text-gray-400">{{ option
                                }}</label>
                        </div>
                    </div>
                    <div v-else class="mt-2">
                        <input type="text" v-model="answers[index]" class="w-full bg-gray-800 text-white p-2 rounded-lg"
                            placeholder="Írd be a válaszod..." />
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-500 text-white px-5 py-3 rounded-lg shadow-md hover:bg-green-600 transition">
                    Beküldés
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import Navbar from "@/Components/Navbar.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        Navbar,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        quiz: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            answers: [],
        };
    },
    methods: {
        submitQuiz() {
            router.post(route("quizzes.submitAnswer", { id: this.quiz.id }), {
                answers: this.answers,
            });
        },
    },
    mounted() {
        console.log(this.quiz);
    },
};
</script>

<style scoped>
input[type="checkbox"] {
    accent-color: #3b82f6;
}
</style>
