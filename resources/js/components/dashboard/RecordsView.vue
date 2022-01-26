<template>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{totalEmployees}}</h3>

                    <p>Total Employees</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{totalAreas}}</h3>

                    <p>Total Areas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{totalOperations}}</h3>

                    <p>Today Operations</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{totalPresent}}</h3>

                    <p>Today Present</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
    <!-- ./col -->
    </div>
</template>
<script>
export default {
    name: 'RecordsView',
    data() {
        return {
            totalEmployees: 0,
            totalAreas: 0,
            totalOperations: 0,
            totalPresent: 0,
        }
    },
    mounted() {
        this.getTotalEmployees()
        this.getTotalAreas()
        this.getTotalOperations()
        this.getTotalPresent()
    },
    methods: {
        async getTotalEmployees() {
            try {
                const res = await this.$API.Dashboard.getTotalEmployees()
                this.totalEmployees = res.data
            } catch (error) {
                console.log(error);
            }
        },
        async getTotalAreas() {
            try {
                const res = await this.$API.Dashboard.getTotalAreas()
                this.totalAreas = res.data
            } catch (error) {
                console.log(error);
            }
        },
        async getTotalOperations() {
            try {
                const res = await this.$API.Dashboard.getTotalOperations()
                this.totalOperations = res.data
            } catch (error) {
                console.log(error);
            }
        },
        async getTotalPresent() {
            try {
                const res = await this.$API.Dashboard.getTotalPresent()
                this.totalPresent = res.data
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
