<template>
    <label label="file" class="button file-input">
        <FontAwesomeIcon class="icon" :icon="source"></FontAwesomeIcon>
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
    export default {
        name: 'ToolbarButtonUpload',
        props: ['source', 'action'],
        methods: {
            emmitFiles(e) {
                this.$uploadFiles(e.target.files)
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .button {
        height: 42px;
        width: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        background: $light_background;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        border: none;

        &:hover {
            background: rgba($theme, .1);

            /deep/ svg path {
                @include transition;
                fill: $theme;
            }
        }

        .icon {
            @include font-size(16);
        }
    }

    @media (prefers-color-scheme: dark) {
        .button {
            background: $dark_mode_foreground;
        }

        .icon svg path {
            fill: $dark_mode_text_secondary;
        }
    }
</style>
