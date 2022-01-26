<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title" v-if="type == 'update'">UPDATE AREA</h1>
                            <h1 class="page_title" v-else>ADD AREA</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="/areas#/areas">Areas</a></li>
                            <li class="breadcrumb-item active">Add Area</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <el-card class="box-card">
                        <div  class="text item" style="padding-bottom: 20px">
                            <div class="row">
                                <div class="col-md-12">
                                    <el-form :inline="true" ref="form" :rules="rules" :model="form" class="demo-form-inline">
                                        <el-form-item label="Name" prop="name">
                                            <el-input v-model="form.name" placeholder="Name"></el-input>
                                        </el-form-item>
                                        <el-form-item label="Color" prop="color">
                                            <el-color-picker v-model="form.color" style="width:100%" show-alpha></el-color-picker>
                                        </el-form-item>
                                        <el-form-item>
                                            <el-button type="primary" @click="submitForm('form')">Save</el-button>
                                        </el-form-item>
                                    </el-form>
                                </div>
                            </div>
                            <div style="height: 700px; width: 100%">
                                <l-map data="parent"  :zoom="zoom" :center="getArea">
                                    <l-control position="topleft">
                                        <button class="btn btn-default btn-sm" style="margin-bottom: 10px" @click="flipActive">
                                        {{ isCreate ? 'Deactivate' : 'Activate' }}
                                        </button> <br>
                                        <button class="btn btn-default btn-sm"  style="margin-bottom: 10px" @click="deleteMap">
                                            Delete
                                        </button>

                                    </l-control>
                                    <l-free-draw v-model="polygons" :mode="mode" :color="form.color"/>
                                    <l-tile-layer :url="url"></l-tile-layer>
                                </l-map>
                            </div>
                        </div>
                    </el-card>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
import { latLng , Icon } from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip , LCircle, LIcon, LPolygon, LControl } from "vue2-leaflet";
import LFreeDraw from 'vue2-leaflet-freedraw';
import { NONE, ALL, DELETE } from 'leaflet-freedraw';
export default {
    name: 'AddMap',
    props: {
        type: null,
        id: null,
        model: {}
    },
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
        LTooltip,
        LCircle,
        LIcon,
        LPolygon,
        LFreeDraw,
        LControl
    },
    data() {
        return {
            zoom: 10,
            center: latLng(0, 0),
            url: 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=cac1c63e746741a79462820881e7f2c6',
            attribution: 'Farm Management',
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: true,
            coordinates: {},
            isCreate: false,
            polygons: [],
            map: null,
            form: {
                name: '',
                color: ''
            },
            rules : {
                name: [
                    { required: true, message: 'Please input name', trigger: 'blur' }
                ],
                color: [
                    { required: true, message: 'Please input select color', trigger: 'blur' }
                ],
            },
            loadingMap: false,
            editArea: [],
            coordin : {}
        }
    },
    async mounted() {
        if(!this.type) {
            await this.getAreaById()
        }
        this.getLocation()
    },
    created() {
        if(this.type == 'update' && this.model && this.model.id) {
            // let coor = this.model.coordinates.map(coor => {
            //     return {lat : coor[0], lng : coor[1]}
            // });
            // this.form = {
            //     name: this.model.name,
            //     color: this.model.color,
            // }
            // this.polygons.push(coor)

            this.getAreaById()
        }
    },
    computed: {
        mode() {
            return this.isCreate ? (ALL ^ DELETE) : NONE
        },
        getArea() {
            if (this.type == 'update') {
                this.zoom = 16
                if(this.polygons.length > 0) {
                    return latLng(this.polygons[0][0].lat, this.polygons[0][0].lng)
                }
            }
            else if(this.id) {
                if(this.polygons.length > 0) {
                    return latLng(this.polygons[0][0].lat, this.polygons[0][0].lng)
                }
            }
            return latLng(this.coordinates.lat, this.coordinates.lng)
        }
    },
    methods: {
        async getLocation() {
            try {
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.coordinates = coordinates
            } catch (error) {
                console.log(error);
            }
        },
        async getAreaById() {
            try {
                this.zoom = 16
                const res = await this.$API.Area.getAreaById(this.id);
                let coor = res.data.coordinates.map(coor => {
                    return {lat : coor[0], lng : coor[1]}
                });
                this.form = {
                    name: res.data.name,
                    color: res.data.color
                }
                this.polygons.push(coor);
            } catch (error) {
                console.log(error);
            }
        },
        flipActive() {
            this.isCreate = !this.isCreate;
        },
        deleteMap() {
            this.polygons = []
        },
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.type == 'update' || this.id) {
                    this.updateArea();
                    return;
                }

                this.storeArea();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
        resetForm(formName) {
            this.polygons = []
            this.$refs[formName].resetFields();
        },
        async storeArea() {
            try {
                if(this.polygons.length < 0) {
                    this.$notify.error({
                            title: 'Error',
                            message: 'Draw and Area first'
                        });
                    return;
                }
                this.form.coordinates = this.polygons
                const res = await this.$API.Area.storeArea(this.form)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully added',
                    type: 'success'
                });
                this.resetForm('form');
                this.$router.push({name: 'Area List'})
            } catch (error) {
                console.log(error);
            }
        },
        async updateArea() {
            try {
                if(this.polygons.length < 0) {
                    this.$notify.error({
                            title: 'Error',
                            message: 'Draw and Area first'
                        });
                    return;
                }
                this.form.coordinates = this.polygons
                const res = await this.$API.Area.updateArea(this.id,this.form)
                this.$notify({
                    title: 'Success',
                    message: 'Successfully Updated',
                    type: 'success'
                });
                this.$router.push({name: 'Area List'})
            } catch (error) {
                console.log(error);
            }
        }
    },
    watch: {
        polygons(val) {
            if(val.length > 1) {
                this.polygons.splice(1, 1)
            }
        },
        model(newVal, oldVal) {
            if(newVal != oldVal && this.type == 'update') {
                this.form = {
                    name: newVal.name,
                    color: newVal.color
                }

                let coor = newVal.coordinates.map(coor => {
                    return {lat : coor[0], lng : coor[1]}
                });

                this.polygons.push(coor)
            }
        }
    }
}
</script>
