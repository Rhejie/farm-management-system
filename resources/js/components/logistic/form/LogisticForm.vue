<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Bud Injection" prop="bud_injection_x1">
                    <el-input v-model="form.bud_injection_x1" type="number" placeholder="Bud Injection"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Bagging Report" prop="bagging_report_x2">
                    <el-input v-model="form.bagging_report_x2" type="number" placeholder="Bagging Report"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Stem Cut" prop="stem_cut_y">
                    <el-input v-model="form.stem_cut_y" type="number" placeholder="Stem Cut"></el-input>
                </el-form-item>
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
                bagging_report_x2: '',
                stem_cut_y: '',
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
                name: this.model.name,
                daily_rate: this.model.daily_rate,
                description: this.model.description,
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateTask();
                    return;
                }

                this.storeTask();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async storeTask() {
            try {
                const res = await this.$API.Task.storeTask(this.form)
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
        async updateTask() {
            try {
                const res = await this.$API.Task.updateTask(this.model.id, this.form)
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
                    id: newVal.id,
                    name: newVal.name,
                    daily_rate: newVal.daily_rate,
                    description: newVal.description,
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    name: ''
                }
            }
        }
    }
}
</script>
