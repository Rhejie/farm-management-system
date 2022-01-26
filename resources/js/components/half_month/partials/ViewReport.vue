<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">REPORTS DETAILS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="/half-month#/reports">Reports</a></li>
                            <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid" v-loading="loading">
                    <el-card class="box-card">
                        <div  class="text item">
                            <h2>From: {{report.from_date | filterDate}} To: {{report.to_date | filterDate}}</h2>
                        </div>
                    </el-card>

                    <el-card class="box-card">
                        <div  class="text item">
                            <el-table
                                v-loading="loading"
                                element-loading-text="Loading..."
                                element-loading-spinner="el-icon-loading"
                                element-loading-background="rgba(0, 0, 0, 0.8)"
                                :data="report.tasks"
                                style="width: 100%">
                                    <el-table-column type="expand">
                                        <template slot-scope="props">
                                            <div class="img_profile">
                                                <h4>MEMBERS</h4>
                                                <el-table
                                                    :data="props.row.members"
                                                    style="width: 100%">
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
                                                    <el-table-column
                                                        prop="position"
                                                        label="POSITION">
                                                    </el-table-column>
                                                </el-table>
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                        prop="date"
                                        label="DATE">
                                            <template slot-scope="scope">
                                                {{scope.row.date | filterDate}}
                                            </template>
                                    </el-table-column>
                                    <el-table-column
                                        prop="task.name"
                                        label="TASK">
                                    </el-table-column>
                                    <el-table-column
                                        prop="team.name"
                                        label="TEAM">
                                    </el-table-column>
                                    <el-table-column
                                        prop="area.name"
                                        label="AREA">
                                    </el-table-column>

                                    <el-table-column
                                        prop="data"
                                        label="DATA">
                                    </el-table-column>
                            </el-table>
                        </div>
                    </el-card>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    name: 'ReportDetails',
    props: {
        id: null,
        model: {}
    },
    data() {
        return {
            report: {},
            loading: false,
        }
    },
    created() {
        if(this.model && this.model.id) {
            this.report = this.model
        }
        else {
            this.getReport()
        }
    },
    filters: {
        filterDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('YYYY-MMM-DD');
            }
        }
    },
    methods: {
        async getReport() {
            try {
                this.loading = true
                const res = await this.$API.Month.getReportByID(this.id);
                this.report = res.data
                this.loading = false
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
