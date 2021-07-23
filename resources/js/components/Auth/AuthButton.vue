<template>
    <button class="button outline hover-text-theme hover-border-theme">
        <span class="text-label">{{ text }}</span>

        <span v-if="loading" class="icon">
			<FontAwesomeIcon icon="sync-alt" class="sync-alt svg-color-theme"/>
		</span>
        <span v-if="! loading && icon" class="icon">
			<FontAwesomeIcon :icon="icon" class="svg-color-theme"/>
		</span>
    </button>
</template>

<script>

    export default {
        name: 'AuthContent',
        props: ['loading', 'icon', 'text'],
        data() {
            return {
                isVisible: false,
            }
        },
        created() {
            this.isVisible = this.visible
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .button {
        cursor: pointer;
        border-radius: 8px;
        text-decoration: none;
        padding: 12px 32px;
        display: inline-block;
        margin-left: 15px;
        margin-right: 15px;
        white-space: nowrap;
        @include transition(150ms);
        background: transparent;

        .text-label {
            @include transition(150ms);
            @include font-size(17);
            font-weight: 800;
            line-height: 0;
        }

        .icon {
            margin-left: 12px;
            @include font-size(16);
        }

        &.solid {
            background: $theme;
            border: 2px solid $theme;

            .text-label {
                color: white;
            }
        }

        &.outline {
            border: 2px solid $text;

            .text-label {
                color: $text;
            }

            .icon {

                path {
                    fill: inherit;
                }
            }

            &:hover {
                border-color: inherit;

                .text-label {
                    color: inherit;
                }
            }
        }
    }

    .dark-mode {
        .button {

            &.outline {
                background: $dark_mode_background;
                border-color: $dark_mode_text_primary;

                .text-label {
                    color: $dark_mode_text_primary;
                }
            }
        }
    }

    .sync-alt {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
