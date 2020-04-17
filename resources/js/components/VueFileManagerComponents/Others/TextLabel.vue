<template>
    <b class="text-label">
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

    .text-label {
        @include font-size(10);
        color: $theme;
        text-transform: uppercase;
        font-weight: 900;
        display: block;
        margin-bottom: 5px;
    }

    @media (prefers-color-scheme: dark) {
        .text-label {
            color: $theme;
        }
    }
</style>
