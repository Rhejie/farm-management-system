<template>
    <el-card class="box-card">
        <div  class="text item">
            <el-button size="mini" type="primary" @click="addArea" style="float:right; margin-bottom:10px">Add Area</el-button>
            <el-input
                size="mini"
                placeholder="Search here....."
                style="float:right; margin-right: 10px"
                @keyup.enter.native="applySearch"
                v-model="search">
            </el-input>
            <el-table
                :data="areas"
                v-loading="loading"
                style="width: 100%">
                    <el-table-column
                        prop="name"
                        label="NAME"
                        show-overflow-tooltip
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="status"
                        label="STATUS"
                        :sortable="true">
                    </el-table-column>
                    <el-table-column
                        prop="color"
                        label="COLOR"
                        :sortable="true">
                            <template slot-scope="scope">
                                <div :style="{backgroundColor: scope.row.color, padding: '10px'}"></div>
                            </template>
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        width="70"
                        label="ACTION">
                        <template slot-scope="scope">
                            <button @click="handleEdit(scope.row)" class="btn btn-text btn-sm"><i class="far fa-edit"></i></button>
                        </template>
                    </el-table-column>
            </el-table>
            <global-pagination
                :current_page="current_page"
                :current_size="current_size"
                :pagination="areasPagination"
                :total="filters.total"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange">
            </global-pagination>
        </div>
        <el-dialog
            :title="mode == 'create' ? 'ADD AREA' : 'UPDATE AREA'" width="35%"
            :before-close="handleClose"
            :visible.sync="dialogTableVisible">
            <area-form :mode="mode" :model="model"></area-form>
        </el-dialog>
    </el-card>
</template>
<script>
import paginate from '../../../mixin/pagination'
export default {
    name: 'AreaList',
    mixins: [paginate],
    data() {
        return {
            areas: [],
            areasPagination: [],
            loading: false,
            current_page: 1,
            current_size: 25,
            search: null,
            mode: '',
            model: {},
            dialogTableVisible: false,
        }
    },
    created() {
        this.getAreas()

        this.$EventDispatcher.listen('NEW_DATA', data => {
            this.areas.push(data)
            this.dialogTableVisible = false
            this.mode = ''
        })

        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.areas.forEach(cat => {
                if(cat.id == data.id) {
                    for(let key in data) {
                        cat[key] = data[key]
                    }
                }
            })

            this.dialogTableVisible = false
            this.mode = ''
        })
    },
    methods: {
        async getAreas() {
            try {
                let params = {
                    current_size: this.current_size,
                    current_page: this.current_page,
                    search: this.search,
                };

                this.loading = true;
                const res = await this.$API.Area.getAreas(params);
                this.areas = res.data.data;
                this.areasPagination = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        addArea() {
            this.$router.push({name: 'Add Map'})
        },
        handleEdit(data) {
            this.model = {...data};
            this.dialogTableVisible = true;
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
                await this.$API.Area.deleteArea(data.id)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Deleted',
                    type: 'success'
                });
                this.areas.splice(index, 1)
            } catch (error) {
                console.log(error);
            }
        },
        viewArea() {
            this.$router.push({name: 'Area View'})
        },
        handleClose(done) {
            this.$EventDispatcher.fire('CLOSE_MODAL')
            done();
        },
        handleSizeChange(val) {
            this.current_size = val;
            this.getAreas();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.getAreas();
        },
        applySearch() {
            this.getAreas();
        },
    },
    watch: {
        search(val) {
            if( val == '') {
                this.getAreas();
            }
        }
    }
}
</script>
