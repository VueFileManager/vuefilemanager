<template>
    <button class="button-base" :class="buttonStyle" type="button">
        <div v-if="loading" class="icon">
            <refresh-cw-icon size="16" class="sync-alt"></refresh-cw-icon>
		</div>
        <div class="content">
            <slot v-if="! loading"></slot>
        </div>
    </button>
</template>

<script>
    import { RefreshCwIcon } from 'vue-feather-icons'

    export default {
        name: 'ButtonBase',
        props: ['buttonStyle', 'loading'],
        components: {
            RefreshCwIcon,
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .button-base {
        @include font-size(15);
        font-weight: 700;
        cursor: pointer;
        transition: 0.15s all ease;
        border-radius: 8px;
        border: 0;
        padding: 10px 28px;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;

        .icon {
            line-height: 1;
            margin-right: 10px;
        }

        &:active {
            transform: scale(0.95);
        }

        &.theme {
            background: rgba($theme, .1);

            .content {
                color: $theme;
            }

            polyline, path {
                stroke: $theme;
            }
        }

        &.theme-solid {
            background: $theme;

            .content {
                color: white;
            }

            polyline, path {
                stroke: white;
            }
        }

        &.danger {
            background: rgba($danger, .1);

            .content {
                color: $danger;
            }

            polyline, path {
                stroke: $danger;
            }
        }

        &.danger-solid {
            background: $danger;

            .content {
                color: white;
            }

            polyline, path {
                stroke: white;
            }
        }

        &.secondary {
            background: $light_background;

            .content {
                color: $text;
            }

            polyline, path {
                stroke: $text;
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

    @media (prefers-color-scheme: dark) {

        .button-base {

            &.secondary {
                background: $dark_mode_foreground;

                .content {
                    color: $dark_mode_text_primary;
                }

                polyline, path {
                    stroke: $theme;
                }
            }
        }
    }
</style>
