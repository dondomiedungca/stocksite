import Vuex from "vuex"

const store = new Vuex.Store({
    state: {
        stepper: {
            canContinue: false,
            canBack: false,
            isFinal: false
        }
    },
    mutations: {
        setStepper(state, payload) {
            state.stepper.canContinue = payload.canContinue
            state.stepper.canBack = payload.canBack
            state.stepper.isFinal = payload.isFinal
        }
    },
    getters: {
        getStepper: state => {
            return state.stepper
        }
    }
})

export default store
