<template>
    <button class="mobile-action-button" :class="{'active' : mobileSelectingActive}">
        <div class="flex" >
            <CheckSquareIcon size="15" class="icon"></CheckSquareIcon>
            <span class="label">
                <slot></slot>
            </span>
        </div>
    </button>
</template>

<script>
    import {CheckSquareIcon} from "vue-feather-icons";
    import {events} from '@/bus'

    export default {
        name: 'MobileActionButton',
        props: [
            'icon'
        ],
        components: {
            CheckSquareIcon
        },
        data () {
            return {
                mobileSelectingActive: false
            }
        },
      mounted() {
          events.$on('mobileSelecting:start' , () => {
              this.mobileSelectingActive = true
          })

           events.$on('mobileSelecting:stop' , () => {
              this.mobileSelectingActive = false
          })
      }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-action-button {
        background: $light_background;
        margin-right: 15px;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        border: none;
        @include transition(150ms);

        .flex {
            display: flex;
            align-items: center;
        }

        .icon {
            margin-right: 10px;
            @include font-size(14);

            path, line, polyline, rect, circle {
                @include transition(150ms);
            }
        }

        .label {
            @include transition(150ms);
            @include font-size(14);
            font-weight: 700;
            color: $text;
        }
    }
    .active {
            .icon {
                path, line, polyline, rect, circle {
                    stroke: $theme !important;
                }
            }

            .label {
                color: $theme !important;
            }
    }

    @media (prefers-color-scheme: dark) {
        .mobile-action-button {
            background: $dark_mode_foreground;

            path, line, polyline, rect, circle {
                stroke: $theme;
            }

            .label {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
