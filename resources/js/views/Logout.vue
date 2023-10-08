<template>
    <div>
        Logging out..
    </div>
</template>
<script>
    export default {
    created() {
        this.logout();
    },
    methods: {
        async logout() {
        try {
            const headers = {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            };

            await axios.post('/api/logout', null, { headers });
            localStorage.removeItem('token');
            // localStorage.removeItem('user'); 
            this.$router.push({ name: 'Login' });
        } catch (error) {
            console.error(error);
        }
        },
    },
    };
</script>