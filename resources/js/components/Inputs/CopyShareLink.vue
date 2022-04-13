<template>
    <div class="relative flex items-center">
        <input
            ref="sel"
            :value="item.data.relationships.shared.data.attributes.link"
            :id="id"
            type="text"
            class="focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background py-2 pr-16 pl-3 text-sm font-bold dark:bg-2x-dark-foreground"
            readonly
        />

        <!--Copy icon-->
        <div class="flex items-center">
            <div @click="copyUrl" class="absolute right-9 p-1">
                <copy-icon v-if="!isCopiedLink" size="14" class="hover-text-theme vue-feather cursor-pointer" />
                <check-icon v-if="isCopiedLink" size="14" class="hover-text-theme vue-feather cursor-pointer" />
            </div>
            <div @click.stop.prevent="moreOptions" class="absolute right-2.5 p-1">
                <more-horizontal-icon size="14" class="hover-text-theme vue-feather cursor-pointer" />
            </div>
        </div>

        <!--Hidden options-->
        <ul
            v-if="isOpenedMoreOptions"
            class="absolute top-12 left-0 right-0 z-10 select-none overflow-y-auto overflow-x-hidden rounded-lg shadow-xl"
        >
            <li
				v-if="item.data.type !== 'folder' && !item.data.relationships.shared.data.attributes.protected"
                @click="copyDirectLink"
                class="block flex cursor-pointer items-center bg-white py-2.5 px-5 hover:bg-light-background dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground"
            >
                <div class="w-8">
                    <download-icon size="14" />
                </div>
                <span class="text-sm font-bold">
                    {{ $t('copy_direct_download_link') }}
                </span>
            </li>
            <li
                @click="getQrCode"
                class="block flex cursor-pointer items-center bg-white py-2.5 px-5 hover:bg-light-background dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground"
            >
                <div class="w-8">
                    <camera-icon size="14" />
                </div>
                <span class="text-sm font-bold">
                    {{ $t('get_qr_code') }}
                </span>
            </li>
            <li
                @click="sendViaEmail"
                class="block flex cursor-pointer items-center bg-white py-2.5 px-5 hover:bg-light-background dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground"
            >
                <div class="w-8">
                    <send-icon size="14" />
                </div>
                <span class="text-sm font-bold">
                    {{ $t('sharelink.share_via_email') }}
                </span>
            </li>
            <li
                @click="copyIframe"
                class="block flex cursor-pointer items-center bg-white py-2.5 px-5 hover:bg-light-background dark:bg-2x-dark-foreground dark:hover:bg-4x-dark-foreground"
            >
                <div class="w-8">
                    <code-icon size="14" />
                </div>
                <span class="text-sm font-bold">
                    {{ $t('sharelink.copy_embed') }}
                </span>
            </li>
        </ul>

        <textarea
            v-model="directLink"
            ref="directLinkTextarea"
            class="pointer-events-none absolute right-full opacity-0"
        ></textarea>

        <textarea
            v-model="iframeCode"
            ref="iframe"
            class="pointer-events-none absolute right-full opacity-0"
        ></textarea>
    </div>
</template>

<script>
import { DownloadIcon, CameraIcon, CopyIcon, CheckIcon, SendIcon, MoreHorizontalIcon, CodeIcon } from 'vue-feather-icons'
import { events } from '../../bus'

export default {
    name: 'CopyShareLink',
    props: ['item'],
    components: {
        MoreHorizontalIcon,
        CameraIcon,
        CheckIcon,
        CopyIcon,
        CodeIcon,
        SendIcon,
		DownloadIcon,
    },
	watch: {
		'item': function () {
			this.setClipboard()
		}
	},
    data() {
        return {
            id: 'link-input-' + Math.floor(Math.random() * 10000000),
			directLink: undefined,
            iframeCode: undefined,
            isCopiedLink: false,
            isOpenedMoreOptions: false,
        }
    },
    methods: {
        moreOptions() {
            this.isOpenedMoreOptions = !this.isOpenedMoreOptions
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
		copyDirectLink() {
            let copyText = this.$refs.directLinkTextarea

            copyText.select()
            copyText.setSelectionRange(0, 99999)

            document.execCommand('copy')

            events.$emit('toaster', {
                type: 'success',
                message: this.$t('direct_link_copied'),
            })

			this.isOpenedMoreOptions = false
        },
		copyIframe() {
			let copyText = this.$refs.iframe

			copyText.select()
			copyText.setSelectionRange(0, 99999)

			document.execCommand('copy')

			events.$emit('toaster', {
				type: 'success',
				message: this.$t('web_code_copied'),
			})

			this.isOpenedMoreOptions = false
		},
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
		setClipboard() {
			this.directLink = this.item.data.relationships.shared.data.attributes.link + '/direct'
			this.iframeCode = `<iframe src="${this.item.data.relationships.shared.data.attributes.link}" width="790" height="400" allowfullscreen frameborder="0"></iframe>`
		}
    },
	created() {
		this.setClipboard()
	}
}
</script>
