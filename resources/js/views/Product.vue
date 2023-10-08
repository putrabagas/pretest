<template>
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="my-2 d-flex justify-content-end" id="inibutton">
                <button v-if="isAdmin && $store.getters.getToken != 0" type="button" class="btn btn-primary me-2 ml-auto">
                    <router-link :to="{ name: 'AddProduct' }" class="text-white text-decoration-none">Add Product</router-link>
                </button>
            </div>
            <!-- <div v-if="error" class="alert alert-danger">{{ error }}</div> -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div v-for="product in products" :key="product.id" class="col">
                    <div class="card shadow-sm">
                        <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg" alt="Thumbnail" width="100%" height="100%">
                        <div class="card-body">
                            <h5 class="card-title" id="title">{{ product.name }}</h5>
                            <p class="card-text fw-bold" id="price">
                                Rp.{{ product.price }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <router-link :to="{ name: 'DetailProduct', params: { id: product.id } }" v-if="isAdmin && $store.getters.getToken != 0">
                                        <button type="button" class="btn btn-outline-success me-2">Edit</button>
                                    </router-link>
                                    <router-link :to="{ name: 'DetailProduct', params: { id: product.id } }" v-else>
                                        <button type="button" class="btn btn-outline-success me-2">+ Cart</button>
                                    </router-link>
                                    <router-link :to="{ name: 'DetailProduct', params: { id: product.id } }">
                                        <button type="button" class="btn btn-outline-success me-2">Detail</button>
                                    </router-link>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
