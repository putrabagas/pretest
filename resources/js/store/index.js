import { createStore } from "vuex";

const store = createStore({
    state: {
        token : localStorage.getItem('token') || 0,
        isAdmin: localStorage.getItem('is_admin') || null,
        error: null,
        products:[],
    },
    mutations:{
        UPDATE_TOKEN(state, payload){
            state.token = payload;
        },
        UPDATE_ISADMIN(state, payload) {
            state.isAdmin = payload;
        },
        setError(state, error) {
            state.error = error;
        },
        setProducts(state, products) {
            state.products = products;
            state.error = null;
        },
    },
    actions:{
        setToken(context, payload){
            localStorage.setItem('token', payload);
            context.commit('UPDATE_TOKEN', payload);
        },
        removeToken(context){
            localStorage.removeItem('token');
            context.commit('UPDATE_TOKEN', 0);
        },
        setAdmin(context, payload){
            localStorage.setItem('is_admin', payload);
            context.commit('UPDATE_ISADMIN', payload);
        },
        checkIsAdmin({ commit }) {
            const isAdmin = localStorage.getItem('is_admin');
            if (isAdmin && isAdmin === '1') {
                commit('UPDATE_ISADMIN', true);
            }
        },
        removeAdmin(context){
            localStorage.removeItem('is_admin');
            context.commit('UPDATE_ISADMIN', null);
        },
        async fetchProducts({ commit }) {
            try {
                const response = await axios.get('/api/products');
                commit('setProducts', response.data.data);
            } catch (error) {
                console.error(error);
            }
        },
    },
    getters:{
        getToken: function(state){
            return state.token;
        },
        getAdmin: function(state){
            return state.isAdmin;
        },
        getProducts: (state) => state.products,
    }

});

export default store;