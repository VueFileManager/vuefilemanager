<template>
    <div class="page-tab">
        <div id="loader" v-show="isLoading">
            <Spinner></Spinner>
        </div>
        <slot v-show="!isLoading"></slot>
    </div>
</template>

<script>
import Spinner from '../../FilesView/Spinner'

export default {
    name: 'PageTab',
    props: ['isLoading'],
    components: {
        Spinner,
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';
</style>
