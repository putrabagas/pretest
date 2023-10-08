import { createRouter, createWebHistory } from "vue-router";

import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Logout from "./views/Logout.vue";
import Home from "./views/Home.vue";
import Product from "./views/Product.vue";
import Transaction from "./views/Transaction.vue";
import Cart from "./views/Cart.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
        meta:{
            requiresAuth: true
        }
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta:{
            requiresAuth: false
        }
    },
    {
        path: "/logout",
        name: "Logout",
        component: Logout,
        
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta:{
            requiresAuth: false
        }
    },
    {
        path: "/products",
        name: "Product",
        component: Product,
        // meta:{
        //     requiresAuth: true
        // }
    },
    {
        path: "/transactions",
        name: "Transaction",
        component: Transaction,
    },
    {
        path: "/carts",
        name: "Cart",
        component: Cart,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from) => {
    if(to.meta.requiresAuth && !localStorage.getItem('token')){
        return { name: 'Login' }
    }
    if(to.meta.requiresAuth == false && localStorage.getItem('token')){
        return { name: 'Home' }
    }
})

export default router;

