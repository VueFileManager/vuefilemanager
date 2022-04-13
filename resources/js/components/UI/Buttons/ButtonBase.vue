<template>
    <button class="button-base" :class="buttonStyle" type="button">
        <div v-if="loading" class="icon">
            <refresh-cw-icon size="16" class="animate-spin" />
        </div>
        <div class="content">
            <slot v-if="!loading" />
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
    },
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

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

    &.theme-solid {
        .content {
            color: white;
        }
    }

    &.danger {
        background: rgba($danger, 0.1);

        .content {
            color: $danger;
        }

        polyline,
        path {
            stroke: $danger;
        }
    }

    &.danger-solid {
        background: $danger;

        .content {
            color: white;
        }

        polyline,
        path {
            stroke: white;
        }
    }

    &.secondary {
        background: $light_background;

        .content {
            color: $text;
        }

        polyline,
        path {
            stroke: $text;
        }
    }
}

.dark {
    .button-base {
        &.secondary {
            background: $dark_mode_foreground;

            .content {
                color: $dark_mode_text_primary;
            }

            polyline,
            path {
                color: inherit;
            }
        }
    }

    .popup-wrapper {
        .button-base.secondary {
            background: lighten($dark_mode_foreground, 3%);
        }
    }
}
</style>
