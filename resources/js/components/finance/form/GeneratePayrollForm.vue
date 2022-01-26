<template>
    <div>
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
            <el-form-item label="From" prop="date_from">
                <el-date-picker
                    v-model="form.date_from"
                    type="date"
                    style="width:100%"
                    format="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    placeholder="Select date from">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="To" prop="area_id">
                <el-date-picker
                    v-model="form.date_to"
                    type="date"
                    style="width:100%"
                    format="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    placeholder="Select date from">
                </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button :loading="loadingGenerate" type="primary" @click="submitForm('form')">Generate</el-button>
                <el-button @click="resetForm('form')">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
export default {
    name: 'AttendanceForm',
    data() {
        return {
            form: {
                employee_id: '',
                date_from: '',
                date_to: ''
            },
            rules : {
                employee_id: [
                    { required: true, message: 'Please select employee', trigger: 'blur' }
                ],
                date_from: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
                date_to: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
            },
            employees: [],
            loading: false,
            areas: [],
            loadingGenerate: false,
        }
    },
    created() {
        this.form.date_to = new Date()

        this.$EventDispatcher.listen('NEW_PAYROLL', data => {
            this.resetForm('form');
        })
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
            this.$EventDispatcher.fire('RESET_FORM', 'reset');
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
                this.form.date_to = this.$df.formatDate(this.form.date_to, "YYYY-MM-DD")
                this.form.date_from = this.$df.formatDate(this.form.date_from, "YYYY-MM-DD")
                this.loadingGenerate = true
                this.$EventDispatcher.fire('LOADING_PAYROLL');
                const res = await this.$API.Finance.generatePayroll(this.form);

                if(res.data == 'already') {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Already Generated'
                    });
                    this.loadingGenerate = false

                    return;
                }
                this.loadingGenerate = false

                this.$EventDispatcher.fire('NEW_GENERATE_PAYROLL', res.data);

            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
