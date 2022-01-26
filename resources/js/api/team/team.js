import axios from 'axios'

export default {
    getTeams(params) {
        return axios.get(`/team/get-teams?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeTeam(data) {
        return axios.post(`/team/store-team`, data)
    },
    updateTeam(id, data) {
        return axios.post(`/team/update-team/${id}`, data)
    },
    deleteTeam(id) {
        return axios.post(`/team/delete-team/${id}`)
    },
    searchTeam(search) {
        return axios.get(`/team/search-team?search=${search}`)
    }
}
