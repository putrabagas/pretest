import { createRouter, createWebHistory } from "vue-router";

import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Logout from "./views/Logout.vue";
import Product from "./views/Product.vue";
import Transaction from "./views/Transaction.vue";
import Cart from "./views/Cart.vue";
import { before } from "lodash";

const routes = [
    {
        path: "/",
        name: "Product",
        component: Product,
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
        path: "/transaction",
        name: "Transaction",
        component: Transaction,
    },
    {
        path: "/cart",
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
        return { name: 'Product' }
    }
})

export default router;

