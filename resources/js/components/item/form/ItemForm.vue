<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name" placeholder="Name"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Category" prop="category_id">
                    <el-select
                        v-model="form.category_id"
                        filterable
                        style="width:100%"
                        remote
                        reserve-keyword
                        @change="changeCategory"
                        placeholder="Please enter a keyword to search category"
                        :remote-method="remoteMethodCategories"
                        :loading="loading">
                            <el-option
                                v-for="item in categories"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                    </el-select>
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
    name: 'ItemForm',
    props: {
        mode: null,
        model: {}
    },
    data() {
        return {
            form: {
                name: '',
                category_id: ''
            },
            rules : {
                name: [
                    { required: true, message: 'Please input item name', trigger: 'blur' }
                ],
                category_id: [
                    { required: true, message: 'Please select category', trigger: 'blur' }
                ],
            },
            categories: [],
            loading: false,
        }
    },
    created() {
        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })

        if(this.model && this.model.id) {
            this.form = {
                name: this.model.name,
                category_id: this.model.category.name,
                category_id_id: this.model.category_id,
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateItem();
                    return;
                }

                this.storeItem();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodCategories(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Warehouse.searchCategory(query);
                    this.categories = res.data;
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeCategory(value) {
            this.form.category_id_id = null;
            this.form.category_id = value;
        },
        async storeItem() {
            try {
                const res = await this.$API.Warehouse.storeItem(this.form);
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
        async updateItem() {
            try {
                const res = await this.$API.Warehouse.updateItem(this.model.id, this.form);
                this.$EventDispatcher.fire('UPDATE_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully added',
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
                    name: newVal.name,
                    category_id: newVal.category.name,
                    category_id_id: newVal.category_id
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    name: '',
                    category_id: ''
                }
            }
        }
    }
}
</script>
