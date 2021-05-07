<template>
    <div class="info-wrapper">

		<!--Is empty clipboard-->
		<EmptyMessage
			v-if="isEmpty"
			:message="$t('messages.nothing_to_preview')"
			icon="eye-off"
		/>

		<div v-if="isClient">

			<!--Single file preview-->
			<div v-if="isSingleFile && !isEmpty" class="info-headline">
				<TitlePreview
					icon="user"
					:title="singleFile.name"
					subtitle="Client"
				/>
			</div>

			<!--File info-->
			<ListInfo v-if="isSingleFile && !isEmpty">

				<ListInfoItem
					:title="$t('in_editor.client_email')"
					:content="singleFile.email"
				/>

				<ListInfoItem
					:title="$t('in.total_net')"
					:content="singleFile.totalNet"
				/>

				<ListInfoItem
					:title="$t('in.total_invoices')"
					:content="singleFile.totalInvoices + ' ' + $t('global.pcs')"
				/>

				<!--Created At-->
				<ListInfoItem
					:title="$t('file_detail.created_at')"
					:content="singleFile.created_at"
				/>
			</ListInfo>
		</div>

		<div v-if="isInvoice">

			<!--Multiple item selection-->
			<div v-if="! isSingleFile && !isEmpty" class="info-headline">
				<TitlePreview
					icon="check-square"
					:title="$t('file_detail.selected_multiple')"
					:subtitle="this.clipboard.length + ' ' + $tc('file_detail.items', this.clipboard.length)"
				/>
			</div>

			<!--Single file preview-->
			<div v-if="isSingleFile && !isEmpty" class="info-headline">
				<TitlePreview
					icon="file-text"
					:title="singleFile.client_name"
					:subtitle="$t('invoice_title') + ' - ' + singleFile.invoice_number"
				/>
			</div>

			<!--File info-->
			<ListInfo v-if="isSingleFile && !isEmpty">

				<ListInfoItem
					:title="$t('invoice_number')"
					:content="singleFile.invoice_number"
				/>

				<ListInfoItem
					:title="$t('total')"
					:content="singleFile.total"
				/>

				<ListInfoItem
					:title="$t('client')"
					:content="singleFile.client_name"
				/>

				<!--Created At-->
				<ListInfoItem
					:title="$t('file_detail.created_at')"
					:content="singleFile.created_at"
				/>
			</ListInfo>
		</div>
    </div>
</template>

<script>
	import FilePreviewDetail from '@/components/Others/FilePreviewDetail'
    import {Edit2Icon, LockIcon, UnlockIcon} from 'vue-feather-icons'
	import ImageMetaData from '@/components/FilesView/ImageMetaData'
    import EmptyMessage from '@/components/FilesView/EmptyMessage'
	import TitlePreview from '@/components/FilesView/TitlePreview'
	import CopyInput from '@/components/Others/Forms/CopyInput'
	import ListInfoItem from '@/components/Others/ListInfoItem'
	import ListInfo from '@/components/Others/ListInfo'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"

	export default {
		name: 'InvoiceInfoSidebar',
		components: {
			FilePreviewDetail,
			ImageMetaData,
			EmptyMessage,
			TitlePreview,
			ListInfoItem,
			UnlockIcon,
			CopyInput,
			Edit2Icon,
			LockIcon,
			ListInfo,
		},
		computed: {
			...mapGetters([
				'permissionOptions',
				'clipboard',
			]),
			isInvoice() {
				return this.clipboard[0] && this.clipboard[0].type === 'invoice'
			},
			isClient() {
				return this.clipboard[0] && this.clipboard[0].type === 'client'
			},
			isEmpty() {
				return this.clipboard.length === 0
			},
			isSingleFile() {
				return this.clipboard.length === 1
			},
			singleFile() {
				return this.clipboard[0]
			},
			canShowMetaData() {
				return this.clipboard[0].metadata && this.clipboard[0].metadata.ExifImageWidth
			},
			isLocked() {
				return this.clipboard[0].shared.is_protected
			},
			sharedInfo() {
				let title = this.permissionOptions.find(option => {
					return option.value === this.clipboard[0].shared.permission
				})

				return title ? this.$t(title.label) : this.$t('shared.can_download')
			},
		},
		methods: {
			openShareOptions() {
				events.$emit('popup:open', {name: 'share-edit', item: this.clipboard[0]})
			},
			openMoveOptions() {
				events.$emit("popup:open", {name: "move", item: this.clipboard});
			}
		}
	}
</script>

<style scoped lang="scss">

	.info-wrapper {
		padding-bottom: 20px;
		height: 100%;
	}

	.info-headline {
		margin-bottom: 20px;
		border-radius: 8px;
	}

	.share-link {
		display: flex;
		width: 100%;
		align-items: center;
		margin-top: 10px;

		.lock-icon {
			display: inline-block;
			width: 15px;
			margin-right: 9px;
			cursor: pointer;
		}

		.copy-share-link {
			width: 100%;
		}
	}
</style>
