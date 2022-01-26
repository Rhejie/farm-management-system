import axios from 'axios';

export default {
    getOperations(params) {
        return axios.get(`/operation/get-operations?date=${params.date}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeOperation(data) {
        return axios.post(`/operation/store-operation`, data)
    },
    updateOperation(id, data) {
        return axios.post(`/operation/update-operation/${id}`, data)
    },
    deleteOperation(id) {
        return axios.post(`/operation/delete-operation/${id}`)
    },
    getUndeployedOperations(params) {
        return axios.get(`/operation/get-undeployed-operations?date=${params.date}&search=${params.search}`)
    }
}
