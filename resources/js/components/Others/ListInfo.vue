<template>
    <ul class="list-info">
        <slot></slot>
    </ul>
</template>

<script>
    export default {
        name: 'ListInfo',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
</style>
