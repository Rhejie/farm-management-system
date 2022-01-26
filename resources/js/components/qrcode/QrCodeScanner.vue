<template>
    <div>
        <div class="container-fluid">
            <el-card class="box-card" style="margin-bottom: 10px; margin-top: 10px;">
                <div class="text item">
                    <h1 class="page_title">ATTENDANCE SCANNER</h1>
                </div>
            </el-card>
            <el-card class="box-card">
                <div class="text item">
                    <div class="row">
                        <div class="col-md-7">
                            <qrcode-stream @decode="onDecode" v-loading="loadingScanner"></qrcode-stream>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <el-form ref="profile" :model="profile" label-position="top">
                                        <el-form-item label="First Name:">
                                            <el-input size="large" v-model="profile.employee.firstname" disabled></el-input>
                                        </el-form-item>
                                        <el-form-item label="Middle Name:">
                                            <el-input size="large" v-model="profile.employee.middlename" disabled></el-input>
                                        </el-form-item>
                                        <el-form-item label="Last Name:">
                                            <el-input size="large" v-model="profile.employee.lastname" disabled></el-input>
                                        </el-form-item>
                                        <el-form-item label="Position:">
                                            <el-input size="large" v-model="profile.employee.position" disabled></el-input>
                                        </el-form-item>
                                    </el-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-card>

        </div>



            <!--<StreamBarcodeReader
                @decode="onDecode"
                @loaded="onLoaded"
            ></StreamBarcodeReader> -->

    </div>
</template>
<script>
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import { StreamBarcodeReader } from "vue-barcode-reader";

import { ImageBarcodeReader } from "vue-barcode-reader";
export default {
    name: 'QrCodeScanner',
    components: {
        ImageBarcodeReader,
        StreamBarcodeReader,
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture
    },
    data() {
        return {
            loadingByButton: false,
            profile: {
                employee: {
                    firstname: '',
                    position: '',
                    middlename: '',
                    lastname: '',
                }
            },
            loadingScanner: false
        }
    },
    created() {
        this.hasUserMedia()
    },
    methods: {
        hasUserMedia() {
        //check if the browser supports the WebRTC
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia);
        } ,
        onDecode (decodedString) {
            console.log(decodedString);
            this.timeIn(decodedString)
        },
        onError() {

        },

        onLoaded() {

        },

        async timeIn(decodedString) {

            try {
                this.loadingByButton = true
                const res = await this.$API.Attendance.qrCode(decodedString);
                if(res.data.status == 'no_employee') {
                    this.$message.error('Oops. Unauthorized');
                }
                if(res.data.status == 'success_in') {
                    this.profile = res.data.profile;
                    this.$message({
                        message: 'Successfully Time In.',
                        type: 'success'
                    });
                }
                if(res.data.status == 'success_out') {
                    this.profile = res.data.profile;
                    this.$message({
                        message: 'Successfully Time Out.',
                        type: 'success'
                    });
                }

                if(res.data.status == 'success_ot_in') {
                    this.profile = res.data.profile;
                    this.$message({
                        message: 'Successfully Overtime in.',
                        type: 'success'
                    });
                }

                if(res.data.status == 'success_ot_out') {
                    this.profile = res.data.profile;
                    this.$message({
                        message: 'Successfully Overtime Out.',
                        type: 'success'
                    });
                }

                if(res.data.status == 'invalid') {
                    this.profile = res.data.profile;
                    this.$message.error('Oops. you are not allowed to overtime');
                }

                if(res.data.status == 'already_time_in_out') {
                    this.profile = res.data.profile;
                    this.$message.error('Oops. Already Time in and out');
                }
                this.loadingByButton = false

                setTimeout(() => {
                    window.location.reload(true);
                }, 15000)
            } catch (error) {
                console.log(error);
            }

        }
    },
}
</script>
<style lang="scss">
    .page_title {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight:700
    }

    .profileButton {
        height: 50px !important;
    }
</style>
