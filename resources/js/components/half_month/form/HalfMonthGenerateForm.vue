<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-form :inline="true" :model="form" :rules="rules" ref="form" class="demo-form-inline">
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
    </el-card>
</template>
<script>
export default {
    name: 'HalfMonthGenerateForm',
    data() {
        return {
            form: {
                date_from: '',
                date_to: ''
            },
            rules : {
                date_from: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
                date_to: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
            },
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
                this.generateReport();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$EventDispatcher.fire('RESET_HALFMONT', 'reset')
            this.$refs[formName].resetFields();
        },
        async generateReport() {
            try {
                this.loadingGenerate = true
                this.form.date_to = this.$df.formatDate(this.form.date_to, "YYYY-MM-DD")
                this.form.date_from = this.$df.formatDate(this.form.date_from, "YYYY-MM-DD")
                const res = await this.$API.Month.generateReport(this.form);

                if(res.data == 'already') {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Already Generated'
                    });
                    this.loadingGenerate = false

                    return;
                }
                this.loadingGenerate = false

                this.$EventDispatcher.fire('NEW_GENERATE_REPORT', res.data);
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
