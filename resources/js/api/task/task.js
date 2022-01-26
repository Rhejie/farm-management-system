import axios from 'axios';

export default {
    getTasks(params) {
        return axios.get(`/task/get-tasks?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeTask(data) {
        return axios.post(`/task/store-task`, data)
    },
    updateTask(id, data) {
        return axios.post(`/task/update-task/${id}`, data)
    },
    deleteTask(id) {
        return axios.post(`/task/delete-task/${id}`)
    },
    searchTask(search) {
        return axios.get(`/task/search-task?search=${search}`)
    }
}
