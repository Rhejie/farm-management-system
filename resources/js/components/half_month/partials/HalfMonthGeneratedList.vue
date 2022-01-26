<template>
    <el-card class="box-card" style="margin-top: 10px">
        <div  class="text item">
            <div class="row">
                <div class="col-md-12" style="padding:5px">
                    <el-button @click="generateReport" style="float:right; " type="primary" v-if="reports.length">Publish</el-button>
                </div>
            </div>
            <el-table
                v-loading="loading"
                element-loading-text="Loading..."
                element-loading-spinner="el-icon-loading"
                element-loading-background="rgba(0, 0, 0, 0.8)"
                :data="reports"
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
                                {{scope.row.date | fileDate}}
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
                        label="DATA">
                            <template slot-scope="scope">
                                <el-input v-model="scope.row.data"></el-input>
                                <div class="invalid-feedback_item" v-if="error.length > 0 && scope.$index === error[0].index">
                                    {{error[0].message}}
                                </div>
                            </template>
                    </el-table-column>
            </el-table>
        </div>
    </el-card>
</template>
<script>
import moment from 'moment'
export default {
    name: 'HalfMonthGeneratedList',
    data() {
        return {
            reports: [],
            loading: true,
            employee: {},
            rate: null,
            error: [],
            report: {}
        }
    },
    created() {

        this.$EventDispatcher.listen('RESET_HALFMONT', data => {
            this.loading = true
        })

        this.$EventDispatcher.listen('NEW_GENERATE_REPORT', data => {

            this.reports = data.reports
            this.report = data.data
            this.loading = false

        });
    },
    filters: {
        timeFormat(value) {
            if(value) {
                return moment(value, 'HH:mm:ss').format('h:mm a')
            }
            return '-'
        },
        fileDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('YYYY-MMM-DD')
            }
        }
    },
    methods: {
        async generateReport() {
            try {
                this.reports.forEach((re, index) => {
                    let data = re.hasOwnProperty('data')
                    if(!data) {
                        this.error.push({message: 'Please Input data', index})
                        return;
                    }
                    else {
                        if(re.data == '') {
                            this.error.push({message: 'Please Input data', index})
                            return
                        }
                    }
                })

                this.report.tasks = this.reports
                const res = await this.$API.Month.storeReport(this.report);
                this.$EventDispatcher.fire('NEW_PAYROLL', res.data)
                this.reports = []
                this.loading = true
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Generate Payroll',
                    type: 'success'
                });
            } catch (error) {
                console.log(error);
            }
        },
        changeRate(value) {

            this.reports = this.reports.map(pay => {
                let day_total = 0;
                if(pay.total_hours >= 9) {
                    day_total = 9
                }
                else {
                    day_total = pay.total_hours
                }
                let day = parseFloat(day_total) / 9
                pay.rate = day * value;
                pay.rate = pay.rate.toFixed(2)

                return pay
            })
        },
        handleDoublePay(data) {
            data.rate = 1
        }
    }
}
</script>
<style lang="scss">
    .invalid-feedback_item {
        color: #EC3434;
        font-size: 11px;
    }
</style>
