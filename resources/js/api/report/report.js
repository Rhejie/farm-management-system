import axios from 'axios'

export default {

    getBananaReport(area) {
        return axios.get(`/report/banana-report/${area}`);
    },
    getBananaReportByYear(area) {
        return axios.get(`/report/banana-by-year-report/${area}`)
    }
}
