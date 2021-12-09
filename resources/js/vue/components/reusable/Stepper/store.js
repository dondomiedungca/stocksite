export const namespaced = true

export const state = {
    stepper: {
        canContinue: false,
        canFinish: false,
        isFinal: false
    }
}
export const mutations = {
    SET_STEPPER(state, payload) {
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
