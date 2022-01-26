import axios from 'axios'

export default {
    getPayrolls(params){
        return axios.get(`/finance/get-payrolls?date=${params.date}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    generatePayroll(data) {
        return axios.post('/finance/generate-payroll', data)
    },
    storePayroll(data) {
        return axios.post(`/finance/store-payroll`, data)
    }
}
