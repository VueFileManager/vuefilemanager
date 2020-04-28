<template>
    <div class="inline-wrapper icon-append copy-input" :class="size" @click="copyUrl">
        <input ref="sel" :value="value" id="link-input" type="text" class="input-text" readonly>
        <div class="icon">
            <FontAwesomeIcon :icon="isCopiedLink ? 'check' : 'link'"/>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'CopyInput',
        props: ['size', 'value'],
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
    @import "@assets/app.scss";
    @import "@assets/vue-file-manager/_inapp-forms.scss";

    // Single page
    .copy-input {

        &.small {

            &.icon-append {

                .icon {
                    padding: 8px 10px;
                    @include font-size(11);
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
</style>
