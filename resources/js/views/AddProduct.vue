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
            <form @submit.prevent="addproduct">
                <h1 class="h3 mb-3 fw-normal">Add Product</h1>
                <div class="form-floating">
                    <input type="text" class="form-control" id="name" v-model="form.name" placeholder="Shirt">
                    <label for="name">Name</label>
                </div>
                <div class="form-floating my-3">
                    <textarea class="form-control" id="description" v-model="form.description" placeholder="Description" rows="3"></textarea>
                    <label for="description">Description</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" min="1" class="form-control" id="price" v-model="form.price" placeholder="5000">
                    <label for="price">Price</label>
                </div>
                <div class="my-2 d-flex justify-content-center" id="inibutton">
                    <button class="btn btn-primary py-2" type="submit">+ Add Product</button>
                    <button type="button" class="btn btn-outline-primary ms-2 ml-auto">
                        <router-link :to="{ name: 'Product' }" class="text-primary text-decoration-none">Back</router-link>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import { reactive, ref } from 'vue'
    import { useRouter } from 'vue-router'
    import { useStore } from 'vuex'
    export default {
        setup() {
            const router = useRouter();
            const store = useStore();

            let form = reactive({
                name: '',
                description: '',
                price: '',
            });
            let errors = ref([])
            let successMessage = ref('');

            const addproduct = async () => {
                const headers = {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                };                
                await axios.post('/api/products', form,{headers}).then(res=>{
                    if(res.data.code == 200){
                        successMessage.value = 'Product added successfully';
                        form.name = '';
                        form.description = '';
                        form.price = '';
                    }
                }).catch(err=>{
                    errors.value = err.response.data.data.errors;
                })          
            }
            return {
                form,
                addproduct,
                errors,
                successMessage
            }
        }
}
</script>