import axios from 'axios';

export default {

    getQrCodes(params) {
        return axios.get(`/qrcode/get-qrcodes?isUsed=${params.isUsed}&page=${params.current_page}&count=${params.current_size}&search=${params.search}`)
    },
    storeQrCode() {
        return axios.get('/qrcode/store-qrcode');
    },
    verifiedQrCode(data) {
        return axios.get(`/qrcode/verified?qrcode=${data}`)
    }
}
