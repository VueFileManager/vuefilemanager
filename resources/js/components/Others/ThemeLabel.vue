<template>
    <b class="theme-label">
        <slot></slot>
    </b>
</template>

<script>
    export default {
        name: 'TextLabel',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .theme-label {
        @include font-size(14);
        color: $theme;
        font-weight: 600;
        display: block;
        margin-bottom: 20px;
    }
</style>
