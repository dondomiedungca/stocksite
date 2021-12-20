import { getPurchasing, doSaveManually } from "./service"
import axios from "axios"

export const namespaced = true

export const state = {
    isLoading: false,
    loaded: 0,
    item_data: {
        stock_number: "",
        item_status_id: 1,
        item_cosmetic_id: 1,
        item_cosmetic_description: "",
        item_description: "",
        origin_price: "",
        selling_price: "",
        discount_percentage: "",
        details: {}
    },
    statuses: [],
    cosmetics: [],
    product_types: [],
    product_types_origin: [],
    transaction_id: null,
    purchasing: {},
    purchasing_id: null,
    selected_product_type: {},
    selected_product_type_id: "",
    photoVisibility: true
}

export const actions = {
    async setForPurchasing({ commit, rooState, dispatch }, data) {
        const { purchasing_type_id, product_type, transaction_id } = data

        let response = await getPurchasing(purchasing_type_id)

        commit("SET_PRODUCT_TYPE", product_type)
        commit("SET_PURCHASING", { purchasing: response.data.purchasing, transaction_id: transaction_id, purchasing_type_id })
    },
    async saveManually({ commit, rooState, dispatch }, data) {
        try {
            commit("SET_LOADING", true)
            const { photo, basis, item_data } = data

            let formData = new FormData()

            formData.append("photo", photo)
            formData.append("inventory", JSON.stringify(item_data))
            formData.append("basis", basis)
            formData.append("transaction_id", state.transaction_id)
            formData.append("product_type_id", state.selected_product_type.id)
            formData.append("purchasing_type_id", state.purchasing.id)

            let response = await doSaveManually(formData)
            commit("SET_LOADING", false)
            if (response.data.success) {
                const notification = {
                    isVisible: true,
                    type: "success",
                    text: response.data.message
                }

                dispatch("snackBar/add", notification, { root: true })
            } else {
                throw { message: response.data.message }
            }
        } catch (error) {
            commit("SET_LOADING", false)

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
    async saveFile({ commit, rootState, dispatch }, data) {
        try {
            commit("SET_LOADING", true)

            const { photo, basis, file, file_name } = data

            let formData = new FormData()

            formData.append("photo", photo)
            formData.append("file", file)
            formData.append("file_name", file_name)
            formData.append("product_type_id", state.selected_product_type.id)
            formData.append("basis", basis)
            formData.append("purchasing_type_id", state.purchasing.id)
            formData.append("transaction_id", state.transaction_id)

            let response = await axios.post("/admin/products/save-file", formData, {
                headers: { "Content-Type": "multipart/form-data" },
                onUploadProgress: progressEvent => {
                    let newLoad = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total)) - (Math.floor(Math.random() * 50) + 1)
                    if (newLoad > state.loaded) {
                        commit("SET_LOADED", newLoad)
                    }
                }
            })

            setTimeout(() => {
                commit("SET_LOADING", false)
                commit("SET_LOADED", 0)
            }, 1500)

            if (response.data.success) {
                commit("SET_LOADED", 100)
                const notification = {
                    isVisible: true,
                    type: "success",
                    text: response.data.message
                }

                dispatch("snackBar/add", notification, { root: true })
            } else {
                throw { message: response.data.message }
            }
        } catch (error) {
            commit("SET_LOADING", false)

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
    SET_REQUIRED_DETAILS(state, payload) {
        const { cosmetics, product_types, statuses, stock_number } = payload

        state.cosmetics = cosmetics.map(cosmetic => ({
            text: cosmetic.name,
            value: cosmetic.id
        }))
        state.product_types = product_types.map(product_type => ({
            text: product_type.product_name,
            value: product_type.id
        }))
        state.product_types_origin = product_types
        state.statuses = statuses.map(status => ({
            text: status.name,
            value: status.id
        }))
        state.stock_number = stock_number

        // set initial product type
        state.selected_product_type = product_types[0]
        state.selected_product_type_id = product_types[0].id
    },
    SET_PRODUCT_TYPE(state, payload) {
        state.selected_product_type = payload
    },
    SET_PRODUCT_TYPE_ID(state, payload) {
        state.selected_product_type_id = payload
        state.selected_product_type = state.product_types_origin.find(pt => pt.id == payload)
    },
    SET_PURCHASING(state, payload) {
        const { purchasing, transaction_id, purchasing_type_id } = payload

        state.purchasing = purchasing
        state.transaction_id = transaction_id
        state.purchasing_id = purchasing_type_id

        if (this.purchasing_type.photo == null) {
            state.photoVisibility = true
        } else {
            state.photoVisibility = false
        }
    },
    SET_LOADING(state, payload) {
        state.isLoading = payload
    },
    SET_LOADED(state, payload) {
        state.loaded = payload
    }
}
