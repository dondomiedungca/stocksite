import Vuex from "vuex"

import { state as local_product_state, getters as local_product_getters, mutations as local_product_setters } from "../components/product/add-product/local_state"

const store = new Vuex.Store({
    state: {
        stepper: {
            canContinue: false,
            canFinish: false,
            isFinal: false
        },
        snackbar: {
            timeout: 2500,
            isVisible: false,
            text: ""
        },
        ...local_product_state
    },
    mutations: {
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
        },
        ...local_product_setters
    },
    getters: {
        getStepper: state => {
            return state.stepper
        },
        getSnackbar: state => {
            return state.snackbar
        },
        ...local_product_getters
    }
})

export default store
