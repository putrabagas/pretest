import { createRouter, createWebHistory } from 'vue-router';
import NavigationComponent from './components/Navigation.vue';
import Login from './views/Login.vue';
import Register from './views/Register.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: NavigationComponent,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;


// export default [
//     {
//         path: '/',
//         name: 'home',
//         component: NavigationComponent,

//     }
// ]