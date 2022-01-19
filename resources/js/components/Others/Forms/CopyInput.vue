<template>
    <div @click="copyUrl" class="flex items-center relative">
        <input ref="sel" :value="str" :id="id" type="text" class="pr-10 focus-border-theme input-dark" readonly>

		<!--Copy icon-->
        <div class="absolute right-0 px-4">
			<copy-icon v-if="! isCopiedLink" size="16" class="cursor-pointer hover-text-theme vue-feather"/>
			<check-icon v-if="isCopiedLink" size="16" class="cursor-pointer text-theme vue-feather"/>
        </div>
    </div>
</template>

<script>
import {
	CopyIcon,
	CheckIcon,
	SendIcon
} from 'vue-feather-icons'

export default {
    name: 'CopyInput',
    props: [
		'size',
		'str',
	],
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
        }
    }
}
</script>
