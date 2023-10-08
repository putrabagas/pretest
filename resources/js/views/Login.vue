<template>
    
    <div class="row justify-content-center">
        <div class="col-sm-4 mt-4">
            <p class="text-danger" v-for="error in errors">
                <span v-for="err in error"> {{ err }}</span>
            </p>
            <form @submit.prevent="login">
                <h1 class="h3 mb-3 fw-normal">Please Login</h1>
            <div class="form-floating">
                <input type="email" class="form-control" id="email" v-model="form.email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating my-3">
                <input type="password" class="form-control" id="password" v-model="form.password" placeholder="Password">
                <label for="password">Password</label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Login</button>
            </form>
        </div>
    </div>
</template>
<script>
    import { reactive, ref } from 'vue'
    import { useRouter } from 'vue-router'
    export default {
        setup() {
            let form = reactive({
                email: '',
                password: ''
            });
            let errors = ref([])
            const router = useRouter();

            const login = async () => {                
                await axios.post('/api/login', form).then(res=>{
                    if(res.data.status){
                        localStorage.setItem('token', res.data.data.token);
                        // localStorage.setItem('user', JSON.stringify(res.data.data.user));
                        router.push({name: 'Product'});
                    }
                }).catch(err=>{
                    errors.value = err.response.data.errors;
                })          
            }
            return {
                form,
                login,
                errors
            }
        }
}
</script>