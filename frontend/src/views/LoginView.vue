<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleLogin = async () => {
    errorMessage.value = ''; // Reset error message

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', { // Sesuaikan URL Laravelmu
            email: email.value,
            password: password.value,
        });

        // Simpan token di localStorage atau Vuex/Pinia (kalau pakai)
        localStorage.setItem('access_token', response.data.access_token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;


        // Redirect ke halaman setelah login (misal: Dashboard)
        router.push('/dashboard'); // Nanti kita buat rute /dashboard
    } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage.value = error.response.data.message;
        } else if (error.response && error.response.data && error.response.data.errors) {
            // Handle validation errors from Laravel
            const errors = error.response.data.errors;
            errorMessage.value = Object.values(errors).flat().join('\n');
        }
        else {
            errorMessage.value = 'Terjadi kesalahan. Coba lagi.';
        }
    }
};
</script>

<template>
    <div class="login-container">
        <h2>Login</h2>
        <form @submit.prevent="handleLogin">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" v-model="password" required>
            </div>
            <button type="submit">Login</button>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<style scoped>
.login-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}
.form-group {
    margin-bottom: 15px;
    text-align: left;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* Ensures padding doesn't increase width */
}
button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
}
button:hover {
    background-color: #45a049;
}
.error-message {
    color: red;
    margin-top: 10px;
}
</style>