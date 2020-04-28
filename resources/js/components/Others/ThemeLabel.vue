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
    @import "@assets/app.scss";

    .theme-label {
        @include font-size(14);
        color: $theme;
        text-transform: uppercase;
        font-weight: 900;
        display: block;
        margin-bottom: 5px;
    }
</style>
