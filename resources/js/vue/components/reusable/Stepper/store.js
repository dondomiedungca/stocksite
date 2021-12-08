export const namespaced = true

export const state = {
    stepper: {
        canContinue: false,
        canFinish: false,
        isFinal: false
    }
}
export const mutations = {
    setStepper(state, payload) {
        state.stepper = {
            ...state.stepper,
            ...payload
        }
    }
}
export const getters = {
    getStepper: state => {
        return state.stepper
    }
}
