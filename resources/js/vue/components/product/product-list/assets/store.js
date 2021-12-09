import { getProducts, updateData, doDelete } from "./services"

export const namespaced = true

export const state = {
    cosmetics: [],
    statuses: [],
    product_types: [],
    product_types_origin: [],
    currency: [],
    selected_product_type: {},
    selected_product_type_id_selected: "",
    search: {
        stock_number: "",
        inventory_status_id: "",
        inventory_cosmetic_id: "",
        origin_price: [0, 10000],
        selling_price: [0, 10000],
        details: []
    }
}

export const getters = {
    statuses_options: state => state.statuses,
    cosmetics_options: state => state.cosmetics,
    product_type: state => state.selected_product_type,
    product_types: state => state.product_types,
    currency: state => state.currency,
    selected_product_type_id_selected: state => state.selected_product_type_id_selected
}

export const actions = {
    async fetchData({ commit, rootState, dispatch }) {
        const requestParam = {
            page: rootState.table.page,
            page_limit: rootState.table.limit,
            search: state.search,
            selected_product_type_id: state.selected_product_type.id
        }

        commit("table/SET_LOADING", true, { root: true })

        try {
            const response = await getProducts(requestParam)
            commit("table/SET_CONTENT_DATA", response.data, { root: true })
            commit("table/SET_LOADING", false, { root: true })
            return true
        } catch (error) {
            commit("table/SET_LOADING", false, { root: true })

            if (!error.response) {
                const notification = {
                    isVisible: true,
                    type: "error",
                    text: error.message
                }

                dispatch("snackBar/add", notification, { root: true })
            }

            return false
        }
    },
    async remove({ commit, rootState, dispatch }, payload) {
        const requestParam = {
            inventory: payload
        }

        commit("table/SET_LOADING", true, { root: true })

        try {
            const response = await doDelete(requestParam)
            if (!response.data.success) throw { message: response.data.message }
            dispatch("fetchData")
            return true
        } catch (error) {
            if (!error.response) {
                const notification = {
                    isVisible: true,
                    type: "error",
                    text: error.message
                }

                dispatch("snackBar/add", notification, { root: true })
            }

            return false
        }
    },
    async patchData({ commit, rootState, dispatch }, payload) {
        const requestParam = {
            inventory: payload
        }

        commit("table/SET_LOADING", true, { root: true })

        try {
            const response = await updateData(requestParam)

            if (!response.data.success) {
                throw { message: response.data.message }
            }
            commit("table/SET_LOADING", false, { root: true })
            return true
        } catch (error) {
            commit("table/SET_LOADING", false, { root: true })

            if (!error.response) {
                const notification = {
                    isVisible: true,
                    type: "error",
                    text: error.message
                }

                dispatch("snackBar/add", notification, { root: true })
            }

            return false
        }
    }
}

export const mutations = {
    SET_REQUIRED_DATAS(state, payload) {
        const { product_types, cosmetics, statuses, currency } = payload

        state.product_types = product_types.map(pt => ({
            text: pt.product_name,
            value: pt.id
        }))
        state.cosmetics = cosmetics.map(c => ({
            text: c.name,
            value: c.id
        }))
        state.statuses = statuses.map(s => ({
            text: s.name,
            value: s.id
        }))
        state.currency = currency
        state.selected_product_type = product_types[0] // set as initial, first product type
        state.selected_product_type_id_selected = product_types[0].id // set as initial for select type, first product type
        state.product_types_origin = product_types
    },
    SET_SEARCH_PARAMS(state, payload) {
        const { selected_product_type_id, search_fields } = payload

        state.selected_product_type = state.product_types_origin.find(pt => selected_product_type_id == pt.id)
        state.search = search_fields
    },
    SET_PRODUCT_TYPE(state, payload) {
        state.selected_product_type_id_selected = payload
    }
}
