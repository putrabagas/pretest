<template>
    
    <div class="row justify-content-center">
        <div class="col-sm-4 mt-4">
            <p class="text-danger" v-for="error in errors" :key="error">
                <span v-for="err in error" :key="err">{{ err }}</span>
            </p>
            <form @submit.prevent="register">
                <h1 class="h3 mb-3 fw-normal">Register</h1>
            <div class="form-floating">
                <input type="text" class="form-control" id="name" v-model="form.name" placeholder="Joe A">
                <label for="name">Name</label>
            </div>
            <div class="form-floating my-3">
                <input type="email" class="form-control" id="email" v-model="form.email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" v-model="form.password" placeholder="Password">
                <label for="password">Password</label>
            </div>
            <div class="form-floating my-3">
                <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation" placeholder="Re-password">
                <label for="password_confirmation">Password Confirmation</label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Register</button>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            });
            let errors = ref([])
            const router = useRouter();

            const register = async () => {                
                await axios.post('/api/register', form).then(res=>{
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
                register,
                errors
            }
        }
}
</script>