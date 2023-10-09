<template>
    <div class="album py-5 px-3 bg-body-tertiary">
      <h2 class="mb-3">List Transaction</h2>
        <div v-if="transaction.length === 0">
            <p>No transaction, go to the product page to add a product and order</p>
            <router-link :to="{name: 'Product'}">
                <button type="button" class="btn btn-success">Go to Product</button>
            </router-link>
        </div>
        <div v-else>
            <div v-for="items in transaction" :key="items.id" class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="d-flex justify-content-between border-bottom">
                    <strong v-if="items.status == 'pending'" class="text-warning">Waiting for payment</strong>
                    <strong v-else-if="items.status == 'completed'" class="text-success">Payment successful</strong>
                    <strong v-else="items.status == 'cancelled'" class="text-danger">Cancelled</strong>
                    <h6 class="pb-2 mb-0">Order Date : {{ formatDate(items.created_at) }}</h6>
                </div>
                <div v-for="item in items.orders" :key="item.id" class="d-flex text-body-secondary pt-3">
                    <img src="https://blog.knitto.co.id/wp-content/uploads/2021/09/kaos-3-scaled.jpg" class="bd-placeholder-img flex-shrink-0 me-2 rounded" alt="Product Image" width="32" height="32">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="text-dark">{{ item.product.name }}</strong>
                            <span class="fw-medium">Rp. {{ item.product.price }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="text-gray-dark"></p>
                            <span class="text-gray-dark fw-medium">x{{ item.quantity }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <h6 v-if="items.status == 'pending' && $store.getters.getAdmin == 1" class="pb-2 mb-0 text-danger"></h6>
                    <button v-else-if="items.status == 'pending'" class="btn btn-warning mx-1" @click="openModal(items.id)">Pay</button>
                    <h6 v-else-if="items.status == 'completed'" class="pb-2 mb-0 text-success">Payment Date : {{ formatDate(items.updated_at) }}</h6>
                    <h6 v-else="items.status == 'cancelled'" class="pb-2 mb-0 text-danger">Cancel Date : {{ formatDate(items.updated_at) }}</h6>
                    <strong class="d-block text-end ">
                        <span>Total Price: Rp. {{ items.total_amount }}</span>
                    </strong>
                </div>
            </div>
        </div>
  
    </div>
<!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="uploadPaymentProof">
                        <div class="mb-3">
                            <label for="paymentProof" class="form-label">Payment Proof</label>
                            <input type="file" class="form-control" id="paymentProof" ref="paymentProofInput" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
</div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        transaction: [],
        processingTransactionId: null,
        paymentProof: null
      };
    },
    created() {
      this.fetchTransactionData();
    },
    methods: {
        fetchTransactionData() {
            const headers = {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            axios.get('/api/orders', { headers })
            .then(response => {
                if (response.data.data) {
                    // console.log(response.data.data);
                this.transaction = response.data.data;
                }
            })
            .catch(error => {
                console.error('Error fetching transaction data:', error);
            });
        },
        formatDate(date) {
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            return new Date(date).toLocaleDateString(undefined, options);
        },
        openModal(transactionId) {
            this.processingTransactionId = transactionId;
            const modal = document.getElementById('paymentModal');
            modal.classList.add('show');
            modal.style.display = 'block';
            modal.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');
        },
        async uploadPaymentProof() {
            const formData = new FormData();
            formData.append('payment_proof', this.$refs.paymentProofInput.files[0]);

            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'multipart/form-data'
            };

            try {
                const response = await axios.post(`/api/orders/payment/${this.processingTransactionId}`, formData, { headers });

                // console.log(response.data);
                window.alert(`Your payment is successful`);

                this.closeModal();

                this.fetchTransactionData();
            } catch (error) {
                console.error('Error uploading payment proof:', error);
            }
        },
        closeModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
        },
    },
  };
  </script>