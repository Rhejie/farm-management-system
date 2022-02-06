<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <el-form-item label="Bud Injection" prop="bud_injection_x1">
                            <el-input v-model="form.bud_injection_x1" type="number" placeholder="Bud Injection"></el-input>
                        </el-form-item>
                    </div>
                    <div class="col-md-6">
                        <el-form-item label="Bud Injection Date" prop="bu_injection_date">
                            <el-date-picker
                                v-model="form.bu_injection_date"
                                type="date"
                                style="width:100%"
                                format="yyyy-MM-dd"
                                value-format="yyyy-MM-dd"
                                placeholder="Select date">
                            </el-date-picker>
                        </el-form-item>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <el-form-item label="Bagging Report" prop="bagging_report_x2">
                            <el-input v-model="form.bagging_report_x2" type="number" placeholder="Bagging Report"></el-input>
                        </el-form-item>
                    </div>
                    <div class="col-md-6">
                        <el-form-item label="Bagging Report Date" prop="bagging_report_date">
                            <el-date-picker
                                v-model="form.bagging_report_date"
                                type="date"
                                style="width:100%"
                                format="yyyy-MM-dd"
                                value-format="yyyy-MM-dd"
                                placeholder="Select date">
                            </el-date-picker>
                        </el-form-item>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <el-form-item label="Stem Cut" prop="stem_cut_y">
                            <el-input v-model="form.stem_cut_y" type="number" placeholder="Stem Cut"></el-input>
                        </el-form-item>
                    </div>
                    <div class="col-md-6">
                        <el-form-item label="Stem Cut Date" prop="stem_cut_y_date">
                            <el-date-picker
                                v-model="form.stem_cut_y_date"
                                type="date"
                                style="width:100%"
                                format="yyyy-MM-dd"
                                value-format="yyyy-MM-dd"
                                placeholder="Select date">
                            </el-date-picker>
                        </el-form-item>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <el-form-item style="float:right">
                    <el-button type="primary" @click="submitForm('form')">Submit</el-button>
                    <el-button @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </div>
        </div>
    </el-form>
</template>
<script>
export default {
    name: 'LogisticForm',
    props: {
        model: {},
        mode: null
    },
    data() {
        return {
            form: {
                bud_injection_x1: '',
                bu_injection_date: '',
                bagging_report_x2: '',
                bagging_report_date: '',
                stem_cut_y: '',
                stem_cut_y_date: ''
            },
            rules : {
                bud_injection_x1: [
                    { required: true, message: 'Please input bud injection', trigger: 'blur' }
                ],
                // bagging_report_x2: [
                //     { required: true, message: 'Please input bagging report', trigger: 'blur' }
                // ],
                // stem_cut_y: [
                //     { required: true, message: 'Please input stem cut', trigger: 'blur' }
                // ],
            },
        }
    },
    created() {
        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })

        if(this.model && this.model.id) {
            this.form = {
                bud_injection_x1: this.model.bud_injection_x1,
                bu_injection_date: this.model.bu_injection_date,
                bagging_report_x2: this.model.bagging_report_x2,
                bagging_report_date: this.model.bagging_report_date,
                stem_cut_y: this.model.stem_cut_y,
                stem_cut_y_date: this.model.stem_cut_y_date
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateLogistic();
                    return;
                }

                this.storeLogistic();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async storeLogistic() {
            try {
                this.form.bu_injection_date = this.$df.formatDate(this.form.bu_injection_date, "YYYY-MM-DD")
                this.form.bagging_report_date = this.$df.formatDate(this.form.bagging_report_date, "YYYY-MM-DD")
                this.form.stem_cut_y_date = this.$df.formatDate(this.form.stem_cut_y_date, "YYYY-MM-DD")
                const res = await this.$API.Logistic.storeLogistic(this.form)
                this.$EventDispatcher.fire('NEW_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully added',
                    type: 'success'
                });
                this.resetForm('form');
            } catch (error) {
                console.log(error);
            }
        },
        async updateLogistic() {
            try {
                this.form.bu_injection_date = this.$df.formatDate(this.form.bu_injection_date, "YYYY-MM-DD")
                this.form.bagging_report_date = this.$df.formatDate(this.form.bagging_report_date, "YYYY-MM-DD")
                this.form.stem_cut_y_date = this.$df.formatDate(this.form.stem_cut_y_date, "YYYY-MM-DD")
                const res = await this.$API.Logistic.updateLogistic(this.model.id, this.form)
                this.$EventDispatcher.fire('UPDATE_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully updated',
                    type: 'success'
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal != oldVal) {
                this.form = {
                    bud_injection_x1: newVal.bud_injection_x1,
                    bu_injection_date: newVal.bu_injection_date,
                    bagging_report_x2: newVal.bagging_report_x2,
                    bagging_report_date: newVal.bagging_report_date,
                    stem_cut_y: newVal.stem_cut_y,
                    stem_cut_y_date: newVal.stem_cut_y_date
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    bud_injection_x1: '',
                    bu_injection_date: '',
                    bagging_report_x2: '',
                    bagging_report_date: '',
                    stem_cut_y: '',
                    stem_cut_y_date: ''
                }
            }
        }
    }
}
</script>
