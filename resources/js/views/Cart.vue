<template>
    <div class="album py-5 px-3 bg-body-tertiary">
      <h2 class="mb-3">Your Carts</h2>
        <div v-if="cart.length === 0">
            <p>Your cart is empty, go to the product page to add a product</p>
            <router-link :to="{name: 'Product'}">
                <button type="button" class="btn btn-success">Go to Product</button>
            </router-link>
        </div>  
        <div v-else>
            <div v-for="item in cart" :key="item.id" class="card mb-3">
                <div class="row g-0 align-items-center">
                    <div class="col-md-3">
                        <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg" class="img-fluid rounded-start" alt="Product Image" width="100" height="100">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h5 class="card-title">{{ item.product.name }}</h5>
                            <p class="card-text">Rp. {{ item.product.price }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-body">
                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-3" role="group" aria-label="First group">
                                <button @click="removeFromCart(item.id)" type="button" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Second group">
                                <button @click="decreaseQuantity(item.id)" :disabled="item.quantity === 1" type="button" class="btn btn-outline-success fw-bold"><i class="bi bi-dash-lg"></i></button>
                                <button class="btn btn-outline-success fw-bold">{{ item.quantity }}</button>
                                <button @click="increaseQuantity(item.id)" type="button" class="btn btn-outline-success fw-bold"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="total-price">
                <h4>Total Price: Rp. {{ calculateTotalPrice() }}</h4>
                <router-link :to="{name: 'Checkout'}">
                    <button type="button" class="btn btn-success">Checkout</button>
                </router-link>
            </div>
        </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        cart: [],
      };
    },
    created() {
      this.fetchCartData();
    },
    methods: {
        fetchCartData() {
            const headers = {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.get('/api/carts', { headers })
            .then(response => {
                if (response.data.status && response.data.data) {
                this.cart = response.data.data;
                }
            })
            .catch(error => {
                console.error('Error fetching cart data:', error);
            });
        },
        removeFromCart(itemId) {
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.delete(`/api/carts/${itemId}`, { headers })
                .then(response => {
                if (response.data.status) {
                    // Item berhasil dihapus, perbarui data keranjang
                    this.cart = this.cart.filter(item => item.id !== itemId);
                }
                })
                .catch(error => {
                console.error('Error removing item from cart:', error);
                });
        },
        updateQuantity(itemId, quantity, i) {
            const headers = {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            const requestBody = {
            quantity: i,
            _method: 'PUT'
            };

            axios.post(`/api/carts/${itemId}`, requestBody, { headers })
            .then(response => {
                if (response.data.status) {
                // Kuantitas berhasil diperbarui, perbarui data keranjang
                this.cart = this.cart.map(item => {
                    if (item.id === itemId) {
                    item.quantity = quantity;
                    }
                    return item;
                });
                }
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
            });
        },
        decreaseQuantity(itemId) {
            const updatedQuantity = Math.max(1, this.cart.find(item => item.id === itemId).quantity - 1);
            console.log(updatedQuantity);
            this.updateQuantity(itemId, updatedQuantity, -1);
        },
        increaseQuantity(itemId) {
            const updatedQuantity = this.cart.find(item => item.id === itemId).quantity + 1;
            console.log(updatedQuantity);
            this.updateQuantity(itemId, updatedQuantity, 1);
        },
        calculateTotalPrice() {
            return this.cart.reduce((total, item) => total + (item.product.price * item.quantity), 0);
        },
    },
  };
  </script>
  
  <style scoped>
  .cart-item {
    display: flex;
    margin-bottom: 10px;
  }
  
  .cart-item img {
    margin-right: 10px;
  }
  
  .total-price {
    margin-top: 20px;
  }
  </style>