<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
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
                        :loading="loading">
                            <el-option
                                v-for="item in areas"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div class="col-md-6">
                <el-form-item label="Quantity" prop="quantity">
                    <el-input v-model="form.quantity" style="width:100%"  type="number" placeholder="Name"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-6">
                <el-form-item label="Date" prop="date">
                    <el-date-picker
                        v-model="form.date"
                        type="datetime"
                        style="width:100%"
                        format="yyyy-MM-dd h:mm a"
                        value-format="yyyy-MM-dd h:mm a"
                        placeholder="Select date">
                    </el-date-picker>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item style="float:right">
                    <el-button type="primary" @click="submitForm('form')">Deploy</el-button>
                    <el-button @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </div>
        </div>
    </el-form>
</template>
<script>
import moment from "moment"
export default {
    name: 'DeployStockForm',
    props: {
        mode: null,
        model: {}
    },
    data() {
        var checkQuantity = (rule, value, callback) => {
            if (!value) {
                return callback(new Error('Please input the age'));
            }
            else {
                let quantity = parseFloat(this.model.quantity);
                if(value > quantity) {
                    callback(new Error('Out of stuck! ('+quantity+')'));
                }
                else {
                    callback();
                }
            }
        };
        return {
            form : {
                stock_id: '',
                item_id: '',
                area_id: '',
                quantity: '',
                date: ''
            },
            rules : {
                area_id: [
                    { required: true, message: 'Please select area', trigger: 'blur' }
                ],
                quantity: [
                    { validator: checkQuantity, trigger: 'blur' }
                ],
            },
            areas: [],
            loading: false
        }
    },
    created() {
        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })
        this.form.date = new Date()

        if(this.model && this.model.id && this.model.from == 'stock') {
            let date = new Date()
            this.form = {
                stock_id: this.model.id,
                item_id: this.model.item_id,
                date,
                area_id: '',
                quantity: '',
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

                this.storeDeploy();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async storeDeploy() {
            try {
                this.form.date = this.$df.formatDate(this.form.date, "YYYY-MM-DD H:mm:ss ")
                const res = await this.$API.Warehouse.storeDeploy(this.form);
                this.$EventDispatcher.fire('DEPLOY_STOCK', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deploy',
                    type: 'success'
                });
                this.resetForm('form');
            } catch (error) {
                console.log(error);
            }
        },
        async remoteMethodArea(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Area.searchArea(query);
                    this.areas = res.data;
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeArea(value) {
            this.form.area_id_id = null;
            this.form.area_id = value;
        },
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal != oldVal && newVal.from == 'stock') {
                let date = new Date()
                this.form = {
                    stock_id: newVal.id,
                    item_id: newVal.item_id,
                    date,
                    area_id: '',
                    quantity: '',
                }
            }
        }
    }
}
</script>
