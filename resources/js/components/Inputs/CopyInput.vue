<template>
    <div @click="copyUrl" class="relative flex items-center">
        <input ref="sel" :value="str" :id="id" type="text" class="focus-border-theme input-dark !pr-10" readonly />

        <!--Copy icon-->
        <div class="absolute right-0 px-4">
            <copy-icon v-if="!isCopiedLink" size="16" class="hover-text-theme vue-feather cursor-pointer" />
            <check-icon v-if="isCopiedLink" size="16" class="text-theme vue-feather cursor-pointer" />
        </div>
    </div>
</template>

<script>
import { CopyIcon, CheckIcon, SendIcon } from 'vue-feather-icons'

export default {
    name: 'CopyInput',
    props: ['size', 'str'],
    components: {
        CheckIcon,
        CopyIcon,
        SendIcon,
    },
    data() {
        return {
            isCopiedLink: false,
            id: 'link-input-' + Math.floor(Math.random() * 10000000),
        }
    },
    methods: {
        copyUrl() {
            // Get input value
            let copyText = document.getElementById(this.id)

            // select link
            copyText.select()
            copyText.setSelectionRange(0, 99999)

            // Copy
            document.execCommand('copy')

            // Mark button as copied
            this.isCopiedLink = true

            // Reset copy button
            setTimeout(() => {
                this.isCopiedLink = false
            }, 1000)
        },
    },
}
</script>
