import axios from 'axios';

export default {

    getEmployees(params) {
        return axios.get(`/employee/get-employees?page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeEmployee(data) {
        return axios.post('/employee/store-employee', data)
    },
    updateEmployee(id, data) {
        return axios.post(`/employee/update-employee/${id}`, data)
    },
    deleteEmployee(id) {
        return axios.post(`/employee/delete-employee/${id}`)
    },
    searchEmployee(search) {
        return axios.get(`/employee/search-employee?search=${search}`)
    },
    getProfile(id) {
        return axios.get(`/employee/get-profile/${id}`)
    }
}
