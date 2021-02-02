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
    }
}

export { state, getters, mutations }
