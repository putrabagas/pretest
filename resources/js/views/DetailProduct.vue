<template>
    <h1>detailproduct</h1>
    <div v-if="product">
      <h1>{{ product.name }}</h1>
      <p>{{ product.description }}</p>
      <p>Harga: {{ product.price }}</p>
    </div>
  </template>
  
<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  data() {
    return {
      product: null,
      productId: this.$route.params.id
    };
  },
  methods: {
    fetchProduct() {
        console.log(this.$route.params.id);
      axios.get(`/api/products/15`)
        .then(response => {
            console.log(response.data);
          if (response.data.status && response.data.data) {
            console.log(response.data);
            this.product = response.data.data;
          } else {
            console.log('Product not found');
            console.error('Product not found');
          }
        })
        .catch(error => {
          console.log('Error fetching product:');
          console.error('Error fetching product:', error);
        });
    }
  },
  onMounted() {
    this.fetchProduct();
  }
};
</script>
