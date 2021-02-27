import Vuex from "vuex"

import * as add_product from "./../components/product/add-product/store"

const modules = {
    add_product
}
const state = {
    stepper: {
        canContinue: false,
        canFinish: false,
        isFinal: false
    },
    snackbar: {
        timeout: 2500,
        isVisible: false,
        text: "",
        color: "#5cb85c"
    }
}
const mutations = {
    setStepper(state, payload) {
        state.stepper = {
            ...state.stepper,
            ...payload
        }
    },
    setSnackbar(state, payload) {
        state.snackbar = {
            ...state.snackbar,
            ...payload
        }
    }
}
const getters = {
    getStepper: state => {
        return state.stepper
    },
    getSnackbar: state => {
        return state.snackbar
    }
}
const actions = {}

// export default store

export default new Vuex.Store({
    modules,
    state,
    getters,
    actions,
    mutations
})
