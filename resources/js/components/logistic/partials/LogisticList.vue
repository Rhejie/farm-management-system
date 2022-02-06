<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-select v-model="area_id" @change="areaChange" clearable="" placeholder="Select Area">
                <el-option
                    v-for="item in areas"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id">
                </el-option>
            </el-select>
            <el-button size="mini" type="primary" @click="addLogistic" style="float:right;">Add Logistic Regression</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-table
                :data="logistics"
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
                        prop="area.name"
                        label="Area"
                        width="160"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="bud_injection_x1"
                        label="BUD INJECTION (X1)"
                        width="160"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="bu_injection_date"
                        width="160"
                        label="BUD INJECTION DATE"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="bagging_report_x2"
                        width="170"
                        label="BAGGING REPORT (X2)"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="bagging_report_date"
                        width="170"
                        label="BAGGING REPORT DATE"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="stem_cut_y"
                        label="STEM CUT (Y)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="stem_cut_y_date"
                        label="STEM CUT DATE"
                        width="160"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="x1y"
                        label="(X1)(Y)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="x2y"
                        label="(X2)(Y)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="x1x2"
                        label="(X1)(X2)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="x12"
                        label="(X1)(2)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="x22"
                        label="(X2)(2)"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="y22"
                        label="(Y)(2)"
                        width="150"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="110"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleEdit(scope.row)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                            <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="logisticsPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog
            :title="mode == 'create' ? 'ADD LOGISTIC REGRESSION' : 'UPDATE LOGISTIC REGRESSION'" width="55%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisible">
            <logistic-form :mode="mode" :model="model"></logistic-form>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
export default {
    name: 'LogisticList',
    mixins: [paginate],
    data() {
        return {
            logistics: [],
            logisticsPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            area_id: '',
            areas: []
        }
    },
    created() {
        this.getLogistics()

        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.logistics.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.logistics.forEach(cat => {
                if(cat.id == data.id) {
                    for(let key in data) {
                        cat[key] = data[key]
                    }
                }
            })

            this.dialogTableVisible = false
            this.mode = ''
        })

        this.getAreas();
    },
    methods: {
        async getLogistics() {
            try {
                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    area_id: this.area_id
                };

                this.loading = true;
                const res = await this.$API.Logistic.getLogistics(params);
                this.logistics = res.data.data;
                this.logisticsPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        async getAreas() {
            try {
                const res = await this.$API.Area.getAllAreas();
                this.areas = res.data
            } catch (error) {
                console.log(error);
            }
        },
        areaChange(value) {
            this.area_id = value;
            this.getLogistics();
        },
        addLogistic() {
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
                await this.$API.Logistic.deleteLogistic(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.logistics.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getLogistics();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getLogistics();
        },
        applySearch() {
            this.getLogistics();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getLogistics();
            }
        }
    }
}
</script>
