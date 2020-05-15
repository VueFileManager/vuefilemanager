<template>
    <label label="file" class="button file-input">
        <upload-cloud-icon size="17"></upload-cloud-icon>
        <input
                @change="emmitFiles"
                v-show="false"
                id="file"
                type="file"
                name="files[]"
                multiple
        />
    </label>
</template>

<script>
    import { UploadCloudIcon } from 'vue-feather-icons'

    export default {
        name: 'ToolbarButtonUpload',
        props: ['action'],
        components: {
            UploadCloudIcon,
        },
        methods: {
            emmitFiles(e) {
                this.$uploadFiles(e.target.files)
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .button {
        height: 42px;
        width: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        border: none;

        &:hover {
            background: $light_background;

            path, line, polyline, rect, circle {
                @include transition(150ms);
                stroke: $theme;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .button {
            background: transparent;

            &:hover {
                background: $dark_mode_foreground;
            }

            path, line, polyline, rect, circle {
                stroke: $dark_mode_text_primary;
            }
        }
    }
</style>
