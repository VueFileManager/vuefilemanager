<template>
    <button class="mobile-action-button">
        <div class="flex">
            <cloud-plus-icon class="icon dark-text-theme" size="15" />
            <label label="file" class="label button file-input button-base">
                <slot />
                <input @change="emmitFiles" v-show="false" id="file" type="file" name="files[]" multiple />
            </label>
        </div>
    </button>
</template>

<script>
import { UploadCloudIcon } from 'vue-feather-icons'
import CloudPlusIcon from '../../Icons/CloudPlusIcon'

export default {
    name: 'MobileActionButtonUpload',
    components: {
        CloudPlusIcon,
        UploadCloudIcon,
    },
    methods: {
        emmitFiles(e) {
            this.$uploadFiles(e.target.files)
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.mobile-action-button {
    background: $light_background;
    margin-right: 8px;
    border-radius: 8px;
    padding: 7px 14px;
    cursor: pointer;
    border: none;

    .flex {
        display: flex;
        align-items: center;
    }

    .icon {
        vertical-align: middle;
        margin-right: 10px;
        @include font-size(14);
    }

    .label {
        vertical-align: middle;
        @include font-size(14);
        font-weight: 700;
        color: $text;
    }
}

.dark {
    .mobile-action-button {
        background: $dark_mode_foreground;

        path,
        line,
        polyline,
        rect,
        circle {
            color: inherit;
        }

        .label {
            color: $dark_mode_text_primary;
        }
    }
}
</style>
