<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" type="primary" @click="addStock" style="float:right;">Add Harvest</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="width:300px; float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-table
                :data="harvest"
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
                        prop="date"
                        label="DATE"
                        :sortable="true">
                            <template slot-scope="scope">
                                {{scope.row.date | filteDate}}
                            </template>
                    </el-table-column>
                    <el-table-column
                        prop="area.name"
                        label="AREA"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="stem_cut"
                        label="STEM CUT"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="120"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleEdit(scope.row)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                            <button @click="askToDelete(scope.$index, scope.row)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="harvestPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog
            :title="mode == 'create' ? 'ADD HARVEST' : 'UPDATE HARVEST'" width="45%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisible">
            <harvest-form :mode="mode" :model="model"></harvest-form>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
import moment from 'moment'
export default {
    name: 'HarvestList',
    mixins: [paginate],
    data() {
        return {
            harvest: [],
            harvestPagination: [],
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
            this.harvest.unshift(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.harvest.forEach(cat => {
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

        this.getHarvest()
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
        }
    },
    methods: {
        async getHarvest() {
            try {
                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                };

                this.loading = true;
                const res = await this.$API.Harvest.getHarvest(params);
                this.harvest = res.data.data;
                this.harvestPagination = res.data;
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
                await this.$API.Harvest.deleteHarvest(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.harvest.splice(index, 1)
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
            this.getHarvest();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getHarvest();
        },
        applySearch() {
            this.getHarvest();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getHarvest();
            }
        }
    }
}
</script>
