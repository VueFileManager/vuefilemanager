<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
            </OptionGroup>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('upload')">
                <OptionUpload :title="$t('upload_files')" type="file" :is-hover-disabled="true" />
                <OptionUpload :title="$t('upload_folder')" type="folder" />
            </OptionGroup>
            <OptionGroup :title="$t('create')">
                <Option
					@click.stop.native="createFolder"
					:title="$t('create_folder')"
					icon="folder-plus"
					:is-hover-disabled="true"
				/>
            </OptionGroup>
        </MobileCreateMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
				@click.native="$moveFileOrFolder(clipboard)"
				class="mr-4"
				source="move"
				:action="$t('move')"
				:class="{ 'is-inactive': clipboard.length < 1 }"
			/>
            <ToolbarButton
				@click.native="$deleteFileOrFolder()"
				source="trash"
				:class="{ 'is-inactive': clipboard.length < 1 }"
				:action="$t('delete')"
			/>
        </MobileMultiSelectToolbar>

        <ContextMenu v-if="canShowUI">
            <template v-slot:empty-select>
                <OptionGroup>
                    <OptionUpload :title="$t('upload_files')" type="file" />
                    <OptionUpload :title="$t('upload_folder')" type="folder" />
                </OptionGroup>
                <OptionGroup>
                    <Option
						@click.native="$createFolder"
						:title="$t('create_folder')"
						icon="create-folder"
					/>
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup>
                    <Option
						@click.native="$renameFileOrFolder(item)"
						:title="$t('rename')"
						icon="rename"
					/>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$openInDetailPanel(item)" :title="$t('detail')" icon="detail" />
                </OptionGroup>
            </template>

            <template v-slot:multiple-select v-if="item">
                <OptionGroup>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile v-if="canShowUI">
			<!--I am Done-->
			<button @click="uploadingDone" class="flex shrink-0 items-center mr-2 rounded-xl bg-theme-200 py-1 px-1 pr-3">
				<MemberAvatar
					:member="uploadRequest.data.relationships.user"
					:size="26"
				/>
				<b class="text-theme ml-2 text-sm">
					{{ $t('tell_you_are_done', {name: userName}) }}
				</b>
			</button>

            <MobileActionButton
				@click.native="$showMobileMenu('create-list')"
				v-if="$checkPermission(['master', 'editor'])"
				icon="cloud-plus"
			>
                {{ $t('upload_or_create') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
                {{ $t('select') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </FileActionsMobile>

        <EmptyFilePage v-if="uploadRequest">

			<div v-if="uploadRequest.data.attributes.status === 'filling'">
				<h1 class="title">
					{{ $t('empty_page.title') }}
				</h1>
				<p class="description">
					{{ $t('empty_page.description') }}
				</p>
				<ButtonUpload button-style="theme">
					{{ $t('empty_page.call_to_action') }}
				</ButtonUpload>
			</div>

			<div v-if="['active', 'filled', 'expired'].includes(uploadRequest.data.attributes.status) && fileQueue.length === 0">
				<div class="relative mx-auto mb-8 w-24 text-center">
					<VueFolderIcon class="inline-block w-28" />
					<MemberAvatar
						v-if="uploadRequest.data.attributes.status !== 'expired'"
						:member="uploadRequest.data.relationships.user"
						class="absolute -bottom-2.5 -right-2"
						:is-border="true"
						:size="32"
					/>
				</div>

				<h1 class="title">
					{{ emptyPageTitle }}
				</h1>

				<p class="description max-w-[420px] mx-auto">
					{{ emptyPageDescription }}
				</p>

				<InfoBox v-if="uploadRequest.data.attributes.notes && uploadRequest.data.attributes.status === 'active'" class="max-w-[420px] mx-auto">
					<b>{{ $t('user_leave_message', {name: userName}) }}: </b>
					<p>{{ uploadRequest.data.attributes.notes }}</p>
				</InfoBox>

				<ButtonUpload v-if="uploadRequest.data.attributes.status === 'active'" button-style="theme">
					{{ $t('empty_page.call_to_action') }}
				</ButtonUpload>
			</div>
        </EmptyFilePage>

        <FileBrowser />
    </div>
</template>

<script>
import MobileMultiSelectToolbar from '../../components/FilesView/MobileMultiSelectToolbar'
import MobileActionButton from '../../components/FilesView/MobileActionButton'
import FileActionsMobile from '../../components/FilesView/FileActionsMobile'
import MobileContextMenu from '../../components/FilesView/MobileContextMenu'
import VueFolderIcon from '../../components/FilesView/Icons/VueFolderIcon'
import MobileCreateMenu from '../../components/FilesView/MobileCreateMenu'
import EmptyFilePage from '../../components/FilesView/EmptyFilePage'
import ToolbarButton from '../../components/FilesView/ToolbarButton'
import MemberAvatar from '../../components/FilesView/MemberAvatar'
import ButtonUpload from '../../components/FilesView/ButtonUpload'
import OptionUpload from '../../components/FilesView/OptionUpload'
import FileBrowser from '../../components/FilesView/FileBrowser'
import ContextMenu from '../../components/FilesView/ContextMenu'
import OptionGroup from '../../components/FilesView/OptionGroup'
import Option from '../../components/FilesView/Option'
import {events} from '../../bus'
import {mapGetters} from 'vuex'
import InfoBox from "../../components/Others/Forms/InfoBox";

export default {
	name: 'Files',
	components: {
		InfoBox,
		MobileMultiSelectToolbar,
		MobileActionButton,
		FileActionsMobile,
		MobileContextMenu,
		MobileCreateMenu,
		EmptyFilePage,
		VueFolderIcon,
		ToolbarButton,
		MemberAvatar,
		ButtonUpload,
		OptionUpload,
		OptionGroup,
		FileBrowser,
		ContextMenu,
		Option,
	},
	computed: {
		...mapGetters(['fastPreview', 'clipboard', 'config', 'user', 'uploadRequest', 'fileQueue']),
		isFolder() {
			return this.item && this.item.data.type === 'folder'
		},
		userName() {
			return this.uploadRequest.data.relationships.user.data.attributes.name
		},
		emptyPageTitle() {
			return {
				active: this.$t('request_for_upload', {name: this.userName}),
				filled: this.$t('request_for_upload_success', {name: this.userName}),
				expired: this.$t('request_for_upload_expired'),
			}[this.uploadRequest.data.attributes.status]
		},
		emptyPageDescription() {
			return {
				active: this.$t('automatically_uploads_for_file_request'),
				filled: this.$t('request_for_upload_unavailable'),
				expired: this.$t('request_for_upload_unavailable'),
			}[this.uploadRequest.data.attributes.status]
		},
		canShowUI() {
			return this.uploadRequest && this.uploadRequest.data.attributes.status === 'filling' || this.fileQueue.length > 0
		}
	},
	data() {
		return {
			item: undefined,
		}
	},
	methods: {
		uploadingDone() {
			events.$emit('confirm:open', {
				title: this.$t('closing_request_for_upload', {name: this.userName}),
				message: this.$t('closing_request_for_upload_warn'),
				action: {
					id: this.$router.currentRoute.params.token,
					operation: 'close-upload-request',
				},
			})
		},
		createFolder() {
			events.$emit('popup:open', {name: 'create-folder'})
		},
	},
	mounted() {
		events.$on('context-menu:show', (event, item) => (this.item = item))
		events.$on('context-menu:current-folder', (folder) => (this.item = folder))
		events.$on('mobile-context-menu:show', (item) => (this.item = item))

		// Load folder from id in router
		if (this.$route.params.id) {
			this.$store.dispatch('getUploadRequestFolder', this.$route.params.id)
		}

		// Load root folder for upload request
		if (! this.$route.params.id && this.uploadRequest && this.uploadRequest.data.attributes.status === 'filling') {
			this.$store.dispatch('getUploadRequestFolder')
		}
	},
}
</script>
