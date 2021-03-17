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
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
</style>
