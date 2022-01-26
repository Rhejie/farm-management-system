<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <p>Name: <span style="font-weight: 700">{{name}}</span></p>
                    <p>Generate Payroll : <span style="font-weight: 700">{{payroll.date | filteDate}}</span></p>
                </div>
                <div class="col-md-6">
                    <p>From: <span style="font-weight: 700">{{payroll.from_date | filteDate}}</span></p>
                    <p>To: <span style="font-weight: 700">{{payroll.to_date | filteDate}}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <el-table
                        :data="payroll.regular"
                        style="width: 100%">
                            <el-table-column label="REGULAR HOURS">
                                <el-table-column
                                    prop="date"
                                    label="DATE">
                                        <template slot-scope="scope">
                                            {{scope.row.date | filteDate}}
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
                    </el-table>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <el-table
                                :data="payroll.overtime"
                                style="width: 100%;">
                                    <el-table-column label="OVERTIME">
                                        <el-table-column
                                            prop="date"
                                            label="DATE">
                                                <template slot-scope="scope">
                                                    {{scope.row.date | filteDate}}
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
                    </div>
                    <el-table
                    :data="payroll.deductions"
                    style="width: 100%">
                        <el-table-column label="DEDUCTIONS">
                            <el-table-column
                                prop="type"
                                label="TYPE">
                            </el-table-column>
                            <el-table-column
                                prop="amount"
                                label="AMOUNT">
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
                        <div class="col-md-12" style="margin-top:10px; ">
                            <!--<button @click="payslip()" class="btn btn-info btn-sm"><i class="fas fa-file"></i> Print</button>
                            <a :href="`finance/payslip/${payroll.id}`" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-arrow-down"></i> Download</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import moment from "moment"
export default {
    name: 'ViewPayroll',
    props: {
        model: {}
    },
    data() {
        return {
            payroll: {}
        }
    },
    created() {
        this.payroll = this.model
    },
    computed: {
        totalSalary() {
            let total = 0
            // this.payroll.item.forEach(pay => {
            //     total += pay.salary
            // })

            return total
        },
        totalHours() {
            let total = 0
            // this.payroll.item.forEach(pay => {
            //     total += (pay.hours - 1)
            // })

            return total
        },
        name() {
            return this.payroll.employee.lastname + ", " + this.payroll.employee.firstname + " - ( "+ this.payroll.employee.position + " )"
        },
        regular_rate() {
            let total = 0
            this.payroll.regular.forEach(reg => {
                total = parseFloat(parseFloat(total) + parseFloat(reg.rate)).toFixed(2)
            })
            return total;
        },
        overtime_rate() {
            let total = 0
            this.payroll.overtime.forEach(over => {
                total = parseFloat(parseFloat(total) + parseFloat(over.overtime_rate)).toFixed(2)
            })
            return total;
        },
        total_deductions() {
            let total = 0 //
            this.payroll.deductions.forEach(dec => {
                total = parseFloat(parseFloat(total) + parseFloat(dec.amount)).toFixed(2)
            })

            return total
        },
        total_salary() {
            return parseFloat(parseFloat(this.regular_rate) + parseFloat(this.overtime_rate) - parseFloat(this.total_deductions)).toFixed(2)
        }
    },
    filters: {
        addComma(value) {
            if(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            else {
                return 0;
            }
        },
        filteDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('YYYY-MMM-DD');
            }
        },
        timeFormat(value) {
            if(value) {
                return moment(value, 'HH:mm:ss').format('h:mm a')
            }
            return '-'
        },
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal != oldVal) {
                this.payroll = newVal
            }
        }
    }
}
</script>
