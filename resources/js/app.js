import './bootstrap';

import { createApp } from 'vue'
window.Vue = require('vue');

import App from '@/App.vue';
import { createWebHistory, createRouter } from 'vue-router';
import { routes } from '@/routes';

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

import Notifications from '@kyvg/vue3-notification'

const app = createApp(App);
app.use(router);
app.use(Notifications);
app.mount('#app');