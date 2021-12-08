export const namespaced = true

export const state = {
    data: [],
    limit: 10,
    page: 1,
    total: 0,
    totalPages: 0,
    to: 0,
    from: 0,
    loading: false
}

export const getters = {}
export const actions = {}

export const mutations = {
    SET_LOADING(state, isLoading) {
        state.loading = isLoading
    },
    SET_CONTENT_DATA(state, tableData) {
        const { data, total, last_page, from, to } = tableData
        state.data = data
        state.total = total
        state.totalPages = last_page
        state.to = to
        state.from = from
        state.originalData = [...data]
    },

    SET_DATA(state, collection) {
        state.data = collection
    },

    SET_PAGE(state, page) {
        state.page = page
    },

    SET_ROWS_PER_PAGE(state, rowsPerPage) {
        state.limit = rowsPerPage
    },

    DECREMENT_TOTAL(state) {
        state.total = state.total - 1
    },

    DECREMENT_TO(state) {
        state.to = state.to - 1
    }
}
