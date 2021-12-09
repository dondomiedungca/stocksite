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
    SET_PRODUCT_NAME(state, payload) {
        state.product_name = payload
    },
    ADD_TO_COLUMNS(state, payload) {
        state.columns.push(payload)
    },
    SET_COLUMNS(state, payload) {
        state.columns.splice(payload, 1)
    }
}

const actions = {
    updateColumns({ commit, state, dispatch }, candidate) {
        let i = state.columns.findIndex(v => v.column_name == candidate.column_name)
        commit("SET_COLUMNS", i)
        if (!state.columns.length) {
            commit(
                "stepper/SET_STEPPER",
                {
                    canFinish: false
                },
                { root: true }
            )
        }
        dispatch("setContentTable")
    },
    saveNewProductType({ commit, state }) {},
    setContentTable({ rootState, state, commit }) {
        commit("table/SET_LOADING", true, { root: true })
        commit(
            "table/SET_CONTENT_DATA",
            {
                data: state.columns.slice((rootState.table.page - 1) * rootState.table.limit, rootState.table.limit * rootState.table.page),
                total: state.columns.length,
                last_page: state.columns.length > rootState.table.limit ? (state.columns.length % rootState.table.limit ? Math.floor(state.columns.length / rootState.table.limit) + 1 : state.columns.length / rootState.table.limit) : 1,
                from: (rootState.table.page - 1) * rootState.table.limit + 1,
                to: (rootState.table.page - 1) * rootState.table.limit + state.columns.slice((rootState.table.page - 1) * rootState.table.limit, rootState.table.limit * rootState.table.page).length
            },
            { root: true }
        )
        commit("table/SET_LOADING", false, { root: true })
    }
}

export { namespaced, state, getters, mutations, actions }
