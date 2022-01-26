<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name" placeholder="Name"></el-input>
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
    name: 'CategoryForm',
    props: {
        model: {},
        mode: null
    },
    data() {
        return {
            form: {
                name: '',
            },
            rules : {
                name: [
                    { required: true, message: 'Please input category name', trigger: 'blur' }
                ],
            },
        }
    },
    created() {
        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })

        if(this.model && this.model.id) {
            this.form = {
                name: this.model.name
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateCategory();
                    return;
                }

                this.storeCategory();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async storeCategory() {
            try {
                const res = await this.$API.Warehouse.storeCategory(this.form)
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
        async updateCategory() {
            try {
                const res = await this.$API.Warehouse.updateCategory(this.model.id, this.form)
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
