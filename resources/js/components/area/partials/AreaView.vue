<template>
    <div>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page_title">MANAGE AREAS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Areas</li>
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
                            <div style="height: 700px; width: 100%">
                                <l-map
                                    v-if="showMap"
                                    :zoom="zoom"
                                    :center="getFirstArea"
                                    :options="mapOptions"
                                    @click="customeClick"
                                    style="height: 80%"
                                    @update:center="centerUpdate"
                                    @update:zoom="zoomUpdate">
                                        <l-circle :lat-lng="circle.center" color="red" fillColor="blue" :radius="10000"></l-circle>
                                        <l-polygon
                                            :lat-lngs="polygon.latlngs"
                                            :color="polygon.color"
                                        />
                                        <l-tile-layer
                                            :url="url"
                                            :attribution="attribution"/>
                                                <l-marker :lat-lng="getCoor(coor.lat, coor.lng)" v-for="coor in coorArray" :key="coor.id">
                                                    <l-icon :icon-url="`/img/area.png`" :icon-size="iconSize"></l-icon>
                                                    <l-popup>
                                                    <div @click="innerClick">
                                                        {{coor.name}}
                                                        <p v-show="showParagraph">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                                        sed pretium nisl, ut sagittis sapien. Sed vel sollicitudin nisi.
                                                        Donec finibus semper metus id malesuada.
                                                        </p>
                                                    </div>
                                                    </l-popup>
                                                </l-marker>
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
import { LMap, LTileLayer, LMarker, LPopup, LTooltip , LCircle, LIcon, LPolygon,} from "vue2-leaflet";
// delete Icon.Default.prototype._getIconUrl;
// Icon.Default.mergeOptions({
//   iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
//   iconUrl: require('leaflet/dist/images/marker-icon.png'),
//   shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
// });
export default {
    name: 'AreaView',
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
        LTooltip,
        LCircle,
        LIcon,
        LPolygon,
    },
    data() {
        return {
            zoom: 10,
            center: latLng(0, 0),
            url: 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=cac1c63e746741a79462820881e7f2c6',
            attribution: 'Farm Management',
            withPopup: latLng(47.41322, -1.219482),
            withTooltip: latLng(0, 0),
            currentZoom: 11.5,
            currentCenter: latLng(47.41322, -1.219482),
            showParagraph: true,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: true,
            coorArray: [
                {id: 1, lat: 47.41322, lng: -1.219482, name: 'Ambot'},
                {id: 2, lat: 7.456723800000001, lng: 125.58301839999999, name: 'Tulalian'}
            ],
            coordinates: {},
            icon: '',
            iconSize: [40,40],
            circle: {
                center: latLng(7.456723800000001, 125.58301839999999),
                radius: 50
            },
            polygon: {
                latlngs: [

                    [7.584843600080864, 125.65932042738467],
                    [7.582491219170435, 125.65684521853923],
                    [7.578140545332663, 125.65426793922595],
                    [7.573992187484203, 125.65748315896332],
                    [7.577786418783289, 125.66508740881837],
                    [7.581959352102654, 125.66169631984934],
                    [7.584907223354542, 125.65940521711725],

                    ],
                color: "#ff00ff"
            },
        }
    },
    mounted() {
        this.getLocation()
    },
    computed: {
        getFirstArea() {
            if(this.coorArray.length > 0) {
                return latLng(this.coorArray[1].lat, this.coorArray[1].lng)
            }

            return latLng(coordinates.lat, coordinates.lng)
        }
    },
    methods: {
        getCoor(lat, lng) {
            return latLng(lat, lng);
        },
        zoomUpdate(zoom) {
            this.currentZoom = zoom;
        },
        centerUpdate(center) {
            this.currentCenter = center;
        },
        showLongText() {
            this.showParagraph = !this.showParagraph;
        },
        innerClick() {
            alert("Click!");
        },
        customeClick(item) {
            const {latlng} = item
            console.log('lat ug lng angkuhaon sa clik', item);

        },
        async getLocation() {
            try {
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.center = latLng(parseFloat(parseFloat(coordinates.lat).toFixed(3)), parseFloat(parseFloat(coordinates.lng).toFixed(3)));
                this.withPopup = latLng(parseFloat(parseFloat(coordinates.lat).toFixed(3)), parseFloat(parseFloat(coordinates.lng).toFixed(3)));
                this.currentCenter = latLng(parseFloat(parseFloat(coordinates.lat).toFixed(3)), parseFloat(parseFloat(coordinates.lng).toFixed(3)))
                // this.form.coordinates.lng = parseFloat(parseFloat(coordinates.lng).toFixed(3));
                // this.noLocation = false;
                // this.loadingMap = false;
                this.coordinates = coordinates
            } catch (error) {
                console.log(error);
            }
        },
    },
}
</script>
