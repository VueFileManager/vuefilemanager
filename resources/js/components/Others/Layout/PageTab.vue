<template>
    <div class="page-tab">
        <div id="loader" v-show="isLoading">
            <Spinner></Spinner>
        </div>
        <slot v-show="! isLoading"></slot>
    </div>
</template>

<script>
    import Spinner from '/resources/js/components/FilesView/Spinner'

    export default {
        name: 'PageTab',
        props: ['isLoading'],
        components: {
            Spinner,
        },
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
</style>