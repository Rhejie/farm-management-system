<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Item" prop="item_id">
                    <el-select
                        v-model="form.item_id"
                        filterable
                        style="width:100%"
                        remote
                        disabled
                        reserve-keyword
                        @change="changeCategory"
                        placeholder="Please enter a keyword to search item"
                        :remote-method="remoteMethodItem"
                        :loading="loading">
                            <el-option
                                v-for="item in items"
                                :key="item.id"
                                :label="`${item.name} (${item.category.name})`"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Quantity" prop="quantity">
                    <el-input v-model="form.quantity" type="number" placeholder="Quantity"></el-input>
                </el-form-item>

            </div>
            <div class="col-md-12">
                <el-form-item style="float:right">
                    <el-button type="primary" @click="submitForm('form')">Save</el-button>
                    <el-button  v-if="mode !== 'update'" @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </div>
        </div>
    </el-form>
</template>
<script>
export default {
    name: 'RestockForm',
    props: {
        mode: null,
        model: {}
    },
    data() {
        return {
            form: {
                quantity: '',
                item_id: ''
            },
            rules : {
                quantity: [
                    { required: true, message: 'Please input quantity', trigger: 'blur' }
                ],
                item_id: [
                    { required: true, message: 'Please select item', trigger: 'blur' }
                ],
            },
            items: [],
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
                item_id: `${this.model.item.name} (${this.model.item.category.name})`,
                item_id_id: this.model.item_id,
                quantity: ''
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateStock();
                    return;
                }

                this.storeStock();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodItem(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Warehouse.searchItem(query);
                    this.items = res.data;
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeCategory(value) {
            this.form.item_id_id = null;
            this.form.item_id = value;
        },
        async storeStock() {
            try {
                const res = await this.$API.Warehouse.storeStock(this.form);
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
        async updateStock() {
            try {
                const res = await this.$API.Warehouse.reStock(this.model.id, this.form);
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
                    item_id: `${newVal.item.name} (${newVal.item.category.name})`,
                    item_id_id: newVal.item_id,
                    quantity: ''
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    quantity: '',
                    item_id: ''
                }
            }
        }
    }
}
</script>
