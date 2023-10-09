import { createRouter, createWebHistory } from "vue-router";

import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Logout from "./views/Logout.vue";
import Home from "./views/Home.vue";

import Product from "./views/Product.vue";
import AddProduct from "./views/AddProduct.vue";
import DetailProduct from "./views/DetailProduct.vue";
import EditProduct from "./views/EditProduct.vue";

import Transaction from "./views/Transaction.vue";
import Checkout from "./views/Checkout.vue";
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
        path: "/add-product",
        name: "AddProduct",
        component: AddProduct,
        meta:{
            requiresAuth: true
        }
    },
    {
        path: "/detail-product/:id",
        name: "DetailProduct",
        component: DetailProduct,
    },
    {
        path: "/edit-product/:id",
        name: "EditProduct",
        component: EditProduct,
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
    {
        path: "/checkout",
        name: "Checkout",
        component: Checkout,
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

