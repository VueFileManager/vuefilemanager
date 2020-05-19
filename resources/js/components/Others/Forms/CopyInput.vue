<template>
    <div class="inline-wrapper icon-append copy-input" :class="size" @click="copyUrl">
        <input ref="sel" :value="value" id="link-input" type="text" class="input-text" readonly>
        <div class="icon">
            <link-icon v-if="! isCopiedLink" size="14"></link-icon>
            <check-icon v-if="isCopiedLink" size="14"></check-icon>
        </div>
    </div>
</template>

<script>
    import { LinkIcon, CheckIcon } from 'vue-feather-icons'

    export default {
        name: 'CopyInput',
        props: ['size', 'value'],
        components: {
            CheckIcon,
            LinkIcon,
        },
        data() {
            return {
                isCopiedLink: false,
            }
        },
        methods: {
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

        .copy-input {
            input {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
