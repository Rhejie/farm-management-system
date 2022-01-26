<template>
    <div>
        <div class="row">
            <div class="col-md-12" style="padding:10px">
                <div class="row">
                    <div class="col-md-6">
                        <span v-if="payroll.length > 0">Rate per Day:</span> <br/>
                        <el-input placeholder="Please input rate per day" @change="changeRate" style="width:100%" type="number" v-if="payroll.length > 0" v-model="rate"></el-input>
                    </div>
                    <div class="col-md-6" v-if="hasOvertime">
                        <span v-if="payroll.length > 0">Rate per hour for overtime:</span> <br/>
                        <el-input placeholder="Please input rate per hour for overtime" @change="changeRateOT" style="width:100%" type="number" v-if="payroll.length > 0" v-model="rate_ot"></el-input>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <el-table
                    v-loading="loading"
                    element-loading-text="Loading..."
                    element-loading-spinner="el-icon-loading"
                    element-loading-background="rgba(0, 0, 0, 0.8)"
                    :data="payroll"
                    style="width: 100%">
                        <el-table-column label="REGULAR HOURS">
                            <el-table-column
                                prop="date"
                                label="DATE">
                                    <template slot-scope="scope">
                                        {{scope.row.date | fileDate}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="status"
                                label="STATUS">
                                    <template slot-scope="scope">
                                        <template v-if="scope.row.status == 'Full'">
                                            <el-tag type="success" effect="dark">{{scope.row.status}}</el-tag>
                                        </template>
                                        <template v-if="scope.row.status == 'Under Time'">
                                            <el-tag type="primary" effect="dark">{{scope.row.status}}</el-tag>
                                        </template>
                                        <template v-if="scope.row.status == 'Half Day'">
                                            <el-tag type="warning" effect="dark">{{scope.row.status}}</el-tag>
                                        </template>
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="time_in"
                                label="IN">
                                    <template slot-scope="scope">
                                        {{scope.row.time_in | timeFormat}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="time_out"
                                label="OUT">
                                    <template slot-scope="scope">
                                        {{scope.row.time_out | timeFormat}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="rate"
                                label="RATE">

                            </el-table-column>
                        </el-table-column>
                            <!--<el-table-column
                                fixed="right"
                                width="110"
                                label="ACTION">
                                <template slot-scope="scope">
                                    <button :disabled="scope.row.is_double_pay" @click="handleDoublePay(scope.row)" class="btn btn-success btn-sm">Double Pay</button>
                                </template>
                            </el-table-column> -->
                </el-table>


                <el-table
                    v-loading="loading"
                    element-loading-text="Loading..."
                    element-loading-spinner="el-icon-loading"
                    element-loading-background="rgba(0, 0, 0, 0.8)"
                    :data="payroll.filter(pay => pay.ot_in != null && pay.ot_out != null && pay.ot_status == 'Approved')"
                    style="width: 100%; margin-top: 20px">
                        <el-table-column label="OVERTIME">
                            <el-table-column
                                prop="date"
                                label="DATE">
                                    <template slot-scope="scope">
                                        {{scope.row.date | fileDate}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="time_in"
                                label="IN">
                                    <template slot-scope="scope">
                                        {{scope.row.ot_in | timeFormat}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="time_out"
                                label="OUT">
                                    <template slot-scope="scope">
                                        {{scope.row.ot_out | timeFormat}}
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="total_hours_ot"
                                label="HOURS">

                            </el-table-column>
                            <el-table-column
                                prop="overtime_rate"
                                label="RATE">

                            </el-table-column>
                        </el-table-column>
                            <!--<el-table-column
                                fixed="right"
                                width="110"
                                label="ACTION">
                                <template slot-scope="scope">
                                    <button :disabled="scope.row.is_double_pay" @click="handleDoublePay(scope.row)" class="btn btn-success btn-sm">Double Pay</button>
                                </template>
                            </el-table-column> -->
                </el-table>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12" style="padding:10px">
                        <el-button @click="addDeduction" style="float:right;" type="info" v-if="payroll.length > 0 & rate !== null">Add Deductions</el-button>
                    </div>
                </div>
                <el-table
                    :data="deductions"
                    style="width: 100%">
                        <el-table-column label="DEDUCTIONS">
                            <el-table-column
                                prop="type"
                                label="TYPE">
                                    <template slot-scope="scope">
                                        <el-input v-model="scope.row.type"></el-input>
                                    </template>
                            </el-table-column>
                            <el-table-column
                                prop="amount"
                                label="AMOUNT">
                                    <template slot-scope="scope">
                                        <el-input v-model="scope.row.amount" @change="checkDeduction($event, scope.$index)" type="number"></el-input>
                                    </template>
                            </el-table-column>
                            <el-table-column
                                fixed="right"
                                width="80">
                                    <template slot-scope="scope">
                                        <el-button @click="deleteDeductions(scope.$index)"><i class="fas fa-trash"></i></el-button>
                                    </template>
                            </el-table-column>
                        </el-table-column>
                </el-table>

                <div class="row">
                    <div class="col-md-12" style="margin-top:10px">
                        <el-descriptions title="PAYROLL SUMMARY" direction="horisontal" :column="1" border>
                            <el-descriptions-item label="Regular Rate">{{regular_rate}}</el-descriptions-item>
                            <el-descriptions-item label="Overtime Rate">{{overtime_rate}}</el-descriptions-item>
                            <el-descriptions-item label="Deductions">{{total_deductions}}</el-descriptions-item>
                            <el-descriptions-item label="Salary">{{total_salary}}</el-descriptions-item>
                        </el-descriptions>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding:10px">
                        <el-button @click="generatePayroll" style="float:right; marging-top:20px" type="primary" v-if="payroll.length > 0 & rate !== null">Generate Payrolll</el-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    name: 'GeneratePayrollList',
    data() {
        return {
            payroll: [],
            loading: true,
            employee: {},
            rate: null,
            rate_ot: null,
            deductions: [],
            overtime: [],
            salaryError: false,
        }
    },
    created() {

        this.$EventDispatcher.listen('RESET_FORM', data => {
            console.log(data)
            this.payroll = []
            this.deductions = []
            this.loading = true
        })
        this.$EventDispatcher.listen('NEW_GENERATE_PAYROLL', data => {
            this.rate = 0
            this.rate_ot = 0
            this.payroll = data.attendance
            this.employee = data.employee
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
    computed: {
        hasOvertime() {
            let payroll = this.payroll.filter(pay => pay.ot_in != null && pay.ot_out != null && pay.ot_status == 'Approved')

            if(payroll.length > 0) {
                return true;
            }

            return false;
        },
        regular_rate() {
            if(this.payroll.length > 0) {
                let rate_total = 0
                this.payroll.forEach(pay => {
                    let rate = pay.hasOwnProperty('rate')
                    if(!rate) {
                        return;
                    }
                    else {
                        if(pay.rate == '') {
                            return 0
                        }
                        else {
                            rate_total = parseFloat(parseFloat(rate_total) + parseFloat(pay.rate)).toFixed(2)
                        }
                    }
                })

                return rate_total
            }
            else {
                return 0;
            }

        },
        overtime_rate() {
            let payroll = this.payroll.filter(pay => pay.ot_in != null && pay.ot_out != null && pay.ot_status == 'Approved')

            if(payroll.length > 0) {
                let overtime = 0
                payroll.forEach(pay => {
                    let rate = pay.hasOwnProperty('overtime_rate')
                    if(!rate) {
                        return 0
                    }
                    else {
                        if(pay.overtime_rate == '') {
                            return
                        }

                        overtime = parseFloat(parseFloat(overtime) + parseFloat(pay.overtime_rate)).toFixed(2)
                    }
                })

                return overtime
            }
            else {
                return 0
            }
        },
        total_deductions() {
            if(this.deductions.length > 0) {
                let total = 0
                this.deductions.forEach(dec => {
                    let amount = dec.hasOwnProperty('amount')
                    if(!amount) {
                        return 0;
                    }
                    else {
                        if(dec.amount == '') {
                            return 0
                        }
                        else {
                            total = parseFloat(parseFloat(total) + parseFloat(dec.amount)).toFixed(2)
                        }
                    }
                })
                return total
            }
            else {
                return 0
            }
        },
        total_salary() {
            return parseFloat(parseFloat(this.regular_rate) + parseFloat(this.overtime_rate) - parseFloat(this.total_deductions)).toFixed(2)
        }
    },
    methods: {
        async generatePayroll() {
            try {
                if(!this.rate || this.rate === 0) {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Please input rate per day'
                    });
                    return;
                }
                let overtime = this.payroll.filter(pay => pay.ot_in != null && pay.ot_out != null && pay.ot_status == 'Approved')
                if(overtime.length > 0) {
                    if(!this.rate_ot || this.rate_ot === 0) {
                        this.$notify.error({
                            title: 'Error',
                            message: 'Please input rate per hour for overtime'
                        });
                        return;
                    }
                }
                this.employee.regular = this.payroll
                this.employee.rate = this.rate
                this.employee.overtime = overtime
                this.employee.deductions = this.deductions
                const res = await this.$API.Finance.storePayroll(this.employee);
                this.$EventDispatcher.fire('NEW_PAYROLL', res.data)
                this.payroll = []
                this.loading = true
                this.deductions = []
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

            this.payroll = this.payroll.map(pay => {
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
        changeRateOT(value) {
            this.payroll = this.payroll.map(pay => {
                pay.overtime_rate = value * parseFloat(pay.total_hours_ot)
                return pay
            })
        },
        handleDoublePay(data) {
            data.rate = 1
        },
        addDeduction() {
            this.deductions.push({
                type: '',
                amount: '',
            })
        },
        deleteDeductions(index) {
            this.deductions.splice(index, 1)
        },
        checkDeduction(value, index) {
            if(value > this.total_salary) {
                this.deductions.splice(index, 1)
            }
            if(this.salaryError) {
                this.deductions.splice(index, 1)
            }
        }
    },
    watch: {
        total_salary(newVal, oldVal) {
            if(newVal < 0) {
                this.$notify.error({
                    title: 'Error',
                    message: 'Salary is negative'
                });
                this.total_salary = 0
                this.salaryError = true
            }

            this.salaryError = false
        }
    }
}
</script>
