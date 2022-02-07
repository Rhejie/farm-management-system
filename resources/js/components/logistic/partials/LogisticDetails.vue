<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">LOGISTIC REGRESSION DETAILS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="/logistic-regression#/logistics">Logistics</a></li>
                            <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <el-select v-model="area_id" @change="areaChange" clearable="" placeholder="Select Area">
                        <el-option
                            v-for="item in areas"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                        </el-option>
                    </el-select>

                    <el-card class="box-card">
                        <div  class="text item" >
                            <h3>Expeted</h3>
                            <el-table
                                :data="dataGraph"
                                v-loading="loadinglogistics"
                                style="width: 100%">
                                    <el-table-column
                                        width="70"
                                        label="No."
                                        :sortable="true">
                                            <template slot-scope="scope">
                                                {{scope.$index + 1}}
                                            </template>
                                    </el-table-column>
                                    <el-table-column
                                        prop="month"
                                        label="MONTH"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="bud_injection_x1"
                                        label="BUD INJECTION (X1)"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="bagging_report_x2"
                                        label="BAGGING REPORT (X2)"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="stem_cut_y"
                                        label="STEM CUT (Y)"
                                        :sortable="true">
                                    </el-table-column>
                            </el-table>
                            <h3>Data</h3>
                            <el-table
                                :data="logistics"
                                v-loading="loadinglogistics"
                                style="width: 100%">
                                    <el-table-column
                                        width="70"
                                        label="No."
                                        :sortable="true">
                                            <template slot-scope="scope">
                                                {{scope.$index + 1}}
                                            </template>
                                    </el-table-column>
                                    <el-table-column
                                        prop="bud_injection_x1"
                                        label="BUD INJECTION (X1)"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="bu_injection_date"
                                        label="BUD INJECTION DATE"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="bagging_report_x2"
                                        label="BAGGING REPORT (X2)"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="bagging_report_date"
                                        label="BAGGING REPORT DATE"
                                        :sortable="true">
                                    </el-table-column>
                                    <el-table-column
                                        prop="y"
                                        label="STEM CUT (Y)"
                                        :sortable="true">
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
export default {
    name: 'LogisticDetails',
    data() {
        return {
            areas: [],
            area_id: null,
            logistics: [],
            loadinglogistics: false,
            graphx1: [],
            graphx2: [],
            graphy: [],
            dataGraph: []
        }
    },
    created() {
        this.getAreas();
    },
    methods: {
        async getLogistics() {
            try {
                if(this.area_id) {
                    this.loadinglogistics = true
                    const res = await this.$API.Logistic.getData(this.area_id)
                    this.logistics = res.data
                    this.loadinglogistics = false
                    return
                }

                this.logistics = []
            } catch (error) {
                console.log(error);
            }
        },
        async getDataGraph() {
            try {
                const res = await this.$API.Logistic.getDataGraph(this.area_id)
                Object.keys(res.data.x1).forEach(key => this.graphx1.push({
                    month: key,
                    value: res.data.x1[key]
                }));
                Object.keys(res.data.x2).forEach(key => this.graphx2.push({
                    month: key,
                    value: res.data.x2[key]
                }));
                Object.keys(res.data.y).forEach(key => this.graphy.push({
                    month: key,
                    value: res.data.y[key]
                }));

                this.graphx1.forEach(x1 => {
                    this.graphx2.forEach(x2 => {
                        if(x1.month == x2.month) {
                            this.dataGraph.push({'month' : x1.month , 'bud_injection_x1': x1.value, 'bagging_report_x2' : x2.value});
                        }
                    })
                })

                this.dataGraph.map(d => {
                    this.graphy.forEach(y => {
                        if(y.month == d.month) {
                            d.stem_cut_y = y.value;
                        }
                    })
                })
            } catch (error) {
                console.log('====================================');
                console.log(error);
                console.log('====================================');
            }
        },
        async getAreas() {
            try {
                const res = await this.$API.Area.getAllAreas();
                this.areas = res.data
            } catch (error) {
                console.log(error);
            }
        },
        areaChange(value) {
            this.area_id = value;
            this.getLogistics();
            this.getDataGraph();
        },
    },
}
</script>
