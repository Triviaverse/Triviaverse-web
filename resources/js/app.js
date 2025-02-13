import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createRouter, createWebHistory } from 'vue-router';

import App from '@/Pages/App.vue';
import Kerdesek from '@/components/Kerdesek.vue';
import Bejelentkezes from '@/components/Bejelentkezes.vue';
import Register from '@/components/Register.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const routes = [
  { path: '/kerdesek', component: Kerdesek },
  { path: '/bejelentkezes', component: Bejelentkezes },
  { path: '/register', component: Register }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue', { eager: true }),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(router) 
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
