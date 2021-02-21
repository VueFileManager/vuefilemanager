<template>
    <div class="form-label">
        <edit-2-icon v-if="!icon" size="22" class="icon"></edit-2-icon>
        <settings-icon v-if="icon === 'settings'" size="22" class="icon"></settings-icon>
        <b class="label">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import { Edit2Icon, SettingsIcon } from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        props: ['icon'],
        components: {
            Edit2Icon,
            SettingsIcon
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

            path,
            circle {
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
