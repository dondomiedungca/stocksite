import axios from "axios"

export const getProducts = async payload => {
    return axios.post("/admin/products/get-products-via-product-types", payload)
}

export const updateData = async payload => {
    return axios.post("/admin/products/update-product", payload)
}
