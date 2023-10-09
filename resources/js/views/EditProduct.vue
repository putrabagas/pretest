<template>
    <div class="row justify-content-center">
      <div class="col-sm-6 mt-4">
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>{{ successMessage }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div v-for="error in errors" :key="error">
          <div v-for="err in error" :key="err" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ err }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
        <form @submit.prevent="editProduct">
          <h1 class="h3 mb-3 fw-normal">Edit Product</h1>
          <div class="form-floating">
            <input type="text" class="form-control" v-model="form.name" placeholder="Shirt">
            <label for="name">Name</label>
          </div>
          <div class="form-floating my-3">
            <textarea class="form-control" v-model="form.description" placeholder="Description" rows="3"></textarea>
            <label for="description">Description</label>
          </div>
          <div class="form-floating mb-3">
            <input type="number" min="1" class="form-control" v-model="form.price" placeholder="5000">
            <label for="price">Price</label>
          </div>
          <div class="my-2 d-flex justify-content-center" id="inibutton">
            <button class="btn btn-primary py-2" type="submit">Save Changes</button>
            <router-link :to="{ name: 'Product' }" class="btn btn-outline-primary ms-2">Back</router-link>
          </div>
        </form>
      </div>
    </div>
  </template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      form: {
        name: '',
        description: '',
        price: '',
        _method: 'PUT'
      },
      errors: [],
      successMessage: '',
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
                    const productData = response.data.data;
                    this.form.name = productData.name;
                    this.form.description = productData.description;
                    this.form.price = productData.price;
                } else {
                    console.error("Product not found");
                }
                })
                .catch((error) => {
                console.error("Error fetching product:", error);
                });
        },
        async editProduct() {
            const productId = this.$route.params.id;
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };
            
            try {
                const response = await axios.post(`/api/products/${productId}`, this.form, { headers });
                if (response.data.code === 200) {
                // this.successMessage = 'Product updated successfully';
                window.alert(`Product updated successfully`);
                this.$router.push({ name: 'DetailProduct', params: { id: productId } });
                }
            } catch (error) {
                this.errors = error.response.data.data.errors;
            }
        },
  },
};
</script>
