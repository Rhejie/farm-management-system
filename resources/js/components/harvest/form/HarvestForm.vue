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
                        :loading="loadingArea">
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
                <el-form-item label="Date" prop="date">
                    <el-date-picker
                        v-model="form.date"
                        type="date"
                        style="width:100%"
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        placeholder="Select date">
                    </el-date-picker>
                </el-form-item>
            </div>
            <div class="col-md-6">
                <el-form-item label="Stem Cut" prop="stem_cut">
                    <el-input v-model="form.stem_cut" type="number" placeholder="Quantity"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item style="float:right">
                    <el-button type="primary" @click="submitForm('form')">Save</el-button>
                    <el-button @click="resetForm('form')">Reset</el-button>
                </el-form-item>
            </div>
        </div>
    </el-form>
</template>
<script>
export default {
    name: 'HarvestForm',
    props: {
        mode: null,
        model: {}
    },
    data() {
        return {
            areas: [],
            loadingArea: false,
            form: {
                stem_cut: '',
                area_id: '',
                date: ''
            },
            rules : {
                stem_cut: [
                    { required: true, message: 'Please input stem cut', trigger: 'blur' }
                ],
                date: [
                    { required: true, message: 'Please input date', trigger: 'blur' }
                ],
                area_id: [
                    { required: true, message: 'Please select item', trigger: 'blur' }
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
                area_id: `${this.model.area.name}`,
                area_id_id: this.model.arae_id,
                stem_cut: this.model.stem_cut,
                date: this.model.date,
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateHarvest();
                    return;
                }

                this.storeHarvest();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodArea(query) {
            try {
                if(query !== '') {
                    this.loadingArea = true;
                    const res = await this.$API.Area.searchArea(query);
                    this.areas = res.data;
                    this.loadingArea = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        changeArea(value) {
            this.form.area_id_id = null;
            this.form.area_id = value;
        },
        async storeHarvest() {
            try {
                this.form.date = this.$df.formatDate(this.form.date, "YYYY-MM-DD")
                const res = await this.$API.Harvest.storeHarvest(this.form);
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
        async updateHarvest() {
            try {
                this.form.date = this.$df.formatDate(this.form.date, "YYYY-MM-DD")
                const res = await this.$API.Harvest.updateHarvest(this.model.id, this.form);
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
                    area_id: `${newVal.area.name}`,
                    area_id_id: newVal.arae_id,
                    stem_cut: newVal.stem_cut,
                    date: newVal.date,
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    stem_cut: '',
                    area_id: '',
                    date: ''
                }
            }
        }
    }
}
</script>
