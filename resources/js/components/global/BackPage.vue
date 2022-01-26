<template>
    <div>
        <el-button @click="back" type="text" icon="el-icon-arrow-left"></el-button>
    </div>
</template>
<script>
export default {
    name: 'BackPage',
    methods: {
        back() {
            this.$router.go(-1);
        }
    },
}
</script>
