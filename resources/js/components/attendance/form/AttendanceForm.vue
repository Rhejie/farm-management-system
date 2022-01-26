<template>
    <el-card class="box-card" style="margin-bottom: 10px;">
        <div  class="text item">
            <el-form :inline="true" :model="form" :rules="rules" ref="form" class="demo-form-inline">
                <el-form-item label="Employee" prop="employee_id">
                    <el-select
                        v-model="form.employee_id"
                        filterable
                        remote
                        style="width:340px"
                        reserve-keyword
                        @change="attendanceChange"
                        placeholder="Please enter a keyword to search employee"
                        :remote-method="remoteMethodEmployee"
                        :loading="loading">
                            <el-option
                                v-for="item in employees"
                                :key="item.id"
                                :label="`${item.lastname}, ${item.firstname} ${item.middlename}`"
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
                <el-form-item label="Time In" prop="time_in">
                    <el-time-select
                        v-model="form.time_in"
                        :picker-options="{
                            start: '06:00',
                            step: '00:15',
                            end: '24:00'
                        }"
                        format="h:mm A"
                        value-format="h:mm A"
                        placeholder="Select time">
                    </el-time-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('form')">In</el-button>
                    <el-button @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</template>
<script>
export default {
    name: 'AttendanceForm',
    data() {
        return {
            form: {
                employee_id: '',
                time_in: '',
                date: '',
            },
            rules : {
                time_in: [
                    { required: true, message: 'Please input time in', trigger: 'blur' }
                ],
                date: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
                employee_id: [
                    { required: true, message: 'Please select employee', trigger: 'blur' }
                ],
            },
            employees: [],
            loading: false,
        }
    },
    created() {
        this.form.date = new Date()
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                this.storeAttendance();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodEmployee(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Employee.searchEmployee(query);
                    this.employees = res.data;
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        attendanceChange() {
            this.employees = []
        },
        async storeAttendance() {
            try {
                // this.form.time_in = this.$df.formatDate(this.form.time_in, "H:mm:ss")
                this.form.date = this.$df.formatDate(this.form.date, "YYYY-MM-DD")
                const res = await this.$API.Attendance.storeAttendance(this.form);

                if(res.data == 'already_time_in') {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Already Time In'
                    });
                    this.resetForm('form');
                    return
                }
                this.$EventDispatcher.fire('NEW_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Time In',
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
