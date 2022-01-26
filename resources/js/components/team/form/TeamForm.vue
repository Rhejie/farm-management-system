<template>
    <el-form :model="form" :rules="rules" ref="form" label-position="top" label-width="120px" class="demo-ruleForm">
        <div class="row">
            <div class="col-md-12">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name" placeholder="Name"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Descritpion" prop="description">
                    <el-input v-model="form.description" type="textarea" placeholder="Description"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-form-item label="Employee" prop="employee_id">
                    <el-select
                        v-model="form.employee_id"
                        filterable
                        remote
                        style="width:100%"
                        reserve-keyword
                        @change="attendanceChange"
                        placeholder="Please enter a keyword to search employee"
                        :remote-method="remoteMethodEmployee"
                        :loading="loading">
                            <el-option
                                v-for="item in employees"
                                :key="item.id"
                                :label="`${item.lastname}, ${item.firstname} ${item.middlename}`"
                                :value="item.id">
                            </el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div class="col-md-12">
                <el-table
                    :data="selectedMemebers"
                    style="width: 100%; margin-bottom:10px">
                    <el-table-column
                        prop="firstname"
                        label="FIRSTNAME">
                    </el-table-column>
                    <el-table-column
                        prop="lastname"
                        label="LASTNAME">
                    </el-table-column>
                    <el-table-column
                        prop="middlename"
                        label="MIDDLENAME">
                    </el-table-column>
                    <el-table-column>
                        <template slot-scope="scope">
                            <el-button type="danger" @click="deleteMemember(scope.$index)">Del</el-button>
                        </template>
                    </el-table-column>
                </el-table>
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
    name: 'TeamForm',
    props: {
        mode: null,
        model: {}
    },
    data() {
        return {
            form: {
                name: '',
                description: ''
            },
            rules : {
                name: [
                    { required: true, message: 'Please input team name', trigger: 'blur' }
                ],
            },
            loading: false,
            employees: [],
            selectedMemebers: []
        }
    },
    created() {
        this.$EventDispatcher.listen('CLOSE_MODAL', () => {
            this.resetForm('form');
        })

        if(this.model && this.model.id) {
            this.form = {
                name: this.model.name,
                description: this.model.description,
            }
            this.selectedMemebers = this.model.employees
        }
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.mode == 'update') {
                    this.updateTeam();
                    return;
                }

                this.storeTeam();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async remoteMethodEmployee(query) {
            try {
                if(query !== '') {
                    this.loading = true;
                    const res = await this.$API.Employee.searchEmployee(query);
                    this.employees = res.data
                    this.loading = false;
                }
            } catch (error) {
                console.log(error);
            }
        },
        attendanceChange(value) {
            let member = this.employees.find(emp => emp.id == value);
            this.selectedMemebers.unshift(member);
            this.employees = []
            this.form.employee_id = null
        },
        deleteMemember(index) {
            this.selectedMemebers.splice(index, 1)
        },
        async storeTeam() {
            try {
                this.form.employees = this.selectedMemebers
                const res = await this.$API.Team.storeTeam(this.form);
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
        async updateTeam() {
            try {
                this.form.employees = this.selectedMemebers

                const res = await this.$API.Team.updateTeam(this.model.id, this.form)
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
                    description: newVal.description,
                }
                this.selectedMemebers = newVal.employees
            }
        },
        mode(val) {
            if(val && val == 'create') {
                this.form = {
                    name: '',
                    description: '',
                }

                this.selectedMemebers = []
            }
        }
    }
}
</script>
