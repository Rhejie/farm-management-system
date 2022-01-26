import axios from 'axios'

export default {
    getTotalEmployees() {
        return axios.get(`/dashboard/total-employees`)
    },
    getTotalAreas() {
        return axios.get(`/dashboard/total-areas`)
    },
    getTotalOperations() {
        return axios.get(`/dashboard/total-operations`)
    },
    getTotalPresent() {
        return axios.get(`/dashboard/total-present`)
    }
}
