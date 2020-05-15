<template>
    <div class="action-button">
        <FontAwesomeIcon class="icon" :icon="icon" />
        <span class="label">
            <slot></slot>
        </span>
    </div>
</template>

<script>
    export default {
        name: 'ActionButton',
        props: ['icon'],
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .action-button {
        cursor: pointer;

        .label {
            @include font-size(12);
            color: $theme;
            font-weight: 600;
            text-decoration: underline;
        }

        .icon {
            @include font-size(10);
            display: inline-block;
            margin-right: 2px;

            path {
                fill: $theme;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

    }
</style>
