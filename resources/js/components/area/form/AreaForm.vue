<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name" placeholder="Name"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Status" v-if="mode == 'update'">
                    <el-select v-model="form.status" placeholder="please select your zone">
                        <el-option label="Draft" value="Draft"></el-option>
                        <el-option label="Publish" value="Publish"></el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Color" prop="color">
                    <el-color-picker v-model="form.color" style="width:100%" show-alpha></el-color-picker>
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
    name: 'AreaForm',
    props: {
        model: {},
        mode: null
    },
    data() {
        return {
            map: null,
            form: {
                name: '',
                status: '',
                color: ''
            },
            rules : {
                name: [
                    { required: true, message: 'Please input category name', trigger: 'blur' }
                ],
                color: [
                    { required: true, message: 'Please input select color', trigger: 'blur' }
                ],
            },
            loadingMap: false
        }
    },
    created() {

        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })

        if(this.model && this.model.id) {
            this.form = {
                name: this.model.name,
                status: this.model.status,
                color: this.model.color,
            }
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateArea();
                    return;
                }

                this.storeArea();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async getLocation() {
            try {
                this.loadingMap = true;
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.form.coordinates.lat = parseFloat(parseFloat(coordinates.lat).toFixed(3));
                this.form.coordinates.lng = parseFloat(parseFloat(coordinates.lng).toFixed(3));
                this.noLocation = false;
                this.loadingMap = false;
            } catch (error) {
                console.log(error);
            }
        },
        async storeArea() {
            try {
                const res = await this.$API.Area.storeArea(this.form)
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
        async updateArea() {
            try {
                const res = await this.$API.Area.updateArea(this.model.id, this.form)
                this.$EventDispatcher.fire('UPDATE_DATA', res.data);
                this.$notify({
                    title: 'Success',
                    message: 'Successfully updated',
                    type: 'success'
                });
            } catch (error) {
                console.log(error);
            }
        },
        getPosition(marker) {
            return {
                lat: parseFloat(marker.lat),
                lng: parseFloat(marker.lng)
            }
        },
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal != oldVal) {
                this.form = {
                    name: newVal.name,
                    status: newVal.status,
                    color: newVal.color,
                }
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    name: '',
                    status: '',
                    color: '',
                }
            }
        }
    }
}
</script>
