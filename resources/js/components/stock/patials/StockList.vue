<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" type="primary" @click="addStock" style="float:right;">Add Stock</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-table
                :data="stocks"
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
                        prop="item.name"
                        label="ITEM"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="item.category.name"
                        label="CATEGORY"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="quantity"
                        label="QUANTITY"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.quantity | addComma}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="unit"
                        label="UNIT"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="used"
                        label="USED"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.used | addComma}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="170"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleDeploy(scope.row)" class="btn btn-info btn-sm"><i class="fas fa-baby-carriage"></i></button>
                            <button @click="handleRestock(scope.row)" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i></button>
                            <button @click="handleEdit(scope.row)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                            <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="stocksPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog
            :title="mode == 'create' ? 'ADD STOCK' : 'UPDATE STOCK'" width="45%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisible">
            <stock-form :mode="mode" :model="model"></stock-form>
        </el-dialog>

        <el-dialog
            title="RESTOCK" width="45%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisibleRestock">
            <restock-form :mode="mode" :model="model"></restock-form>
        </el-dialog>

        <el-dialog
            title="Deploy Stock" width="40%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisibleDeploy">
            <deploy-stock-form :mode="mode" :model="model"></deploy-stock-form>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
export default {
    name: 'StockList',
    mixins: [paginate],
    data() {
        return {
            stocks: [],
            stocksPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
            dialogTableVisibleRestock: false,
            dialogTableVisibleDeploy: false,
        }
    },
    created() {
        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.stocks.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('DEPLOY_STOCK', data => {
            this.stocks.forEach(st => {
                if(st.id == data.stock_id) {
                    st.quantity -= parseFloat(data.quantity)
                    st.used += parseFloat(data.quantity)
                }
            })
            this.dialogTableVisibleDeploy = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.stocks.forEach(cat => {
                if(cat.id == data.id) {
                    for(let key in data) {
                        cat[key] = data[key]
                    }
                }
            })

            this.dialogTableVisible = false
            this.dialogTableVisibleRestock = false
            this.mode = ''
        })

        this.getStocks()
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
    },
    methods: {
        async getStocks() {
            try {
                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                };

                this.loading = true;
                const res = await this.$API.Warehouse.getStocks(params);
                this.stocks = res.data.data;
                this.stocksPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        addStock() {
            this.mode = 'create';
            this.dialogTableVisible = true;
        },
        handleEdit(data) {
            this.model = {...data};
            this.dialogTableVisible = true;
            this.mode = 'update'
        },
        handleDeploy(data) {
            this.mode = 'create'
            data.from = 'stock';
            this.model = {...data};
            this.dialogTableVisibleDeploy = true;
        },
        handleRestock(data) {
            this.model = {...data};
            this.dialogTableVisibleRestock = true;
            this.mode = 'update'
        },
        askToDelete(index, data) {
            this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    this.delete(index, data);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled'
                    });
                });
        },
        async delete(index, data) {
            try {
                await this.$API.Warehouse.deleteStock(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.stocks.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getStocks();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getStocks();
        },
        applySearch() {
            this.getStocks();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getStocks();
            }
        }
    }
}
</script>
