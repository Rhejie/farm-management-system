import axios from 'axios';

export default {
    generateReport(data) {
        return axios.get(`/month/generate-report?date_from=${data.date_from}&date_to=${data.date_to}`)
    },
    storeReport(data) {
        return axios.post(`/month/store-report`, data)
    },
    getReports(params) {
        return axios.get(`/month/get-reports?date=${params.date}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    getReportByID(id) {
        return axios.get(`/month/get-report/${id}`)
    }
}
