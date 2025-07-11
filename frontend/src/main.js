// src/main.js atau file utama Vue.js kamu
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios'; // Pastikan axios diimport

// Konfigurasi Axios untuk mengirim kredensial (cookies)
axios.defaults.withCredentials = true;

// URL Laravel Sanctum CSRF cookie
axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie').then(() => {
    createApp(App).use(router).mount('#app');
});