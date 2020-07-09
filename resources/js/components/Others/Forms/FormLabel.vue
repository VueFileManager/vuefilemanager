<template>
    <div class="form-label">
        <edit-2-icon size="22" class="icon"></edit-2-icon>
        <b class="label">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import { Edit2Icon } from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        components: {
            Edit2Icon
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .form-label {
        display: flex;
        align-items: center;
        margin-bottom: 30px;

        .icon {
            margin-right: 10px;

            path {
                stroke: $theme;
            }
        }

        .label {
            @include font-size(18);
            font-weight: 700;
        }
    }

    @media (prefers-color-scheme: dark) {
        .form-label {

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
