<template>
    <div>
        <div class="payslip-container" id="printMe">
            <div class="row">
                <div class="col-md-12 name">
                    <h3>{{name}}</h3>
                    <hr>
                </div>
                <div class="col-md-12 info">
                    <el-descriptions border :column="1">
                        <el-descriptions-item label="Pay Period">{{fromDate}} - {{toDate}}</el-descriptions-item>
                        <el-descriptions-item label="Position">{{position}}</el-descriptions-item>
                        <el-descriptions-item label="Rate">P {{this.payslip.rate}}</el-descriptions-item>
                    </el-descriptions>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <th>Particulars</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Number of Days: {{days}}
                                </td>
                                <td>
                                    P {{regular_salary}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Number of Overtime: {{overtime}}
                                </td>
                                <td>
                                    P {{overtime_salary}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right">
                                    Gross Salary
                                </td>
                                <td>
                                    P {{gross_salary}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-sm" style="margin-top: -10px">
                        <thead>
                            <th>Deductions</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="(dec, index) in payslip.deductions" :key="index">
                                <td>
                                    {{dec.type}}
                                </td>
                                <td>
                                    P {{dec.amount}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right">
                                    Total Deductions
                                </td>
                                <td>
                                    P {{total_deductions}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12" style="text-align:right; padding-right:10px; padding-right:20px;">
                            <p>Net Salary: P {{net_salary}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" >
                <button @click="print" style="float:right" class="btn btn-info btn-sm">Print</button>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    name: 'PrintPayslip',
    props: {
        model: {}
    },
    data() {
        return {
            payslip: {}
        }
    },
    created() {
        this.payslip = this.model
    },
    computed: {
        name() {
            return this.payslip.employee.lastname +", "+this.payslip.employee.firstname+" "+this.payslip.employee.middlename
        },
        fromDate() {
            return moment(this.payslip.from_date, 'YYYY-MM-DD').format('YYYY-MMM-DD');
        },
        toDate() {
            return moment(this.payslip.to_date, 'YYYY-MM-DD').format('YYYY-MMM-DD');
        },
        position() {
            return this.payslip.employee.position
        },
        days() {
            let days = 0
            this.payslip.regular.forEach(ref => {
                days++
            })

            return days
        },
        overtime() {
            let days = 0
            this.payslip.overtime.forEach(ref => {
                days++
            })

            return days
        },
        regular_salary() {
            let salary = 0
            this.payslip.regular.forEach(reg => {
                salary += parseFloat(reg.rate)
            })

            return salary
        },
        overtime_salary() {
            let salary = 0
            this.payslip.overtime.forEach(reg => {
                salary += parseFloat(reg.overtime_rate)
            })

            return salary
        },
        total_deductions() {
            let salary = 0
            this.payslip.deductions.forEach(reg => {
                salary += parseFloat(reg.amount)
            })

            return salary
        },
        gross_salary() {
            return parseFloat(parseFloat(this.regular_salary) + parseFloat(this.overtime_salary)).toFixed(2)
        },
        net_salary() {
            return parseFloat(parseFloat(this.gross_salary) - parseFloat(this.total_deductions)).toFixed(2)
        }
    },
    methods: {
        async print() {
            // Pass the element id here
            await this.$htmlToPaper('printMe');
        },
    },
    watch: {
        model(newVal, oldVal) {
            if(newVal == oldVal) {
                this.payslip = newVal
            }
        }
    }

}
</script>
<style lang="scss">
    .payslip-container {
        border-style: solid;
        border-color: grey;
        border-width: 1px;

        .name {
            margin-bottom: -20px;
        }

        .info {

        }
    }
</style>
