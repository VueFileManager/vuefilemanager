<template>
    <div class="flex items-center relative">
        <input ref="sel" :value="item.data.relationships.shared.data.attributes.link" :id="id" type="text" class="pr-16 py-2 pl-3 text-sm focus-border-theme input-dark" readonly>

		<!--Copy icon-->
		<div class="flex items-center">
            <div @click="copyUrl" class="absolute right-9 p-1">
                <copy-icon v-if="! isCopiedLink" size="14" class="cursor-pointer hover-text-theme vue-feather"/>
                <check-icon v-if="isCopiedLink" size="14" class="cursor-pointer hover-text-theme vue-feather"/>
            </div>
            <div @click.stop.prevent="moreOptions" class="absolute right-2.5 p-1">
                <more-horizontal-icon size="14" class="cursor-pointer hover-text-theme vue-feather" />
            </div>
        </div>

		<!--Hidden options-->
		<ul v-if="isOpenedMoreOptions" class="shadow-xl rounded-lg absolute top-12 left-0 right-0 z-10 overflow-y-auto overflow-x-hidden select-none">
			<li @click="getQrCode" class="flex items-center py-2.5 px-5 block cursor-pointer dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground hover:bg-light-background bg-white">
				<div class="w-8">
					<camera-icon size="14" />
				</div>
				<span class="text-sm font-bold">
					{{ $t('Get QR Code') }}
				</span>
			</li>
			<li @click="sendViaEmail" class="flex items-center py-2.5 px-5 block cursor-pointer dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground hover:bg-light-background bg-white">
				<div class="w-8">
					<send-icon size="14" />
				</div>
				<span class="text-sm font-bold">
					{{ $t('sharelink.share_via_email') }}
				</span>
			</li>
			<li @click="copyIframe" class="flex items-center py-2.5 px-5 block cursor-pointer dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground hover:bg-light-background bg-white">
				<div class="w-8">
					<code-icon size="14" />
				</div>
				<span class="text-sm font-bold">
					{{ $t('sharelink.copy_embed') }}
				</span>
			</li>
		</ul>

		<textarea v-model="iframeCode" ref="iframe" class="absolute right-full opacity-0 pointer-events-none"></textarea>
    </div>
</template>

<script>
import { CameraIcon, CopyIcon, CheckIcon, SendIcon, MoreHorizontalIcon, CodeIcon } from 'vue-feather-icons'
import { events } from '/resources/js/bus'

export default {
    name: 'CopyShareLink',
    props: [
		'item',
	],
    components: {
		MoreHorizontalIcon,
		CameraIcon,
        CheckIcon,
		CopyIcon,
		CodeIcon,
        SendIcon
    },
    data() {
        return {
			id: 'link-input-' + Math.floor(Math.random() * 10000000),
			iframeCode: '',
            isCopiedLink: false,
			isOpenedMoreOptions: false,
        }
    },
    methods: {
        moreOptions() {
            this.isOpenedMoreOptions = ! this.isOpenedMoreOptions
        },
		getQrCode() {
			events.$emit('popup:open', {
				name: 'share-edit',
				item: this.item,
				section: 'qr-code',
			})

			this.isOpenedMoreOptions = false
		},
		sendViaEmail() {
            events.$emit('popup:open', {
                name: 'share-edit',
                item: this.item,
                section: 'email-sharing',
            })

			this.isOpenedMoreOptions = false
        },
		copyIframe() {
        	// generate iframe
        	this.iframeCode = `<iframe src="${this.item.data.relationships.shared.link}" width="790" height="400" allowfullscreen frameborder="0"></iframe>`

			let copyText = this.$refs.iframe

			copyText.select()
			copyText.setSelectionRange(0, 99999)

			document.execCommand('copy')

			events.$emit('toaster', {
				type: 'success',
				message: this.$t('Your web insert code was copied'),
			})

			this.isOpenedMoreOptions = false
        },
        copyUrl() {

            // Get input value
            var copyText = document.getElementById(this.id)

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
