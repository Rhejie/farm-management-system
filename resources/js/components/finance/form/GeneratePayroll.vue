<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">GENERATE PAYROLL</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="/payroll#/payrolls">Payroll</a></li>
                            <li class="breadcrumb-item active">Generate</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <el-card class="box-card" style="margin-bottom: 10px">
                        <div  class="text item">
                            <generate-payroll-form></generate-payroll-form>
                        </div>
                    </el-card>
                    <el-card class="box-card">
                        <div  class="text item">
                            <generate-payroll-list></generate-payroll-list>
                        </div>
                    </el-card>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
export default {
    name: 'GeneratePayroll',
    data() {
        return {

        }
    },
    created() {

    },
}
</script>
