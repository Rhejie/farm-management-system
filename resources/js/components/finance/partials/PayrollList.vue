<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" type="primary" @click="generatePayroll" style="float:right;">Add Payroll</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <!--<el-date-picker
                v-model="date"
                @change="changeDate"
                type="date"
                :clearable="false"
                placeholder="Pick a day">
            </el-date-picker> -->
            <el-table
                :data="payroll"
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
                        prop="employee"
                        label="EMPLOYEE"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.employee.lastname}}, {{scope.row.employee.firstname}} {{scope.row.employee.middlename}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="rate"
                        label="RATE PER DAY"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="from_date"
                        label="FROM"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.from_date | filteDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="to_date"
                        label="TO"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.to_date | filteDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="date"
                        label="PAYROLL GENERATE"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.date | filteDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="180"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handlePayslip(scope.row)" class="btn btn-info btn-sm">Payslip</button>
                            <button @click="handleView(scope.row)" class="btn btn-success btn-sm">View</button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="payrollsPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog title="Payroll" width="75%" :visible.sync="dialogTableVisible" :before-close="handleClose">
            <view-payroll :model="model"></view-payroll>
        </el-dialog>
        <el-dialog title="Payroll" width="75%" :visible.sync="dialogTableVisiblePayslip" :before-close="handleClose">
            <print-payslip :model="model"></print-payslip>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
import moment from 'moment'
export default {
    name: 'PayrollList',
    mixins: [paginate],
    data() {
        return {
            payroll: [],
            payrollsPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            date: '',
            dialogTableVisiblePayslip: false
        }
    },
    created() {
        this.date = new Date();
        this.getPayrolls()

        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.payroll.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.payroll.forEach(cat => {
                if(cat.id == data.id) {
                    for(let key in data) {
                        cat[key] = data[key]
                    }
                }
            })

            this.dialogTableVisible = false
            this.mode = ''
        })
    },
    filters: {
        filteDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('YYYY-MMM-DD');
            }
        }
    },
    methods: {
        async getPayrolls() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Finance.getPayrolls(params);
                this.payroll = res.data.data;
                this.payrollsPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        generatePayroll() {
            this.$router.push({name : 'Generate Payroll'})
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
                await this.$API.Warehouse.deleteCategory(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.payroll.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        handlePayslip(data) {
            this.dialogTableVisiblePayslip = true;
            this.model = {...data}
        },
        handleView(data) {
            this.model = {...data}
            this.dialogTableVisible = true;
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getPayrolls();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getPayrolls();
        },
        applySearch() {
            this.getPayrolls();
        },
        changeDate(value) {
            this.date = value
            this.getPayrolls();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getPayrolls();
            }
        }
    }
}
</script>

