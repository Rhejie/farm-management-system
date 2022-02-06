import axios from 'axios'

export default {
    getLogistics() {
        return axios.get(`/logistic/get-logistics`)
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
