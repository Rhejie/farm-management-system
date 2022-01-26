<template>
    <div>
        <el-form :model="form" :rules="rules" label-position="top" ref="form" class="demo-form-inline">
            <el-form-item label="Time Out" prop="time_out">
                <el-time-select
                    v-model="form.time_out"
                    style="width:100%"
                    :picker-options="{
                        start: '00:00',
                        step: '00:15',
                        end: '24:00'
                    }"
                    format="h:mm A"
                    value-format="h:mm A"
                    placeholder="Select time out">
                </el-time-select>
            </el-form-item>
            <div class="row">
                <div class="col-md-12">
                    <el-form-item style="float:right">
                        <el-button type="primary" @click="submitForm('form')">Out</el-button>
                        <el-button @click="resetForm('form')">Reset</el-button>
                    </el-form-item>
                </div>
            </div>
        </el-form>
    </div>
</template>
<script>
export default {
    name: 'AttendanceOutForm',
    props: {
        model: {}
    },
    data() {
        return {
            form: {
                employee_id: '',
                time_out: '',
                date: '',
            },
            rules : {
                time_out: [
                    { required: true, message: 'Please input time out', trigger: 'blur' }
                ],
            },
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                this.updateAttendance();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async updateAttendance() {
            try {
                const res = await this.$API.Attendance.updateAttendance(this.model.id, this.form);
                this.$EventDispatcher.fire('UPDATE_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Time Out',
                    type: 'success'
                });
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>
