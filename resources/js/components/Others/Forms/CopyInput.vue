<template>
    <div class="inline-wrapper icon-append copy-input" :class="size" @click="copyUrl">
        <input ref="sel" :value="value" id="link-input" type="text" class="input-text" readonly>
        <div :class="[shareViaEmail ? 'multi-icon' : 'icon' ]">
            <div>
                <link-icon v-if="! isCopiedLink" size="14"></link-icon>
                <check-icon v-if="isCopiedLink" size="14"></check-icon>
            </div>
             <div @click.stop.prevent="menuForEmail" v-if="! isCopiedLink && shareViaEmail">
                <send-icon  size="14"></send-icon>
            </div>
        </div>
    </div>
</template>

<script>
    import { LinkIcon, CheckIcon,SendIcon } from 'vue-feather-icons'
    import {events} from '@/bus'

    export default {
        name: 'CopyInput',
        props: ['size', 'value', 'shareViaEmail'],
        components: {
            CheckIcon,
            LinkIcon,
            SendIcon,
        },
        data() {
            return {
                isCopiedLink: false,
            }
        },
        methods: {
            menuForEmail(){
                events.$emit('openSendToRecipientsMenu')
            },
            copyUrl() {

                // Get input value
                var copyText = document.getElementById("link-input");

                // select link
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                // Copy
                document.execCommand("copy");

                // Mark button as copied
                this.isCopiedLink = true

                // Reset copy button
                setTimeout(() => {this.isCopiedLink = false}, 1000)
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import "@assets/vue-file-manager/_forms.scss";

    .multi-icon {
        display: flex;
        align-items: center;
        background: $light_background;
        border-bottom-right-radius: 8px ;
        border-top-right-radius: 8px ;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
        

        line,
        path,
        polygon {
            stroke: $text !important;
        }

        & > * {
            padding: 9px 10px;
            display: flex;
            align-items: center;
            border-left: 1px solid $dark_background;

            &:hover {
                background: $text;
                line,
                polyline,
                path,
                polygon {
                    stroke: white !important;
                }
            }

            &:last-child {
                border-bottom-right-radius: 8px ;
                border-top-right-radius: 8px ;
            }
        }

        
    }

    // Single page
    .copy-input {

        &.small {

            &.icon-append {

                .icon {
                    padding: 10px;
                }
            }

            input {
                padding: 6px 10px;
                @include font-size(13);
            }
        }

        .icon {
            cursor: pointer;
        }

        input {
            text-overflow: ellipsis;

            &:disabled {
                color: $text;
                cursor: pointer;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .multi-icon {
        background: $dark_mode_foreground;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
    
        line,
        path,
        polygon {
            stroke: $dark_mode_text_primary !important;
        }

        & > * {
            border-left: 1px solid $dark_mode_background;

            &:hover {
                background: rgba($theme, 0.1);
                line,
                polyline,
                path,
                polygon {
                    stroke: $theme !important;
                }
            }
        }

        
    }

        .copy-input {
            input {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
