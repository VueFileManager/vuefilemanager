<template>
    <div class="action-button">
        <x-icon size="12" class="icon" v-if="icon === 'x'"></x-icon>
        <edit-2-icon size="12" class="icon" v-if="icon === 'pencil-alt'"></edit-2-icon>
        <span class="label">
            <slot></slot>
        </span>
    </div>
</template>

<script>
    import { Edit2Icon, XIcon } from 'vue-feather-icons'

    export default {
        name: 'ActionButton',
        props: ['icon'],
        components: {
            Edit2Icon,
            XIcon,
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .action-button {
        cursor: pointer;

        .label {
            @include font-size(12);
            font-weight: 600;
        }

        .icon {
            @include font-size(10);
            vertical-align: middle;
            display: inline-block;
            margin-right: 2px;

            path, circle, line {
                stroke: $theme;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

    }
</style>
