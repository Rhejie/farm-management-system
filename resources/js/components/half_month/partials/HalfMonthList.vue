<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" type="primary" @click="generateHalfMonth" style="float:right;">Add Report</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-table
                :data="reports"
                v-loading="loading"
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
                        prop="from_date"
                        label="FROM"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.from_date | filterDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="to_date"
                        label="TO"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.to_date | filterDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="date"
                        label="REPORT GENERATED"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.date | filterDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="120"
                        label="ACTION">
                        <template slot-scope="scope">
                            <!--a :href="`finance/payslip/${scope.row.id}`" class="btn btn-info btn-sm" target="_blank">Payslip</!--a-->
                            <button @click="handleView(scope.row)" class="btn btn-success btn-sm">View</button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="reportsPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
import moment from 'moment'
export default {
    name: 'HalfMonthList',
    mixins: [paginate],
    data() {
        return {
            reports: [],
            reportsPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            date: '',
        }
    },
    created() {
        this.date = new Date();
        this.getReports()
    },
    filters: {
        filterDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('YYYY-MMM-DD');
            }
        }
    },
    methods: {
        async getReports() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Month.getReports(params);
                this.reports = res.data.data;
                this.reportsPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        generateHalfMonth() {
            this.$router.push({name: 'Generate Half Month'})
        },
        handleView(data) {
            this.$router.push({name : 'Report Details', params: {model: data, id: data.id} })
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getReports();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getReports();
        },
        applySearch() {
            this.getReports();
        },
        changeDate(value) {
            this.date = value
            this.getReports();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getReports();
            }
        }
    }

}
</script>
