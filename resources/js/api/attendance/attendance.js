import axios from 'axios';

export default {

    getAttendance(params) {
        return axios.get(`/attendance/get-attendance?date=${params.date}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeAttendance(data) {
        return axios.post('/attendance/store-attendance', data)
    },
    updateAttendance(id, data) {
        return axios.post(`/attendance/update-attendance/${id}`, data)
    },
    qrCode(decodedString) {
        return axios.post(`/attendance/time-in?qrcode=${decodedString}`)
    },
    OTin(id) {
        return axios.post(`/attendance/ot-in/${id}`)
    },
    OTout(id) {
        return axios.post(`/attendance/ot-out/${id}`)
    },
    getOvertime(params) {
        return axios.get(`/attendance/get-overtime?date=${params.date}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    approvedOT(id) {
        return axios.post(`/attendance/approved-ot/${id}`)
    },
    declineOT(id) {
        return axios.post(`/attendance/decline-ot/${id}`)
    }
}
