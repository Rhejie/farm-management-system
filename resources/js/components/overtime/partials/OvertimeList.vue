<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-input
                size="mini"
                placeholder="Search here....."
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
                :data="overtime"
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
                        prop="date"
                        label="DATE"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.date | filteDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="ot_in"
                        label="OT IN"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.ot_in | timeFormat}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="ot_out"
                        label="OT OUT"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.ot_out | timeFormat}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="ot_status"
                        label="Status"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="180"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleApproved(scope.$index, scope.row)" class="btn btn-success btn-sm">Approved</button>
                            <button @click="handleDecline(scope.$index, scope.row)" class="btn btn-danger btn-sm">Decline</button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="overtimePagination"
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
    name: 'OvertimeList',
    mixins: [paginate],
    data() {
        return {
            overtime: [],
            overtimePagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            date: '',
        }
    },
    created() {
        this.date = new Date();
        this.getOvertime()

        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.overtime.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.overtime.forEach(cat => {
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
        },
        timeFormat(value) {
            if(value) {
                return moment(value, 'HH:mm:ss').format('h:mm a')
            }
            return '-'
        }
    },
    methods: {
        async getOvertime() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Attendance.getOvertime(params);
                this.overtime = res.data.data;
                this.overtimePagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
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
                        message: 'Approved canceled'
                    });
                });
        },
        handleApproved(index, data) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    this.approved(index, data);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Approved canceled'
                    });
                });
        },
        async approved(index, data) {
            try {
                const res = await this.$API.Attendance.approvedOT(data.id);
                this.$message({
                    message: 'Successfully approved.',
                    type: 'success'
                });
                this.overtime.forEach(emp => {
                    if(emp.id == res.data.id) {
                        for(let key in res.data) {
                            emp[key] = res.data[key]
                        }
                    }
                });
            } catch (error) {
                console.log(error);
            }
        },
        async declined(index, data) {
            try {
                const res = await this.$API.Attendance.declineOT(data.id)
                this.$message({
                    message: 'Success fully declined.',
                    type: 'success'
                });
                this.overtime.forEach(emp => {
                    if(emp.id == res.data.id) {
                        for(let key in res.data) {
                            emp[key] = res.data[key]
                        }
                    }
                });
            } catch (error) {
                console.log(error);
            }
        },
        handleDecline(index, data) {
            this.$confirm('Declined?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    this.declined(index, data);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled'
                    });
                });
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getOvertime();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getOvertime();
        },
        applySearch() {
            this.getOvertime();
        },
        changeDate(value) {
            this.date = value
            this.getOvertime();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getOvertime();
            }
        }
    }
}
</script>
