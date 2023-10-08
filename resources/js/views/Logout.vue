<template>
    <div>
        Logging out..
    </div>
</template>
<script>
    import { useStore } from 'vuex';
    import store from '../store';
    export default {
    created() {
        this.logout();
        const store = useStore();
    },
    methods: {
        async logout() {
        try {
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            await axios.post('/api/logout', null, { headers });
            store.dispatch('removeToken');
            store.dispatch('removeAdmin');
            // localStorage.removeItem('token');
            // localStorage.removeItem('user'); 
            this.$router.push({ name: 'Login' });
        } catch (error) {
            console.error(error);
        }
        },
    },
    };
</script>