<template>
    <div>
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
        <el-button @click="refresh"><i class="fas fa-retweet"></i></el-button>
        <el-table
            :data="deploy"
            v-loading="loading"
            style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <div class="img_profile">
                            <h4>MEMBERS</h4>
                            <el-table
                                :data="props.row.members"
                                style="width: 100%">
                                <el-table-column
                                    prop="firstname"
                                    label="FIRSTNAME">
                                </el-table-column>
                                <el-table-column
                                    prop="lastname"
                                    label="LASTNAME">
                                </el-table-column>
                                <el-table-column
                                    prop="middlename"
                                    label="MIDDLENAME">
                                </el-table-column>
                                <el-table-column
                                    prop="position"
                                    label="POSITION">
                                </el-table-column>
                            </el-table>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                    width="70"
                    label="No."
                    :sortable="true">
                        <template slot-scope="scope">
                            {{scope.$index + 1}}
                        </template>
                </el-table-column>
                <el-table-column
                    prop="team.name"
                    label="TEAM"
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
                    prop="area.name"
                    label="Area"
                    :sortable="true">
                </el-table-column>
                <el-table-column
                    fixed="right"
                    width="110"
                    label="ACTION">
                    <template slot-scope="scope">
                        <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </template>
                </el-table-column>
        </el-table>
        <global-pagination
            :current_page="current_page"
            :current_size="current_size"
            :pagination="deployPagination"
            :total="filters.total"
            @handleSizeChange="handleSizeChange"
            @handleCurrentChange="handleCurrentChange">
        </global-pagination>
        <el-dialog :title="name" width="30%" :visible.sync="dialogTableVisible" :before-close="handleClose">
            <deploy-out-form :model="model"></deploy-out-form>
        </el-dialog>
    </div>
</template>
<script>
import paginate from '../../../mixin/pagination'
import moment from 'moment'
export default {
    name: 'DeployTeamList',
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

        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.deploy.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.deploy.forEach(cat => {
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
        filterDate(value) {
            if(value) {
                return moment(value, 'YYYY-MMM-DD').format('YYYY-MMM-DD')
            }
            return '-'
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
                const res = await this.$API.Deploy.getDeploy(params);
                this.deploy = res.data.data;
                this.deployPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        addAttendance() {
            this.dialogTableVisible = true;
            this.model = {}
            this.mode = 'create';
        },
        handleOut(data) {
            this.$confirm('Are yous sure you want to time out?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                    }).then(() => {
                        this.timeOut(data)
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: 'Time out canceled'
                        });
                    });
        },
        async timeOut(data) {
            let form = {
                time_out: ''
            }
            const res = await this.$API.Attendance.updateAttendance(data.id, form);
            this.$EventDispatcher.fire('UPDATE_DATA', res.data);
            this.$notify({
                title: 'Success',
                message: 'Successfully Time Out',
                type: 'success'
            });
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
                await this.$API.Deploy.deleteDeploy(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.$EventDispatcher.fire('DELETE_DEPLOY', data.id)
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
        refresh() {
            this.getDeploy()
        }
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
