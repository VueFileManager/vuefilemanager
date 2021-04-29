<template>
    <div class="page-tab">
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
        <slot v-if="! isLoading"></slot>
    </div>
</template>

<script>
    import Spinner from '@/components/FilesView/Spinner'

    export default {
        name: 'PageTab',
        props: ['isLoading'],
        components: {
            Spinner,
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

	.page-tab {
		position: relative;
	}
</style>