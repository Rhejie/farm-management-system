<template>
    <div>
        <el-date-picker
            v-model="date"
            @change="changeDate"
            type="date"
            style="margin-bottom:10px"
            :clearable="false"
            placeholder="Pick a day">
        </el-date-picker>
        <el-button @click="refresh"><i class="fas fa-retweet"></i></el-button>
        <div style="height: 600px; width: 100%">
            <l-map
                :zoom="zoom"
                :center="getFirstArea"
                :options="mapOptions"
                style="height: 100%">
                    <l-polygon
                        v-for="(area, index) in deploy" :key="index"
                        :color="area.color"
                        :lat-lngs="area.coordinates">
                        <l-popup>
                            <h4>{{area.name}}</h4>
                            <div v-for="(dep, index2) in area.deploy_team" :key="index2">
                                <span style="padding:0; margin:0; magin-bottom:0;">TASK: {{dep.task.task.name}} </span>
                                <div style="width: 100%">
                                    <table class="table table-sm table-bordered table-striped">
                                        <thead>
                                            <th>Members</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="member in dep.members" :key="member.id">
                                                <td>{{member.lastname}}, {{member.firstname}} - ( {{member.position}} )</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </l-popup>
                    </l-polygon>
                    <!--<l-polygon
                        v-for="(dep, index) in deploy" :key="index"
                        :color="dep.area.color"
                        :lat-lngs="dep.area.coordinates"
                        :bind-popup="dep.area.name">
                        <l-popup>
                            <h4>{{dep.area.name}}</h4>
                            <h6>TASK: {{dep.task.task.name}}</h6>
                            <div style="width: 300px">
                                <el-table
                                    :data="dep.members"
                                    style="width: 100%">
                                        <el-table-column
                                            show-overflow-tooltip
                                            label="MEMBERS">
                                                <template slot-scope="scope">
                                                    {{scope.row.lastname}}, {{scope.row.firstname}} - ( {{scope.row.position}} )
                                                </template>
                                        </el-table-column>
                                </el-table>
                            </div>
                        </l-popup>
                    </l-polygon> -->
                    <l-tile-layer
                        :url="url"
                        :attribution="attribution"/>
            </l-map>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
import { latLng , Icon} from "leaflet";
import { LMap, LTileLayer, LMarker, LPopup, LTooltip , LCircle, LIcon, LPolygon,} from "vue2-leaflet";
export default {
    name: "DeployArea",
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
            date: '',
            search: '',
            deploy: [],
            zoom: 15,
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
        }
    },
    mounted() {

        this.getLocation()

    },
    created() {
        this.date = new Date();
        this.getDeploy()
        this.$EventDispatcher.listen('DELETE_DEPLOY', data => {
            this.deploy.filter(dep => dep.id != data)
        })

        this.$EventDispatcher.listen('NEW_DEPLOY_TEAM', data => {
            this.deploy.push(data);
        })

    },
    computed: {
        getFirstArea() {
            if(this.deploy.length > 0) {
                if(this.deploy[0].coordinates.length > 0) {
                    return latLng(this.deploy[0].coordinates[0][0], this.deploy[0].coordinates[0][1])
                }
                return latLng(this.coordinates.lat, this.coordinates.lng)
            }
            return latLng(this.coordinates.lat, this.coordinates.lng)
        },
        deployData () {
            let deplot = this.deploy.map(dep => {
                let arrayData = []
                return dep
            })
        }
    },
    methods: {
        async getDeploy() {
            try {
                this.date = this.$df.formatDate(this.date, "YYYY-MM-DD")

                let params = {
                    search: this.search,
                    date: this.date,
                };

                this.loading = true;
                const res = await this.$API.Deploy.getDeployByArea(params);
                this.deploy = res.data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            }
        },
        changeDate(value) {
            this.date = value
            this.getDeploy();
        },
        async getLocation() {
            try {
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.center = latLng(parseFloat(parseFloat(coordinates.lat).toFixed(3)), parseFloat(parseFloat(coordinates.lng).toFixed(3)));
                this.coordinates = coordinates
            } catch (error) {
                alert('turn on your location')
                window.location.reload()
            }
        },
        refresh() {
            this.getDeploy()
        }
    },
}
</script>
