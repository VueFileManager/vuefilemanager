<template>
    <div class="form-label">
        <edit-2-icon v-if="!icon" size="22" class="icon text-theme dark-text-theme" />
        <settings-icon v-if="icon === 'settings'" size="22" class="icon text-theme dark-text-theme" />
        <smartphone-icon v-if="icon === 'smartphone'" size="22" class="icon text-theme dark-text-theme" />
        <key-icon v-if="icon === 'key'" size="22" class="icon text-theme dark-text-theme" />
        <b class="label">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import {
		KeyIcon,
		Edit2Icon,
		SettingsIcon,
		SmartphoneIcon,
	} from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        props: ['icon'],
        components: {
			KeyIcon,
            Edit2Icon,
            SettingsIcon,
			SmartphoneIcon,
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

    .dark-mode {
        .form-label {

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
