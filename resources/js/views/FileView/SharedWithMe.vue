<template>
	<div>
		<MobileContextMenu>
			<OptionGroup v-if="canEdit && item">
				<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
				<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
				<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
			</OptionGroup>
            <OptionGroup v-if="item">
                <Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
		</MobileContextMenu>

		<MobileCreateMenu>
			<OptionGroup>
				<OptionUpload :title="$t('actions.upload')" is-hover-disabled="true" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.stop.native="createFolder" :title="$t('actions.create_folder')" icon="folder-plus" is-hover-disabled="true" />
			</OptionGroup>
		</MobileCreateMenu>

		<MobileMultiSelectToolbar>
			<ToolbarButton v-if="canEdit" @click.native="$moveFileOrFolder(clipboard)" class="action-btn" source="move" :action="$t('actions.move')" :class="{'is-inactive' : clipboard.length < 1}" />
			<ToolbarButton v-if="canEdit" @click.native="$deleteFileOrFolder(clipboard)" class="action-btn" source="trash" :class="{'is-inactive' : clipboard.length < 1}" :action="$t('actions.delete')" />
            <ToolbarButton @click.native="$downloadSelection(item)" class="action-btn" source="download" :action="$t('actions.download')" />
		</MobileMultiSelectToolbar>

		<ContextMenu>
			<template v-slot:empty-select v-if="canEdit">
				<OptionGroup v-if="! isTeamFolderHomepage">
					<OptionUpload :title="$t('actions.upload')" />
					<Option @click.stop.native="$createFolder" :title="$t('actions.create_folder')" icon="folder-plus" />
				</OptionGroup>
			</template>

			<template v-slot:single-select v-if="item">
				<OptionGroup v-if="canEdit">
					<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
					<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>

			<template v-slot:multiple-select v-if="item">
				<OptionGroup v-if="canEdit">
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<FileBrowser>
			<template v-slot:file-actions-mobile>
				<MobileActionButton @click.native="$openSpotlight" icon="search">
					{{ $t('actions.search') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$showLocations" icon="filter">
					{{ $getCurrentSectionName() }}
				</MobileActionButton>
				<MobileActionButton v-if="canEdit" @click.native="$createItems" icon="cloud-plus">
					{{ $t('mobile.create') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
					{{ $t('context_menu.select') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$showViewOptions" icon="preview-sorting">
					{{ $t('preview_sorting.preview_sorting_button') }}
				</MobileActionButton>
			</template>

			<template v-slot:empty-file-page>

				<!--Homepage-->
				<template v-if="isTeamFolderHomepage">
					<h1 class="title">
						{{ $t('Nothing shared with you') }}
					</h1>
					<p class="description">
						{{ $t('All items that are shared with you will be visible here.') }}
					</p>
				</template>

				<!--Empty folder wit can-edit privileges -->
				<template v-if="canEdit && ! isTeamFolderHomepage">
					<h1 class="title">
						{{ $t('empty_page.title') }}
					</h1>
					<p class="description">
						{{ $t('empty_page.description') }}
					</p>
					<ButtonUpload button-style="theme">
						{{ $t('empty_page.call_to_action') }}
					</ButtonUpload>
				</template>

				<!--Empty folder wit can-view privileges -->
				<template v-if="! canEdit && ! isTeamFolderHomepage">
					<h1 class="title">
						{{ $t('There is Nothing Yet') }}
					</h1>
				</template>
			</template>
		</FileBrowser>
	</div>
</template>

<script>
    import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileMultiSelectToolbar from "/resources/js/components/FilesView/MobileMultiSelectToolbar"
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
	import MobileContextMenu from "/resources/js/components/FilesView/MobileContextMenu"
	import MobileCreateMenu from '/resources/js/components/FilesView/MobileCreateMenu'
    import ButtonUpload from '/resources/js/components/FilesView/ButtonUpload'
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
	import OptionUpload from '/resources/js/components/FilesView/OptionUpload'
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../bus";
	import Vue from "vue";
	import router from "../../router";

	export default {
		name: 'SharedWithMe',
		components: {
			MobileActionButtonUpload,
			MobileMultiSelectToolbar,
			MobileActionButton,
			MobileContextMenu,
			MobileCreateMenu,
			ToolbarButton,
			ButtonUpload,
			OptionUpload,
			OptionGroup,
			FileBrowser,
			ContextMenu,
			ButtonBase,
			Option,
		},
		computed: {
			...mapGetters([
				'currentTeamFolder',
				'clipboard',
				'config',
				'user',
			]),
			canEdit() {
				if (this.currentTeamFolder && this.user) {
					let member = this.currentTeamFolder.data.relationships.members.data.find(member => member.data.id === this.user.data.id)

					return member.data.attributes.permission === 'can-edit'
				}

				return false
			},
			isTeamFolderHomepage() {
				return this.$isThisRoute(this.$route, ['SharedWithMe'])
					&& ! this.$route.params.id
			},
			isFolder() {
				return this.item && this.item.data.type === 'folder'
			},
			hasFile() {
				return this.clipboard.find(item => item.type !== 'folder')
			},
		},
		data() {
			return {
				item: undefined,
			}
		},
		methods: {
			createFolder() {
				events.$emit('popup:open', {name: 'create-folder'})
			},
		},
		created() {
			this.$store.dispatch('getSharedWithMeFolder', this.$route.params.id)

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('mobile-context-menu:show', item => this.item = item)
			events.$on('context-menu:current-folder', folder => this.item = folder)
		}
	}
</script>
