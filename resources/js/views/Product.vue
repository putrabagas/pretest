<template>
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
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
                                    <button type="button" class="btn btn-md btn-outline-success">
                                        + Cart
                                    </button>
                                    <button type="button" class="btn btn-md btn-outline-success">
                                        Detail
                                    </button>
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
export default {
  computed: {
    products() {
      return this.$store.getters.getProducts;
    }
  },
  created() {
    this.$store.dispatch('fetchProducts');
  }
};
</script>
