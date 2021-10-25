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
				:icon="clipboard[0].data.type"
				:title="clipboard[0].data.attributes.name"
				:subtitle="clipboard[0].data.attributes.mimetype"
			/>
        </div>

		<!--File info-->
        <ListInfo v-if="isSingleFile && !isEmpty">

			<!--Author-->
            <ListInfoItem
				v-if="canShowAuthor"
				:title="$t('Author')"
			>
                <div class="author-preview">
					<MemberAvatar :size="32" :member="singleFile.data.relationships.user" />
                    <span class="name">{{ singleFile.data.relationships.user.data.attributes.name }}</span>
                </div>
            </ListInfoItem>

			<!--Filesize-->
            <ListInfoItem
				v-if="singleFile.data.attributes.filesize"
				:title="$t('file_detail.size')"
				:content="singleFile.data.attributes.filesize"
			/>

			<!--Created At-->
            <ListInfoItem
				:title="$t('file_detail.created_at')"
				:content="singleFile.data.attributes.created_at"
			/>

			<!--Location-->
            <ListInfoItem
				v-if="$checkPermission(['master'])"
				:title="$t('file_detail.where')"
			>
                <div class="action-button" @click="openMoveOptions">
                    <span>{{ singleFile.data.relationships.parent ? singleFile.data.relationships.parent.data.attributes.name : $t('locations.home') }}</span>
                    <edit-2-icon size="10" class="edit-icon" />
                </div>
            </ListInfoItem>

			<!--Location-->
            <ListInfoItem
				v-if="singleFile.data.attributes.isTeamFolder"
				:title="$t('Shared with the Team')"
			>
                <div class="action-button" @click="$updateTeamFolder(singleFile)">
                    <TeamMembersPreview :folder="singleFile" :avatar-size="32" />
                    <edit-2-icon size="10" class="edit-icon" />
                </div>
            </ListInfoItem>

			<!--Shared-->
            <ListInfoItem
				v-if="$checkPermission('master') && singleFile.data.relationships.shared"
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
	import FilePreviewDetail from '/resources/js/components/Others/FilePreviewDetail'
	import CopyShareLink from '/resources/js/components/Others/Forms/CopyShareLink'
	import ImageMetaData from '/resources/js/components/FilesView/ImageMetaData'
	import EmptyMessage from '/resources/js/components/FilesView/EmptyMessage'
	import TitlePreview from '/resources/js/components/FilesView/TitlePreview'
	import TeamMembersPreview from "../Teams/Components/TeamMembersPreview"
	import ListInfoItem from '/resources/js/components/Others/ListInfoItem'
	import {Edit2Icon, LockIcon, UnlockIcon} from 'vue-feather-icons'
	import ListInfo from '/resources/js/components/Others/ListInfo'
	import {events} from '/resources/js/bus'
	import {mapGetters} from 'vuex'
	import MemberAvatar from "./MemberAvatar";

	export default {
		name: 'InfoSidebar',
		components: {
			MemberAvatar,
			TeamMembersPreview,
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
				'user',
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
				return this.clipboard[0].data.attributes.metadata && this.clipboard[0].data.attributes.metadata.ExifImageWidth
			},
			isLocked() {
				return this.clipboard[0].data.relationships.shared.protected
			},
			sharedInfo() {
				let title = this.permissionOptions.find(option => {
					return option.value === this.clipboard[0].data.relationships.shared.permission
				})

				return title ? this.$t(title.label) : this.$t('shared.can_download')
			},
			canShowAuthor() {
				return this.$isThisRoute(this.$route, ['SharedWithMe', 'TeamFolders'])
					&& this.user.data.id !== this.clipboard[0].data.relationships.user.data.id
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

	.author-preview	{
		display: flex;
		align-items: center;

		.name {
			margin-left: 10px;
			display: block;
		}
	}

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
