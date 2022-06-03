import './bootstrap';

import { createApp } from 'vue'
window.Vue = require('vue');

import App from './App.vue';
import { createWebHistory, createRouter } from 'vue-router';
import { routes } from './routes';

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

createApp(App).use(router).mount('#app');