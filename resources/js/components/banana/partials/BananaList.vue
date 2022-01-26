<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-select
                v-model="area"
                filterable
                style="width:30%; margin-bottom: 10px"
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
            <line-chart :chartdata="chartData" v-if="!loadingData" :options="chartOptions"></line-chart>

            <h3>RECORDS</h3>
            <el-tabs type="border-card" v-if="!loadingData">
                <el-tab-pane v-for="(report, index) in bananaReportByYear" :key="index" :label="index">
                    <data-report-list :report="report"></data-report-list>
                </el-tab-pane>
            </el-tabs>
        </div>
    </el-card>
</template>
<script>
    import LineChart from './LineChart.vue'
    export default {
        name: 'BananaList',
        components: {
            LineChart
        },
        data() {
            return {
                area: "Search Area",
                bananaReport: {
                    months: [],
                    stem_cut: []
                },
                chartData: {
                    labels: [],
                    datasets: [{
                        label: 'Bar Chart',
                        borderWidth: 1,
                        backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                        ],
                        pointBorderColor: '#2554FF',
                        data: []
                    }]
                },
                chartOptions: {
                    scales: {
                        yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: true
                        }
                        }],
                        xAxes: [{
                        gridLines: {
                            display: false
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                    responsive: true,
                    maintainAspectRatio: false
                },
                loadingData: false,
                loadingArea: false,
                areas: [],
                loadingDataByYear: false,
                bananaReportByYear: []
            }
        },
        async created() {
            await this.getBananaReport()
        },
        methods: {
            async getBananaReport() {
                try {
                    this.loadingData = true
                    const res = await this.$API.Report.getBananaReport(this.area);
                    this.bananaReport = res.data
                    this.chartData.labels = this.bananaReport.months
                    this.chartData.datasets[0].data = this.bananaReport.stem_cut
                    this.loadingData = false
                    await this.getData()
                } catch (error) {
                    console.log(error)
                }
            },
            async getData() {
                try {
                    this.loadingDataByYear = true
                    const res = await this.$API.Report.getBananaReportByYear(this.area)
                    this.bananaReportByYear = (res.data);
                    this.loadingDataByYear = false
                } catch (error) {
                    console.log(error);
                }
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
                this.area = value;
                this.getBananaReport()
            },
        }
    }
</script>
