<template>
    
    <div v-if="product" class="container mt-5 mb-5">
      <h2 class="mb-3">Product Details</h2>
      <div class="row d-flex justify-content-center">
        <div class="col-md-10">
          <div class="card">
            <div class="row">
              <div class="col-md-6">
                <div class="images p-3">
                  <div class="text-center"> 
                    <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg" width="320" class="rounded"/> 
                  </div>
                </div>
              </div>
              <div class="col-md-6 bg-body-tertiary">
                <div class="product p-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                      <router-link :to="{name: 'Product'}" class="text-decoration-none text-secondary">
                        <span><i class="bi bi-arrow-left-short"></i>Back</span> 
                      </router-link>
                    </div>
                  </div>
                  <div class="mt-4 mb-3">
                    <h5 class="text-uppercase">{{ product.name }}</h5>
                    <div class="price d-flex flex-row align-items-center">
                      <span class="act-price">Rp.{{ product.price }}</span>
                    </div>
                  </div>
                  <p class="about">{{ product.description }}</p>
                  <div class="cart mt-4 align-items-center"> 
                    <div class="btn-group">
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
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
    data() {
        return {
            product: null,
            productId: this.$route.params.id,
            quantity:1,
        };
    },
    created() {
        this.fetchProduct();
    },
    methods: {
        fetchProduct() {
            axios
                .get(`/api/products/${this.$route.params.id}`)
                .then((response) => {
                    if (response.data.status && response.data.data) {
                        this.product = response.data.data;
                    } else {
                        console.error("Product not found");
                    }
                })
                .catch((error) => {
                    console.error("Error fetching product:", error);
                });
        },
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
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.delete(`/api/products/${productId}`, { headers })
                .then(response => {
                    if (response.data.status && response.data.data) {
                        window.alert('Product deleted successfully!');
                        this.$router.push({ name: 'Product' });
                    }
                })
                .catch(error => {
                    window.alert('Error deleting product:', error);
                });
        },
    },
    // onMounted() {
    //   this.fetchProduct();
    // }
};
</script>
