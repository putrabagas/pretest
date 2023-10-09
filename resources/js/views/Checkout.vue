<template>
    <div class="album py-5 px-3 bg-body-tertiary">
      <h2 class="mb-3">Order Preview</h2>
        <div v-if="cart.length === 0">
            <p>No product, go to the product page to add a product</p>
            <router-link :to="{name: 'Product'}">
                <button type="button" class="btn btn-success">Go to Product</button>
            </router-link>
        </div>  
        <div v-else-if="checkoutSuccess">
            <p>Your order is successful, please go to the transaction page to confirm your payment</p>
            <router-link :to="{name: 'Transaction'}">
                <button type="button" class="btn btn-success">Go to Transaction</button>
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
                            <p class="card-text">Quantity: {{ item.quantity }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-price text-end">
                <h4>Total Price: Rp. {{ calculateTotalPrice() }}</h4>
                <button @click="checkout" class="btn btn-success mx-2">Order</button>
                <router-link :to="{name: 'Cart'}">
                    <button type="button" class="btn btn-outline-success">Back</button>
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
        checkoutSuccess: false,
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

            axios.get('/api/checkout', { headers })
            .then(response => {
                if (response.data.data) {
                this.cart = response.data.data;
                }
            })
            .catch(error => {
                console.error('Error fetching cart data:', error);
            });
        },
        calculateTotalPrice() {
            return this.cart.reduce((total, item) => total + (item.product.price * item.quantity), 0);
        },
        checkout() {
            const cart = this.cart;

            const checkoutData = {
            total_amount: 0,
            products: cart.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity
            }))
            };

            checkoutData.total_amount = cart.reduce((total, item) => {
            return total + (item.product.price * item.quantity);
            }, 0);

            const headers = {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.post('/api/checkout', checkoutData, { headers })
            .then(response => {
                this.checkoutSuccess = true;
            })
            .catch(error => {
                this.showErrorAlert();
            });
        },
        showErrorAlert() {
            window.alert(`Error during checkout, please try again later. You will be directed to the cart page`);
            this.$router.push({ name: 'Cart' });
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