<template>
    <button class="mobile-action-button">
        <FontAwesomeIcon class="icon" :icon="icon"></FontAwesomeIcon>
        <label label="file" class="label button file-input button-base">
            <slot></slot>
            <input
                    accept="*"
                    v-show="false"
                    @change="emmitFiles"
                    id="file"
                    type="file"
                    name="files[]"
                    multiple
            />
        </label>
    </button>
</template>

<script>
    export default {
        name: 'MobileActionButtonUpload',
        props: [
            'icon'
        ],
        data() {
            return {
                files: undefined
            }
        },
        methods: {
            emmitFiles(e) {
                this.$uploadFiles(e.target.files)
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";


    .mobile-action-button {
        background: $light_background;
        margin-right: 15px;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        border: none;

        .icon {
            margin-right: 8px;
            @include font-size(14);
        }

        .label {
            @include font-size(14);
            font-weight: 700;
            color: $text;
        }
    }

    @media (prefers-color-scheme: dark) {
        .mobile-action-button {
            background: $dark_mode_foreground;

            .icon path {
                fill: $theme;
            }

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
