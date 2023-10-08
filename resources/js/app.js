// import './bootstrap';
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.js';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { createApp } from 'vue';
// import { createRouter, createWebHistory } from 'vue-router';
import App from './layouts/App.vue';
import router from './router.js';
import store from './store/index.js';

createApp(App)
    .use(router)
    .use(store)
    .mount('#app');


// const app = createApp({});

// const router = createRouter({
//     history: createWebHistory(),
//     routes: Routes,
// });

// app.use(router);
// app.mount('#app');
