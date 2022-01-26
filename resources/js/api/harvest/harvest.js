import axios from 'axios'

export default {
    getHarvest(params) {
        return axios.get(`/harvest/get-harvest?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeHarvest(data) {
        return axios.post(`/harvest/store-harvest`, data)
    },
    updateHarvest(id, data) {
        return axios.post(`/harvest/update-harvest/${id}`, data)
    },
    deleteHarvest(id) {
        return axios.post(`/harvest/delete-harvest/${id}`);
    }
}
