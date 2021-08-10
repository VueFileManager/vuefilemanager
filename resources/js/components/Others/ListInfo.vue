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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
</style>
