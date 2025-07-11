<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const user = ref(null);
const router = useRouter();

const fetchUserData = async () => {
    const token = localStorage.getItem('access_token');
    if (!token) {
        router.push('/login'); // Redirect kalau belum login
        return;
    }

    // Set default header Authorization untuk setiap request
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    try {
        const response = await axios.get('http://127.0.0.1:8000/api/user'); // Sesuaikan URL Laravelmu
        user.value = response.data;
    } catch (error) {
        console.error('Error fetching user data:', error);
        localStorage.removeItem('access_token'); // Hapus token kadaluarsa
        router.push('/login'); // Redirect ke login
    }
};

const handleLogout = async () => {
    try {
        // Pastikan token sudah ada di header sebelum logout
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            await axios.post('http://127.0.0.1:8000/api/logout'); // Sesuaikan URL Laravelmu
        }
        localStorage.removeItem('access_token');
        delete axios.defaults.headers.common['Authorization']; // Hapus header
        router.push('/login');
    } catch (error) {
        console.error('Logout error:', error);
        localStorage.removeItem('access_token'); // Pastikan token dihapus walau error
        router.push('/login');
    }
};

onMounted(fetchUserData);
</script>

<template>
    <div class="dashboard-container">
        <h2>Selamat Datang di Dashboard!</h2>
        <div v-if="user">
            <p>Halo, {{ user.name }} ({{ user.email }})</p>
            </div>
        <button @click="handleLogout">Logout</button>
    </div>
</template>

<style scoped>
.dashboard-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}
button {
    background-color: #dc3545;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
}
button:hover {
    background-color: #c82333;
}
</style>