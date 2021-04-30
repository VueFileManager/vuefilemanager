<template>
    <div class="form-label">
        <edit2-icon v-if="!icon" size="22" class="icon text-theme" />
        <credit-card-icon  v-if="icon === 'credit-card'" size="22" class="icon text-theme" />
        <tool-icon  v-if="icon === 'tool'" size="22" class="icon text-theme" />
        <edit-icon  v-if="icon === 'edit'" size="22" class="icon text-theme" />
        <user-icon  v-if="icon === 'user'" size="22" class="icon text-theme" />
        <settings-icon v-if="icon === 'settings'" size="22" class="icon text-theme" />
        <b class="label">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import { Edit2Icon, SettingsIcon, UserIcon, EditIcon, ToolIcon, CreditCardIcon } from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        props: ['icon'],
        components: {
			CreditCardIcon,
            SettingsIcon,
            Edit2Icon,
			EditIcon,
            UserIcon,
			ToolIcon,
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .form-label {
        display: flex;
        align-items: center;
        margin-bottom: 30px;

        .icon {
            margin-right: 10px;

            path, circle, rect, line {
                color: inherit;
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
