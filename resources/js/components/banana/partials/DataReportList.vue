<template>
    <div>
        <el-table
            :data="report"
            style="width: 100%">
            <el-table-column
                prop="area.name"
                label="AREA">
            </el-table-column>
            <el-table-column
                prop="name"
                label="DATE">
                    <template slot-scope="scope">
                        {{scope.row.date | filterDate}}
                    </template>
            </el-table-column>
            <el-table-column
                prop="stem_cut"
                label="STEM CUT">
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    name: "DataReportList",
    props: {
        report: []
    },
    filters: {
        filterDate(value) {
            if(value) {
                return moment(value, 'YYYY-MM-DD').format('MMM-DD');
            }
        }
    },
}
</script>
