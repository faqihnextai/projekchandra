import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/DashboardView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login' // Arahkan default ke halaman login
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true } // Tandai rute ini butuh autentikasi
    },
    // Tambahkan rute lain jika diperlukan
  ]
});

// Navigation Guard: Melindungi rute yang membutuhkan autentikasi
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('access_token')) {
    next('/login'); // Redirect ke login jika tidak ada token
  } else {
    next();
  }
});

export default router;