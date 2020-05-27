<template>
    <b class="color-label" :class="color">
        <slot></slot>
    </b>
</template>

<script>
    export default {
        name: 'ColorLabel',
        props: ['color'],
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .color-label {
        text-transform: capitalize;
        @include font-size(12);
        display: inline-block;
        border-radius: 6px;
        font-weight: 700;
        padding: 4px 6px;

        &.purple {
            color: $purple;
            background: rgba($purple, 0.1);
        }

        &.yellow {
            color: $yellow;
            background: rgba($yellow, 0.1);
        }
    }

    @media only screen and (max-width: 1024px) {

    }

    @media (prefers-color-scheme: dark) {

    }
</style>
