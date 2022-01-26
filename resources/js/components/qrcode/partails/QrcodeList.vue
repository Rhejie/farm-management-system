<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" :loading="loadingGenerate" type="primary" @click="addQRCode" style="float:right;">Generate QR Code</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-select v-model="isUsed" @change="isUsedChange" placeholder="Select">
                <el-option
                    label="Unused"
                    :value="0">
                </el-option>
                <el-option
                    label="Used"
                    :value="1">
                </el-option>
            </el-select>
            <el-table
                :data="qrcodes"
                v-loading="loading"
                style="width: 100%">
                    <el-table-column
                        width="70"
                        label="No."
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.$index + 1}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="name"
                        label="NAME"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="180"
                        label="ACTION">
                        <template slot-scope="scope">
                            <a :href="`/generate-qr/${scope.row.id}`" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-qrcode"></i> Generate</a>
                            <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="qrcodesPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog
            title="Generate Qr Code" width="45%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisible">
            <qrcode-form></qrcode-form>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
export default {
    name: 'QrcodeList',
    mixins: [paginate],
    data() {
        return {
            qrcodes: [],
            qrcodesPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            dialogTableVisible: false,
            loadingGenerate: false,
            isUsed: 0
        }
    },
    created() {
        this.getQRcodes()
    },
    filters: {
        filterIsUsed(value) {
            if(value == 0) {
                return 'No'
            }
            else {
                return 'Yes'
            }
        }
    },
    methods: {
        async getQRcodes() {
            try {
                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    isUsed: this.isUsed,
                };
                this.loading = true;
                const res = await this.$API.QrCode.getQrCodes(params)
                this.qrcodes = res.data.data;
                this.qrcodesPagination = res.data
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        async addQRCode() {
            try {
                this.loadingGenerate = true;
                const res = await this.$API.QrCode.storeQrCode();
                this.qrcodes.unshift(res.data);
                this.loadingGenerate = false;
            } catch (error) {
                console.log(error);
            }
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        isUsedChange(val) {
            this.isUsed = val;
            this.getQRcodes()
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getQRcodes();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getQRcodes();
        },
        applySearch() {
            this.getQRcodes();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getQRcodes();
            }
        }
    }
}
</script>
