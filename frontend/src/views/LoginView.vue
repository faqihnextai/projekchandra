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
/* Styling untuk tema Cyberpunk */
.login-container {
    /* Default untuk mobile dan ukuran kecil */
    width: 90%; /* Menggunakan persentase untuk lebar adaptif */
    max-width: 350px; /* Batas lebar maksimum untuk mobile */
    margin: 30px auto; /* Margin atas/bawah lebih kecil untuk mobile */
    padding: 20px;
    background-color: rgba(10, 10, 10, 0.9);
    border: 1px solid #00ffff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.5), 0 0 30px rgba(0, 255, 255, 0.2);
    text-align: center;
    color: #e0e0e0;
    font-family: 'Inter', sans-serif;
    box-sizing: border-box; /* Penting untuk responsivitas */
}

/* Untuk ukuran tablet dan laptop kecil (misal: lebar > 768px) */
@media (min-width: 768px) {
    .login-container {
        max-width: 500px; /* Lebih besar untuk tablet/laptop kecil */
        padding: 40px; /* Padding lebih besar */
        margin: 80px auto; /* Margin atas/bawah lebih besar */
        box-shadow: 0 0 25px rgba(0, 255, 255, 0.6), 0 0 50px rgba(0, 255, 255, 0.3);
    }
}

/* Untuk ukuran laptop besar dan desktop (misal: lebar > 1024px) */
@media (min-width: 1024px) {
    .login-container {
        max-width: 600px; /* Lebih besar untuk desktop */
        padding: 50px; /* Padding lebih besar lagi */
        margin: 100px auto; /* Margin atas/bawah lebih besar lagi */
        box-shadow: 0 0 30px rgba(0, 255, 255, 0.7), 0 0 60px rgba(0, 255, 255, 0.4);
    }
}


h2 {
    color: #00ffff;
    text-shadow: 0 0 8px #00ffff;
    margin-bottom: 30px;
    font-size: 2.2em;
    letter-spacing: 2px;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #00aaff;
    text-shadow: 0 0 5px rgba(0, 170, 255, 0.5);
    font-size: 1.1em;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    background-color: #1a1a1a;
    border: 1px solid #00aaff;
    border-radius: 4px;
    box-sizing: border-box;
    color: #e0e0e0;
    font-size: 1em;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #00ffff;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
}

button {
    background-color: #00aaff;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.2em;
    margin-top: 25px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
    box-shadow: 0 0 10px rgba(0, 170, 255, 0.5);
}

button:hover {
    background-color: #00ffff;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.8), 0 0 30px rgba(0, 255, 255, 0.5);
}

.error-message {
    color: #ff0000;
    text-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    margin-top: 15px;
    font-size: 0.95em;
    white-space: pre-wrap;
}
</style>
