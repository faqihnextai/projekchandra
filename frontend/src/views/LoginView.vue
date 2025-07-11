<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'; // Pastikan ini diimport dengan benar

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleLogin = async () => {
    errorMessage.value = ''; // Reset error message

    // --- DEBUGGING: Log data yang akan dikirim ---
    console.log('--- Memulai Proses Login ---');
    console.log('Email yang dimasukkan:', email.value);
    console.log('Password yang dimasukkan:', password.value);
    console.log('Mengirim POST request ke: http://127.0.0.1:8000/api/login');

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', { // Sesuaikan URL Laravelmu
            email: email.value,
            password: password.value,
        });

        // --- DEBUGGING: Log respons sukses ---
        console.log('Respons sukses dari server:', response);
        console.log('Data respons:', response.data);
        console.log('Access Token diterima:', response.data.access_token);


        // Simpan token di localStorage
        localStorage.setItem('access_token', response.data.access_token);
        // Set default Authorization header untuk Axios agar setiap request selanjutnya otomatis menyertakan token
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;


        // Redirect ke halaman setelah login (misal: Dashboard)
        console.log('Login berhasil! Mengalihkan ke /dashboard...');
        router.push('/dashboard');
    } catch (error) {
        // --- DEBUGGING: Log error yang terjadi ---
        console.error('--- Terjadi Kesalahan Saat Login ---');
        console.error('Objek Error:', error);

        if (error.response) {
            // Error dari server (misal: 404, 422, 500)
            console.error('Error Response Status:', error.response.status);
            console.error('Error Response Data:', error.response.data);
            console.error('Error Response Headers:', error.response.headers);

            if (error.response.data && error.response.data.message) {
                errorMessage.value = error.response.data.message;
            } else if (error.response.data && error.response.data.errors) {
                // Handle validation errors from Laravel (misal: email tidak valid)
                const errors = error.response.data.errors;
                errorMessage.value = Object.values(errors).flat().join('\n');
            } else {
                // Jika tidak ada pesan spesifik, tampilkan status code
                errorMessage.value = `Terjadi kesalahan ${error.response.status}. Coba lagi.`;
            }
        } else if (error.request) {
            // Permintaan dibuat tapi tidak ada respons diterima (misal: server mati, koneksi terputus)
            console.error('Tidak ada respons dari server. Request:', error.request);
            errorMessage.value = 'Tidak ada respons dari server. Periksa koneksi internet atau pastikan server backend berjalan.';
        } else {
            // Ada yang salah saat menyiapkan permintaan (error di kode frontend)
            console.error('Error saat menyiapkan request:', error.message);
            errorMessage.value = 'Terjadi kesalahan internal. Silakan coba lagi.';
        }
    } finally {
        console.log('--- Proses Login Selesai ---');
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
/* Styling yang sama seperti sebelumnya */
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
    white-space: pre-wrap; /* Agar newline di pesan error bisa tampil */
}
</style>
