import axios from 'axios';

export default {

    getAreas(params) {
        return axios.get(`/area/get-areas?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeArea(data) {
        return axios.post('/area/store-area', data)
    },
    updateArea(id, data) {
        return axios.post(`/area/update-area/${id}`, data)
    },
    deleteArea(id) {
        return axios.post(`/area/delete-area/${id}`)
    },
    searchArea(search) {
        return axios.get(`/area/search-areas?search=${search}`)
    },
    getAllAreas() {
        return axios.get('/area/get-all-areas')
    },
    getAreaById(id) {
        return axios.get(`/area/get-area/${id}`)
    }
}
