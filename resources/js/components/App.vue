<template>
	<div class="app">
		<router-view :pageData="pageData"></router-view>
	</div>
</template>

<script>
	export default {
		name: 'App',
        props: {
			pageData: {}
		},
		data() {
			return {
                rhejie: ''
            }
		},
        created() {
            console.log('app', this.pageData );
        },
	}
</script>
