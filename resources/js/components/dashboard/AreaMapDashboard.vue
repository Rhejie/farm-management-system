<template>
    <div style="height: 600px; width: 100%">
        <l-map
            :zoom="zoom"
            :center="getFirstArea"
            :options="mapOptions"
            @click="customeClick"
            style="height: 100%"
            @update:center="centerUpdate"
            @update:zoom="zoomUpdate">
                <l-polygon
                    v-for="area in areas.filter(area => area.status == 'Publish')" :key="area.id"
                    :color="area.color"
                    :lat-lngs="area.coordinates"
                    :bind-popup="area.name">
                        <l-popup>
                            <h3>{{area.name}}</h3>
                        </l-popup>
                </l-polygon>
                <l-tile-layer
                    :url="url"
                    :attribution="attribution"/>
        </l-map>
    </div>
</template>
<script>
import { latLng , Icon} from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip , LCircle, LIcon, LPolygon,} from "vue2-leaflet";
export default {
    name: 'AreaMapDashboard',
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
            zoom: 16,
            center: latLng(0, 0),
            url: 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=cac1c63e746741a79462820881e7f2c6',
            attribution: 'Farm Management',
            currentZoom: 11.5,
            currentCenter: latLng(47.41322, -1.219482),
            showParagraph: true,
            mapOptions: {
                zoomSnap: 0.5
            },
            coordinates: {},
            areas: []
        }
    },
    mounted() {

        this.getLocation()
        this.getAreas()
    },
    computed: {
        getFirstArea() {
            if(this.areas.length > 0) {
                if(this.areas[0].coordinates.length > 0) {
                    return latLng(this.areas[0].coordinates[0][0], this.areas[0].coordinates[0][1])
                }
                return latLng(this.coordinates.lat, this.coordinates.lng)
            }
            return latLng(this.coordinates.lat, this.coordinates.lng)
        },
        getMarkers() {
            let markers = []
            this.areas.forEach(area => {
                let center = area.coordinates.reduce(function (x,y) {
                    return [x[0]  + y[0] / area.coordinates.length, x[1] + y[1] /area.coordinates.length]
                }, [0,0])

                markers.push(center)
            })

            return markers
        }
    },
    created() {
        this.$EventDispatcher.listen('UPDATE_DATA', data => {
            this.areas.forEach(area => {
                if(area.id == data.id) {
                    area.name = data.name
                    area.status = data.status
                    area.color = data.color
                }
            })
        })
    },
    methods: {

        async getLocation() {
            try {
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.center = latLng(parseFloat(parseFloat(coordinates.lat).toFixed(3)), parseFloat(parseFloat(coordinates.lng).toFixed(3)));
                this.coordinates = coordinates
            } catch (error) {
                console.log(error);
            }
        },

        async getAreas() {
            try {
                const res = await this.$API.Area.getAllAreas();
                this.areas = res.data.filter(area => area.status == 'Publish')
            } catch (error) {
                console.log(error);
            }
        },
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
    },
}
</script>
