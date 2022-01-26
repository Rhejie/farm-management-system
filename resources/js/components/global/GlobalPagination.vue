<template>
    <div class="GlobalPagination">
        <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page.sync="currentPage"
        :page-sizes="page_sizes"
        :page-size="current_size"
        :layout="pageLayout"
        :total="pagination.total">
        </el-pagination>
    </div>
</template>
<script>
export default {
    props: [
        "current_size",
        "current_page",
        "pagination",
        "layout"
    ],
    data: function() {
        return {
            page_sizes: [10, 25, 50, 100, 200],
            currentPage: this.current_page,
            pageLayout : this.layout ? this.layout : "total, sizes, prev, pager, next"
        }
    },
    methods: {
        handleSizeChange(val) {
            this.$emit('handleSizeChange', val);
        },
        handleCurrentChange(val) {
            // console.log('cur', val)
            this.$emit('handleCurrentChange', val);
        }
    }
}
</script>
<style lang="scss">
    .GlobalPagination {
        margin-top: 15px;
        text-align: right;

        .el-pagination {
            .el-pager li.active {
                color:  #EC3434;
            }
            .el-pager li:hover {
                color:  #EC3434;
            }
        }

    }
</style>
