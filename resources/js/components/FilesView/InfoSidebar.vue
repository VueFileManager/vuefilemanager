<template>
    <div class="2xl:w-104 w-96 px-2.5 overflow-y-auto overflow-x-hidden h-screen lg:block hidden">
		<!--Is empty clipboard-->
		<div v-if="isEmpty" class="flex items-center justify-center h-full">
			<div class="text-center">
				<eye-off-icon size="28" class="vue-feather text-gray-400 inline-block mb-3" />
				<small class="text-sm block text-gray-400">
					{{ $t('messages.nothing_to_preview') }}
				</small>
			</div>
		</div>

		<!--Multiple item selection-->
		<TitlePreview
			v-if="! isSingleFile && !isEmpty"
			class="mb-6"
			icon="check-square"
			:title="$t('file_detail.selected_multiple')"
			:subtitle="this.clipboard.length + ' ' + $tc('file_detail.items', this.clipboard.length)"
		/>

		<!--Single file preview-->
        <div v-if="isSingleFile && !isEmpty">
            <FilePreviewDetail />

			<TitlePreview
				class="mb-6"
				:icon="clipboard[0].data.type"
				:title="clipboard[0].data.attributes.name"
				:subtitle="clipboard[0].data.attributes.mimetype"
			/>

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
                <div @click="$moveFileOrFolder(singleFile)" class="flex items-center cursor-pointer">
                    <span class="inline-block font-bold text-sm">
						{{ singleFile.data.relationships.parent ? singleFile.data.relationships.parent.data.attributes.name : $t('locations.home') }}
					</span>
                    <Edit2Icon size="10" class="ml-2" />
                </div>
            </ListInfoItem>

			<!--Team-->
            <ListInfoItem
				v-if="singleFile.data.attributes.isTeamFolder"
				:title="$t('Shared with the Team')"
			>
                <div class="action-button" @click="$updateTeamFolder(singleFile)">
                    <TeamMembersPreview :folder="singleFile" :avatar-size="32" />
                    <Edit2Icon size="10" class="ml-2" />
                </div>
            </ListInfoItem>

			<!--Shared-->
            <ListInfoItem
				v-if="$checkPermission('master') && singleFile.data.relationships.shared"
				:title="$t('file_detail.shared')"
			>
                <div @click="$shareFileOrFolder(singleFile)" class="flex items-center cursor-pointer mb-2">
                    <span class="inline-block font-bold text-sm">
						{{ sharedInfo }}
					</span>
                    <Edit2Icon size="10" class="ml-2" />
                </div>
                <div class="flex items-center w-full">
                    <lock-icon v-if="isLocked" @click="$shareFileOrFolder(singleFile)" size="17"  class="hover-text-theme vue-feather cursor-pointer"/>
                    <unlock-icon v-if="! isLocked" @click="$shareFileOrFolder(singleFile)" size="17"  class="hover-text-theme vue-feather cursor-pointer"/>
                    <CopyShareLink :item="singleFile" size="small" class="w-full pl-2.5"/>
                </div>
            </ListInfoItem>

			<!--Author-->
            <ListInfoItem
				v-if="canShowAuthor"
				:title="$t('Author')"
			>
                <div class="flex items-center mt-1.5">
					<MemberAvatar :size="32" :member="singleFile.data.relationships.owner" />
                    <span class="ml-3 block font-bold font-sm">
						{{ singleFile.data.relationships.owner.data.attributes.name }}
					</span>
                </div>
            </ListInfoItem>

			<!--Metadata-->
            <ListInfoItem
				v-if="canShowMetaData"
				:title="$t('file_detail_meta.meta_data')"
			>
                <ImageMetaData />
            </ListInfoItem>
        </div>
    </div>
</template>

<script>
	import FilePreviewDetail from '/resources/js/components/Others/FilePreviewDetail'
	import CopyShareLink from '/resources/js/components/Others/Forms/CopyShareLink'
	import {Edit2Icon, LockIcon, UnlockIcon, EyeOffIcon} from 'vue-feather-icons'
	import ImageMetaData from '/resources/js/components/FilesView/ImageMetaData'
	import TitlePreview from '/resources/js/components/FilesView/TitlePreview'
	import TeamMembersPreview from "../Teams/Components/TeamMembersPreview"
	import ListInfoItem from '/resources/js/components/Others/ListInfoItem'
	import MemberAvatar from "./MemberAvatar"
	import {mapGetters} from 'vuex'

	export default {
		name: 'InfoSidebar',
		components: {
			TeamMembersPreview,
			FilePreviewDetail,
			ImageMetaData,
			CopyShareLink,
			MemberAvatar,
			TitlePreview,
			ListInfoItem,
			UnlockIcon,
			EyeOffIcon,
			Edit2Icon,
			LockIcon,
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
					&& this.clipboard[0].data.type !== 'folder'
					&& this.user.data.id !== this.clipboard[0].data.relationships.owner.data.id
			},
		},
	}
</script>
