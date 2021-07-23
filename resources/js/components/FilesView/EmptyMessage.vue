<template>
    <div class="empty-message">
        <div class="message">
            <eye-off-icon v-if="icon === 'eye-off'" size="36" class="icon"></eye-off-icon>
            <p>{{ message }}</p>
        </div>
    </div>
</template>

<script>
    import { EyeOffIcon } from 'vue-feather-icons'

    export default {
        name: 'EmptyMessage',
        props: ['icon', 'message'],
        components: {
            EyeOffIcon
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .empty-message {
        text-align: center;
        display: flex;
        align-items: center;
        height: 100%;

        .message {
            margin: 0 auto;

            p {
                margin-top: 10px;
                max-width: 130px;
                @include font-size(13);
                font-weight: 500;
                color: $text-muted;
            }

            .icon {
                path, line, polyline, rect, circle {
                    stroke: $text;
                }
            }
        }
    }

    .dark-mode {
        .empty-message {

            .message {
                .icon {
                    path, line, polyline, rect, circle {
                        stroke: $dark_mode_text_secondary;
                    }
                }

                p {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>
