import axios from 'axios'

export default {
    getLogistics(params) {
        return axios.get(`/logistic/get-logistics?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeLogistic(data) {
        return axios.post(`/logistic/store-logistic`, data)
    },
    updateLogistic(id, data) {
        return axios.post(`/logistic/update-logistic/${id}`, data)
    },
    deleteLogistic(id) {
        return axios.post(`/logistic/delete-logistic/${id}`)
    }
}
