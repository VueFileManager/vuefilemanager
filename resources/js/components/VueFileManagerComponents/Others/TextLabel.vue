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
        @include font-size(11);
        color: $light_text;
        text-transform: uppercase;
        font-weight: 900;
        display: block;
        margin-bottom: 5px;
    }
</style>
