<template>
    <b class="color-label text-xs rounded-lg" :class="color">
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .color-label {
        text-transform: capitalize;
        display: inline-block;
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

        &.green {
            color: $theme;
            background: rgba($theme, 0.1);
        }

        &.red {
            color: $danger;
            background: rgba($danger, 0.1);
        }
    }

    @media only screen and (max-width: 1024px) {

    }

    .dark {

    }
</style>
