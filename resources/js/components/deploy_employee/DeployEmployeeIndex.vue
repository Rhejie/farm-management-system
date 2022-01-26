<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">MANAGE DEPLOY TEAM</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Deploy</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <el-tabs type="border-card">
                        <el-tab-pane label="Deployment">
                            <deployement-list></deployement-list>
                        </el-tab-pane>
                        <el-tab-pane label="Areas" lazy>
                            <deploy-area></deploy-area>
                        </el-tab-pane>
                        <el-tab-pane label="Deployed Teams" lazy>
                            <deploy-employee-list></deploy-employee-list>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
export default {
    name: 'AreaIndex',
    data() {
        return {

        }
    },
    created() {

    },
}
</script>
