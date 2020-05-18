<template>
    <b class="text-label">
        <slot></slot>
    </b>
</template>

<script>
    export default {
        name: 'SectionTitle',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .text-label {
        @include font-size(12);
        color: #AFAFAF;
        font-weight: 700;
        display: block;
        margin-bottom: 20px;
    }

    @media only screen and (max-width: 1024px) {

    }

    @media (prefers-color-scheme: dark) {
        .text-label {
            color: $theme;
        }
    }
</style>
