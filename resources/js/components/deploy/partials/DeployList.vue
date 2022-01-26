<template>
    <el-card class="box-card">
        <div  class="text item">
            <!--<el-button size="mini" type="primary" @click="addDeploy" style="float:right;">Deploy</el-button> -->
            <el-input
                size="mini"
                placeholder="Search here....."
                clearable
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-date-picker
                v-model="date"
                @change="changeDate"
                type="date"
                :clearable="false"
                placeholder="Pick a day">
            </el-date-picker>
            <el-table
                :data="deploy"
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
                        prop="item.name"
                        label="ITEM"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="date"
                        label="DATE"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.date | filterDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="quantity"
                        label="QUANTITY"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.quantity | addComma}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="area.name"
                        label="AREA"
                        :sortable="true">
                    </el-table-column>
                    <!--<el-table-column
                        fixed="right"
                        width="120"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleEdit(scope.row)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                            <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </template>
                    </el-table-column> -->
            </el-table>

            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="deployPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
import moment from 'moment'
export default {
    name: 'DeployList',
    mixins: [paginate],
    data() {
        return {
            deploy: [],
            deployPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            date: null,
            name: ''
        }
    },
    created() {
        this.date = new Date();
        this.getDeploy()
    },
    filters: {
        addComma(value) {
            if(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            else {
                return 0;
            }
        },
        filterDate(value) {
            if(value) {
                return moment(value).format('YYYY-MMM-DD h:mm a');
            }
        }
    },
    methods: {
        async getDeploy() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Warehouse.getDeploy(params);
                this.deploy = res.data.data;
                this.deployPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        addDeploy() {
            this.dialogTableVisible = true;
            this.model = {}
            this.mode = 'create';
        },
        handleEdit(data) {
            this.model = {...data};
            this.dialogTableVisible = true;
            this.mode = 'update'
        },
        askToDelete(index, data) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    this.delete(index, data);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled'
                    });
                });
        },
        async delete(index, data) {
            try {
                await this.$API.Warehouse.deleteDeploy(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.deploy.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        changeDate(value) {
            this.date = value
            this.getDeploy();
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getDeploy();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getDeploy();
        },
        applySearch() {
            this.getDeploy();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getDeploy();
            }
        }
    }
}
</script>
