const state = {
    product_name: "",
    columns: []
}

const getters = {
    getProductName: state => {
        return state.product_name
    }
}

const mutations = {
    setProductName(state, payload) {
        state.product_name = payload
    }
}

export { state, getters, mutations }
