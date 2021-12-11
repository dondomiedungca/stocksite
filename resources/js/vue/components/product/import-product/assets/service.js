import axios from "axios"

export const getPurchasing = async id => {
    let response = await axios.get(`/admin/purchasing/get-purchasing-type/${id}`)

    return response
}

export const doSaveManually = async data => {
    let response = axios.post(`/admin/products/save-manual-item`, data, {
        headers: { "Content-Type": "multipart/form-data" }
    })

    return response
}
