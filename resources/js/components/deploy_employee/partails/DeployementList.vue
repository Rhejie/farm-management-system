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
            :data="operations"
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
                </el-table-column>
                <el-table-column
                    prop="task.name"
                    label="TASK"
                    :sortable="true">
                </el-table-column>
                <el-table-column
                    prop="area.name"
                    label="AREA"
                    :sortable="true">
                </el-table-column>
                <el-table-column
                    fixed="right"
                    width="110"
                    label="ACTION">
                    <template slot-scope="scope">
                        <!--<button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> -->
                        <button @click="handleDeploy(scope.$index, scope.row)" class="btn btn-info btn-sm"><i class="fas fa-arrow-down"></i> Deploy</button>
                    </template>
                </el-table-column>
        </el-table>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    name: 'DeployementList',
    data() {
        return {
            date: '',
            search: '',
            operations: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            operationsPagination: []
        }
    },
    created() {
        this.date = new Date();
        this.getOperations()
    },
    filters: {
        timeFormat(value) {
            if(value) {
                return moment(value, 'HH:mm:ss').format('h:mm a')
            }
            return '-'
        }
    },
    methods: {
        async getOperations() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Operation.getUndeployedOperations(params);
                this.operations = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        applySearch() {
            this.getOperations();
        },
        changeDate(value) {
            this.date = value
            this.getOperations();
        },
        handleDeploy(index, data) {
            this.$confirm('Deploy this team?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    this.deploy(index, data);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Cancel deploy'
                    });
                });
        },
        async deploy(index, data) {
            try {
                data.date = this.$df.formatDate(data.date, "YYYY-MM-DD")
                const res = await this.$API.Deploy.deployTeam(data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deploy',
                    type: 'success'
                });

                this.$EventDispatcher.fire('NEW_DEPLOY_TEAM', res.data)

                this.operations.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        refresh() {
            this.getOperations()
        }
    },
}
</script>
