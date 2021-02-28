const namespaced = true

const state = {
    product_name: "",
    columns: []
}

const getters = {
    getProductName: state => {
        return state.product_name
    },
    getColumns: state => {
        return state.columns.map(column => column)
    },
    getColumnNames: state => state.columns.map(column => column.column_name)
}

const mutations = {
    setProductName(state, payload) {
        state.product_name = payload
    },
    addToColumns(state, payload) {
        state.columns.push(payload)
    },
    setColumns(state, payload) {
        state.columns.splice(payload, 1)
    }
}

const actions = {
    updateColumns({ commit, state }, candidate) {
        let i = state.columns.findIndex(v => v.column_name == candidate.column_name)
        commit("setColumns", i)
        if (!state.columns.length) {
            commit(
                "setStepper",
                {
                    canFinish: false
                },
                { root: true }
            )
        }
    },
    saveNewProductType({ commit, state }) {}
}

export { namespaced, state, getters, mutations, actions }
