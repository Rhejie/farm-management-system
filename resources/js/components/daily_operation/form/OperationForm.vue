<template>
    <el-card class="box-card" style="margin-bottom: 10px;">
        <div  class="text item">
            <el-form :inline="true" :model="form" :rules="rules" ref="form" class="demo-form-inline">
                <el-form-item label="Team" prop="team_id">
                    <el-select
                        v-model="form.team_id"
                        filterable
                        remote
                        style="width:340px"
                        reserve-keyword
                        @change="attendanceChange"
                        placeholder="Please enter a keyword to search teams"
                        :remote-method="remoteMethodTeam"
                        :loading="loading">
                            <el-option
                                v-for="item in teams"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Task" prop="task_id">
                    <el-select
                        v-model="form.task_id"
                        filterable
                        style="width:100%"
                        remote
                        reserve-keyword
                        @change="changeTask"
                        placeholder="Please enter a keyword to search task"
                        :remote-method="remoteMethodTask"
                        :loading="loadingTask">
                            <el-option
                                v-for="item in tasks"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Area" prop="area_id">
                    <el-select
                        v-model="form.area_id"
                        filterable
                        style="width:100%"
                        remote
                        reserve-keyword
                        @change="changeArea"
                        placeholder="Please enter a keyword to search area"
                        :remote-method="remoteMethodArea"
                        :loading="loadingArea">
                            <el-option
                                v-for="item in areas"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Date" prop="date">
                    <el-date-picker
                        v-model="form.date"
                        type="date"
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        placeholder="Select date">
                    </el-date-picker>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('form')">Operate</el-button>
                    <el-button @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</template>
<script>
export default {
    name: 'OperationForm',
    data() {
        return {
            form: {
                team_id: '',
                area_id: '',
                task_id: '',
                date: '',
                members: []
            },
            rules : {
                area_id: [
                    { required: true, message: 'Please select area', trigger: 'blur' }
                ],
                date: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
                team_id: [
                    { required: true, message: 'Please select team', trigger: 'blur' }
                ],
                task_id: [
                    { required: true, message: 'Please select task', trigger: 'blur' }
                ],
            },
            teams: [],
            loading: false,
            areas: [],
            loadingArea: false,
            tasks: [],
            loadingTask : false,
        }
    },
    created() {
        this.form.date = new Date()
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                this.storeOperation();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodTeam(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Team.searchTeam(query);
                    this.teams = res.data;
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        attendanceChange(value) {
            let team = this.teams.find(t => t.id == value);
            this.form.members = team.employees
            this.teams = []
        },
        async remoteMethodArea(query) {
            try {
                if(query !== '') {
                    this.loadingArea = true;
                    const res = await this.$API.Area.searchArea(query);
                    this.areas = res.data;
                    this.loadingArea = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeArea(value) {
            this.form.area_id_id = null;
            this.form.area_id = value;
        },
        async remoteMethodTask(query) {
            try {
                if(query !== '') {
                    this.loadingTask = true;
                    const res = await this.$API.Task.searchTask(query);
                    this.tasks = res.data;
                    this.loadingTask = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeTask(value) {
            this.form.task_id_id = null;
            this.form.task_id = value;
        },
        async storeOperation() {
            try {
                // this.form.time_in = this.$df.formatDate(this.form.time_in, "H:mm:ss")
                this.form.date = this.$df.formatDate(this.form.date, "YYYY-MM-DD")
                const res = await this.$API.Operation.storeOperation(this.form);

                if(res.data == 'already_time_in') {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Already Deployed'
                    });
                    this.resetForm('form');
                    return
                }
                this.$EventDispatcher.fire('NEW_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deploy',
                    type: 'success'
                });
                this.resetForm('form');
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
