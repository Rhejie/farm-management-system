<template>
    <div>
        <div class="content-wrapper" v-loading="loading">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">EMPLOYEE DETAILS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="#/employees">Employees</a></li>
                            <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3" style="text-align:center">
                                    <img v-if="profile.profile" :src="`storage/${profile.profile_path}/${profile.profile}`" alt="Profile">
                                    <img v-else :src="`/img/profile-default.png`" style="width: 100%" alt="profile">
                                    <p>Profile</p>
                                </div>
                                <div class="col-md-9">
                                    <el-card class="box-card">
                                        <div  class="text item">
                                            <el-descriptions :column="2" title="INFORMATION" style="font-size: 30px">
                                                <el-descriptions-item label="First Name">{{profile.firstname}}</el-descriptions-item>
                                                <el-descriptions-item label="Last Name">{{profile.lastname}}</el-descriptions-item>
                                                <el-descriptions-item label="Middle Name">{{profile.middlename}}</el-descriptions-item>
                                                <el-descriptions-item label="Suffix">{{profile.suffix}}</el-descriptions-item>
                                            </el-descriptions>
                                        </div>
                                    </el-card>
                                </div>
                                <div class="col-md-12">
                                    <el-card class="box-card">
                                        <div  class="text item">
                                            <el-descriptions :column="3" style="font-size: 30px">
                                                <el-descriptions-item label="Gender">{{profile.gender}}</el-descriptions-item>
                                                <el-descriptions-item label="Birthday">{{profile.birthday}}</el-descriptions-item>
                                                <el-descriptions-item label="Contact">{{profile.contact}}</el-descriptions-item>
                                                <el-descriptions-item label="Position">{{profile.position}}</el-descriptions-item>
                                                <el-descriptions-item label="Address">{{profile.address}}</el-descriptions-item>
                                            </el-descriptions>
                                        </div>
                                    </el-card>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
export default {
    name: 'EmplpyeeDetails',
    props: {
        id: null,
        model: {}
    },
    data() {
        return {
            profile: {},
            loading: false,
        }
    },
    created() {
        if(this.model && this.model.id) {
            this.profile = this.model
        }
        else {
            this.getProfile()
        }
    },
    computed: {
        name() {
            return this.profile.lastname+', '+this.profile.firstname
        }
    },
    methods: {
        async getProfile() {
            try {
                this.loading = true
                const res = await this.$API.Employee.getProfile(this.id);
                this.profile = res.data
                this.loading = false
            } catch (error) {
                console.log(error);
            }
        }
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal != oldVal) {
                this.profile = newVal
            }
        }
    }
}
</script>
<style lang="scss" scoped>
    .el-descriptions-item__content {
        font-size: 30px !important;
    }

    .el-descriptions-item__label{
        font-size: 30px !important;
        font-weight: 300 !important
    }
</style>
