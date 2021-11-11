import Vuex from 'vuex';
    
export const store = new Vuex.Store({
    strict:true,
    state:{
        cart: []
    },
    getters:{
        getCart(state){ //take parameter state
            return state.cart
        },
        addCart(state){
            return state.cart.length + 1
        }
    },
    actions:{
        allCartFromDatabase(context){
            axios.get(`/cart`)
                .then((response)=>{
                    console.log(response.data)
                    context.commit("cart",response.data) //categories will be run from mutation
                }).catch(()=>{
                console.log("Error........")
            })
        },
    },
    mutations: {
        cart(state, data) {
           return state.cart = data
        },
        addCart(state, payload){
            state.cart.length = payload
        }
    }
});