<template>
    <header class="p-3 px-5 text-bg-dark">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 me-lg-4 mb-lg-0 text-success text-decoration-none">
          E-commerce 
        </a>

        <ul class="nav nav-pills col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li>
                <router-link :to="{name: 'Product'}" class="nav-link px-2 text-white">
                    Product
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'Transaction'}" v-if="$store.getters.getToken != 0" class="nav-link px-2 text-white" >
                    Transaction
                </router-link>
            </li>
        </ul>

        <div class="text-end">
            <router-link :to="{name: 'Cart'}" v-if="$store.getters.getAdmin == 0 && $store.getters.getToken != 0">
                <button type="button" class="btn btn-outline-danger me-2"><i class="bi bi-cart2"></i></button>
            </router-link>
            <router-link :to="{name: 'Login'}" v-if="$store.getters.getToken == 0" >
                <button type="button" class="btn btn-outline-light me-2">Login</button>
            </router-link>
            <router-link :to="{name: 'Register'}" v-if="$store.getters.getToken == 0">
                <button type="button" class="btn btn-danger">Register</button>
            </router-link>
            <router-link :to="{name: 'Logout'}" v-if="$store.getters.getToken != 0">
                <button type="button" class="btn btn-danger">Logout</button>
            </router-link>
        </div>
      </div>
  </header>
</template>
<script>
import { mapState, mapActions } from 'vuex';
export default {
    computed: {
        products() {
        return this.$store.getters.getProducts;
        },
        ...mapState(['isAdmin']),
    },
    created() {
        this.$store.dispatch('fetchProducts');
    },
    methods: {
        ...mapActions(['checkIsAdmin']),
    },
    mounted() {
        this.checkIsAdmin();
    },
};
</script>
