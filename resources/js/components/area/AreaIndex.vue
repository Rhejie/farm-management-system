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
                    <areas></areas>
                </div>
            </section>
        </div>
    </div>
</template>
<script>

export default {
    name: 'AreaIndex',
    data() {
        return {
            error: '',
            lat:'',
            lon:'',
            location: ''
        }
    },
    created() {
        // this.$getLocation()
        //     .then(coordinates => {
        //         console.log(coordinates);
        //     });
        this.getLocation()
    },
    methods: {
        async getLocation() {
            try {
                const coordinates = await this.$getLocation({
                    enableHighAccuracy: true
                });
                this.coordinates = coordinates;
                this.noLocation = false;
            } catch (error) {
                console.log(error);
            }
        },
    },
}
</script>
