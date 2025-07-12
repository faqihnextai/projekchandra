<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';

const isLoading = ref(true);
const progress = ref(0);

onMounted(() => {
  let interval = setInterval(() => {
    if (progress.value < 100) {
      progress.value += 5;
    } else {
      clearInterval(interval);
      setTimeout(() => {
        isLoading.value = false;
      }, 500);
    }
  }, 100);
});
</script>

<template>
  <div id="app">
    <div v-if="isLoading" class="loading-screen">
      <div class="loading-content">
        <div class="loading-bar-container">
          <div class="loading-bar" :style="{ width: progress + '%' }"></div>
        </div>
        <div class="loading-text">{{ progress }}%</div>
      </div>
    </div>

    <div v-else class="main-content">
      <video autoplay loop muted playsinline class="background-video">
        <source src="/bg.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>

      <router-view v-slot="{ Component }">
        <transition name="page-fade-slide" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </div>
</template>

<style>
body, html {
  margin: 0;
  padding: 0;
  overflow: hidden;
  font-family: 'Inter', sans-serif;
  background-color: #000;
}

#app {
  position: relative;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}

.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #000;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  z-index: 9999;
  color: #fff;
  transition: opacity 0.5s ease-out;
}

.loading-content {
  text-align: center;
}

.loading-bar-container {
  width: 300px;
  height: 20px;
  background-color: #333;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 20px;
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.5), 0 0 20px rgba(0, 255, 255, 0.3);
  border: 1px solid rgba(0, 255, 255, 0.7);
}

.loading-bar {
  height: 100%;
  background: linear-gradient(90deg, #00ffff, #00aaff);
  border-radius: 10px;
  transition: width 0.1s linear;
  box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.8);
}

.loading-text {
  font-family: monospace;
  font-size: 2.5em;
  color: #00ffff;
  text-shadow: 0 0 5px #00ffff, 0 0 10px #00ffff;
  letter-spacing: 2px;
}

.main-content {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  color: white;
  overflow: hidden;
  opacity: 0;
  animation: fadeInContent 1s forwards;
  animation-delay: 0.5s;
}

@keyframes fadeInContent {
  from { opacity: 0; }
  to { opacity: 1; }
}

.background-video {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: -2;
  transform: translate(-50%, -50%);
  background-size: cover;
  filter: brightness(0.7) grayscale(0.2);
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  z-index: -1;
}

header {
  line-height: 1.5;
  max-height: 100vh;
  position: relative;
  z-index: 1;
  padding: 2rem;
  width: 100%;
  box-sizing: border-box;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: #00ffff;
  text-shadow: 0 0 5px #00ffff;
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border, #444);
  color: #fff;
  text-decoration: none;
  transition: color 0.3s ease;
}

nav a:hover {
  color: #00aaff;
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;
    padding: 1rem 0;
    margin-top: 1rem;
  }
}

.page-fade-slide-enter-active,
.page-fade-slide-leave-active {
  transition: all 0.5s ease-in-out;
  position: absolute;
  width: 100%;
  top: 0;
  left: 0;
}

.page-fade-slide-enter-from {
  opacity: 0;
  transform: translateY(100%);
}
.page-fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-100%);
}
</style>
