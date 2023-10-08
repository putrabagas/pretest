import { createStore } from "vuex";

const store = createStore({
    state: {
        token : localStorage.getItem('token') || 0,
        products:[],
    },
    mutations:{
        UPDATE_TOKEN(state, payload){
            state.token = payload;
        },
        setProducts(state, products) {
            state.products = products;
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
        getProducts: (state) => state.products,
    }

});

export default store;