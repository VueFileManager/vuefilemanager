<template>
    <div class="info-wrapper">

		<!--Is empty clipboard-->
		<EmptyMessage
			v-if="isEmpty"
			:message="$t('messages.nothing_to_preview')"
			icon="eye-off"
		/>

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
            <FilePreviewDetail />

			<TitlePreview
				:icon="clipboard[0].type"
				:title="clipboard[0].name"
				:subtitle="clipboard[0].mimetype"
			/>
        </div>

		<!--File info-->
        <ListInfo v-if="isSingleFile && !isEmpty">

			<!--Filesize-->
            <ListInfoItem
				v-if="singleFile.filesize"
				:title="$t('file_detail.size')"
				:content="singleFile.filesize"
			/>

			<!--Participant-->
            <ListInfoItem
				v-if="$checkPermission(['master']) && singleFile.author !== 'user'"
				:title="$t('file_detail.author')"
				:content="$t('file_detail.author_participant')"
			/>

			<!--Created At-->
            <ListInfoItem
				:title="$t('file_detail.created_at')"
				:content="singleFile.created_at"
			/>

			<!--Location-->
            <ListInfoItem
				v-if="$checkPermission(['master'])"
				:title="$t('file_detail.where')"
			>
                <div class="action-button" @click="openMoveOptions">
                    <span>{{ singleFile.parent ? singleFile.parent.name : $t('locations.home') }}</span>
                    <edit-2-icon size="10" class="edit-icon" />
                </div>
            </ListInfoItem>

			<!--Shared-->
            <ListInfoItem
				v-if="$checkPermission('master') && singleFile.shared"
				:title="$t('file_detail.shared')"
			>
                <div @click="openShareOptions" class="action-button">
                    <span>{{ sharedInfo }}</span>
                    <edit-2-icon size="10" class="edit-icon" />
                </div>
                <div class="share-link">
                    <lock-icon v-if="isLocked" @click="openShareOptions" class="lock-icon" size="17" />
                    <unlock-icon v-if="! isLocked" @click="openShareOptions" class="lock-icon" size="17" />
                    <CopyShareLink :item="singleFile" class="copy-share-link" size="small" />
                </div>
            </ListInfoItem>

			<!--Metadata-->
            <ListInfoItem
				v-if="canShowMetaData"
				:title="$t('file_detail_meta.meta_data')"
			>
                <ImageMetaData />
            </ListInfoItem>
        </ListInfo>
    </div>
</template>

<script>
	import FilePreviewDetail from '@/components/Others/FilePreviewDetail'
    import {Edit2Icon, LockIcon, UnlockIcon} from 'vue-feather-icons'
	import ImageMetaData from '@/components/FilesView/ImageMetaData'
    import EmptyMessage from '@/components/FilesView/EmptyMessage'
	import TitlePreview from '@/components/FilesView/TitlePreview'
	import CopyShareLink from '@/components/Others/Forms/CopyShareLink'
	import ListInfoItem from '@/components/Others/ListInfoItem'
	import ListInfo from '@/components/Others/ListInfo'
	import {mapGetters} from 'vuex'
	import {events} from "@/bus"

	export default {
		name: 'InfoSidebar',
		components: {
			FilePreviewDetail,
			ImageMetaData,
			EmptyMessage,
			TitlePreview,
			ListInfoItem,
			UnlockIcon,
			CopyShareLink,
			Edit2Icon,
			LockIcon,
			ListInfo,
		},
		computed: {
			...mapGetters([
				'permissionOptions',
				'clipboard',
			]),
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
