<template>
    <div class="album py-5 px-3 bg-body-tertiary">
        <h2 class="mb-3">List Product</h2>
        <div class="container">
            <div class="my-3 d-flex justify-content-end" id="inibutton">
                <button v-if="$store.getters.getAdmin == 1 && $store.getters.getToken != 0" type="button" class="btn btn-primary ml-auto">
                    <router-link :to="{ name: 'AddProduct' }" class="text-white text-decoration-none">+ Add Product</router-link>
                </button>
            </div>
            <form @submit.prevent="performSearch" class="mb-3 col-12 col-lg-auto input-group" role="search">
                <input v-model="searchQuery" type="text" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="search">
                <button class="btn btn-outline-success" type="submit" id="search">Search</button>
            </form>
            <div v-if="searchResults.length == 0 && isNotFound == true">
                <h4 class="mt-5 text-center">Sorry, Product not found <i class="bi bi-emoji-frown"></i></h4>
            </div>
            <div v-else-if="searchResults.length == 0 && isNotFound == false ">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div v-for="product in products" :key="product.id" class="col">
                        <div class="card shadow-sm">
                            <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg"  class="rounded-top"/> 
                            <div class="card-body">
                                <h5 class="card-title" id="title">{{ product.name }}</h5>
                                <p class="card-text fw-bold" id="price">
                                    Rp.{{ product.price }}
                                </p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="btn-group">
                                        <router-link :to="{ name: 'DetailProduct', params: { id: product.id } }">
                                            <button type="button" class="btn btn-outline-success me-2">Detail</button>
                                        </router-link>
                                        <div v-if="$store.getters.getAdmin == 1 && $store.getters.getToken != 0">
                                            <router-link :to="{ name: 'EditProduct', params: { id: product.id } }">
                                                <button type="button" class="btn btn-outline-success me-2">Edit</button>
                                            </router-link>
                                            <button type="button" class="btn btn-outline-danger me-2" id="deleteButton" @click="confirmDelete(product.id)">Delete</button>
                                        </div>
                                        <form @submit.prevent="addToCart(product.id)" v-else class="me-2">
                                            <div class="input-group" style="max-width: 170px;">
                                                <input v-model="quantity" type="number" class="form-control" min="1" required placeholder="Quantity">
                                                <button type="submit" class="btn btn-outline-success">+ Cart</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div v-for="product in searchResults" :key="product.id" class="col">
                        <div class="card shadow-sm">
                            <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg"  class="rounded-top"/> 
                            <div class="card-body">
                                <h5 class="card-title" id="title">{{ product.name }}</h5>
                                <p class="card-text fw-bold" id="price">
                                    Rp.{{ product.price }}
                                </p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="btn-group">
                                        <router-link :to="{ name: 'DetailProduct', params: { id: product.id } }">
                                            <button type="button" class="btn btn-outline-success me-2">Detail</button>
                                        </router-link>
                                        <div v-if="$store.getters.getAdmin == 1 && $store.getters.getToken != 0">
                                            <router-link :to="{ name: 'EditProduct', params: { id: product.id } }">
                                                <button type="button" class="btn btn-outline-success me-2">Edit</button>
                                            </router-link>
                                            <button type="button" class="btn btn-outline-danger me-2" id="deleteButton" @click="confirmDelete(product.id)">Delete</button>
                                        </div>
                                        <form @submit.prevent="addToCart(product.id)" v-else class="me-2">
                                            <div class="input-group" style="max-width: 170px;">
                                                <input v-model="quantity" type="number" class="form-control" min="1" required placeholder="Quantity">
                                                <button type="submit" class="btn btn-outline-success">+ Cart</button>
                                            </div>
                                        </form>
                                    </div>
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
    data() {
        return {
            quantity: 1,
            searchQuery: '',
            searchResults: [],
            isNotFound: false,
        };
    },
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
        
        addToCart(productId) {
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };
            const data = {
                product_id: productId,
                quantity: this.quantity,
            };

            axios.post('/api/carts', data, { headers })
                .then(response => {
                    if (response.data.status && response.data.data) {
                        const addedProduct = response.data.data;
                        window.alert(`Added ${addedProduct.quantity} to cart!`);
                    }
                })
                .catch(error => {
                    console.error('Error adding product to cart:', error);
                });
        },
        confirmDelete(productId) {
            const confirmed = window.confirm("Are you sure you want to delete this product?");
            if (confirmed) {
                this.deleteProduct(productId);
            }
        },

        deleteProduct(productId) {
            console.log('deleteProduct');
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.delete(`/api/products/${productId}`, { headers })
                .then(response => {
                    if (response.data.status && response.data.data) {
                        window.alert('Product deleted successfully!');
                        this.$store.dispatch('fetchProducts');
                    }
                })
                .catch(error => {
                    window.alert('Error deleting product:', error);
                });
        },
        async performSearch() {
            try {
                const response = await axios.post('/api/products/search', {
                search: this.searchQuery,
                });
                if (response.data.status) {
                this.searchResults = response.data.data;
                } else {
                this.searchResults = [];
                this.isNotFound = true;
                }
            } catch (error) {
                this.searchResults = [];
                this.isNotFound = true;
                // console.error('Error searching products:', error);
            }
        },
    },
    mounted() {
        this.checkIsAdmin();
    },
};
</script>
