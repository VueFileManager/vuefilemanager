<template>
	<div>
		<MobileContextMenu>
			<OptionGroup v-if="item && isFolder">
				<Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

			<OptionGroup v-if="item">
				<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
				<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
				<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
			</OptionGroup>
			<OptionGroup v-if="item">
				<Option @click.native="$shareFileOrFolder(item)" :title="item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
				<Option @click.native="$convertAsTeamFolder(item)" v-if="isFolder" :title="$t('Convert as Team Folder')" icon="users" />
			</OptionGroup>

            <OptionGroup v-if="item">
                <Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
		</MobileContextMenu>

		<MobileCreateMenu>
			<OptionGroup>
				<OptionUpload :class="{'is-inactive': !hasCapacity }" :title="$t('actions.upload')" type="file" :is-hover-disabled="true" />
				<OptionUpload :title="$t('actions.upload_folder')" type="folder" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.stop.native="$createTeamFolder" :title="$t('Create Team Folder')" icon="users" :is-hover-disabled="true" />
				<Option @click.stop.native="createFolder" :title="$t('actions.create_folder')" icon="folder-plus" :is-hover-disabled="true" />
			</OptionGroup>
		</MobileCreateMenu>

		<MobileMultiSelectToolbar>
			<ToolbarButton @click.native="$moveFileOrFolder(clipboard)" class="action-btn" source="move" :action="$t('actions.move')" :class="{'is-inactive' : clipboard.length < 1}" />
			<ToolbarButton @click.native="$deleteFileOrFolder(clipboard)" class="action-btn" source="trash" :class="{'is-inactive' : clipboard.length < 1}" :action="$t('actions.delete')" />
            <ToolbarButton @click.native="$downloadSelection(item)" class="action-btn" source="download" :action="$t('actions.download')" />
		</MobileMultiSelectToolbar>

		<ContextMenu>
			<template v-slot:empty-select>
				<OptionGroup>
					<OptionUpload :title="$t('actions.upload')" type="file" />
					<OptionUpload :title="$t('actions.upload_folder')" type="folder" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$createFolder" :title="$t('context_menu.create_folder')" icon="create-folder" />
				</OptionGroup>
			</template>

			<template v-slot:single-select v-if="item">
				<OptionGroup v-if="isFolder">
					<Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$shareFileOrFolder(item)" :title="item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
					<Option @click.native="$convertAsTeamFolder(item)" v-if="isFolder" :title="$t('Convert as Team Folder')" icon="user-plus" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
					<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>

			<template v-slot:multiple-select v-if="item">
				<OptionGroup v-if="!hasFile">
					<Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$downloadSelection()" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<FileActionsMobile>
			<MobileActionButton @click.native="$openSpotlight()" icon="search">
				{{ $t('Spotlight') }}
			</MobileActionButton>
			<MobileActionButton @click.native="$showMobileMenu('file-filter')" :icon="$getCurrentSectionIcon()">
				{{ $getCurrentSectionName() }}
			</MobileActionButton>
			<MobileActionButton @click.native="$showMobileMenu('create-list')" v-if="$checkPermission(['master', 'editor'])" icon="cloud-plus">
				{{ $t('mobile.create') }}
			</MobileActionButton>
			<MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
				{{ $t('context_menu.select') }}
			</MobileActionButton>
			<MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
				{{ $t('preview_sorting.preview_sorting_button') }}
			</MobileActionButton>
		</FileActionsMobile>

		<EmptyFilePage>
			<h1 class="title">
				{{ $t('empty_page.title') }}
			</h1>
			<p class="description">
				{{ $t('empty_page.description') }}
			</p>
			<ButtonUpload button-style="theme">
				{{ $t('empty_page.call_to_action') }}
			</ButtonUpload>
		</EmptyFilePage>

		<FileBrowser />
	</div>
</template>

<script>
	import EmptyFilePage from "../../components/FilesView/EmptyFilePage";
	import FileActionsMobile from "../../components/FilesView/FileActionsMobile";
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
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../bus";

	export default {
		name: 'Files',
		components: {
			EmptyFilePage,
			FileActionsMobile,
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
			Option,
		},
		computed: {
			...mapGetters([
				'fastPreview',
				'clipboard',
				'config',
				'user',
			]),
			hasCapacity() {
				// Check if storage limitation is set
				if (!this.config.storageLimit) return true

				// Check if user has storage
				return this.user && this.user.data.attributes.storage.used <= 100
			},
			isFolder() {
				return this.item && this.item.data.type === 'folder'
			},
			isInFavourites() {
				return this.favourites.find((el) => el.id === this.item.data.id)
			},
			hasFile() {
				return this.clipboard.find(item => item.data.type !== 'folder')
			},
			favourites() {
				return this.user.data.relationships.favourites.data
			},
		},
		data() {
			return {
				item: undefined,
			}
		},
		methods: {
			addToFavourites() {
				// Check if folder is in favourites and then add/remove from favourites
				if (this.favourites && !this.favourites.find(el => el.id === this.item.data.id)) {
					// Add to favourite folder that is not selected
					if (!this.clipboard.includes(this.item)) {
						this.$store.dispatch('addToFavourites', this.item)
					}

					// Add to favourites all selected folders
					if (this.clipboard.includes(this.item)) {
						this.$store.dispatch('addToFavourites', null)
					}
				} else {
					this.$store.dispatch('removeFromFavourites', this.item)
				}
			},
			createFolder() {
				events.$emit('popup:open', {name: 'create-folder'})
			},
		},
		created() {
			this.$store.dispatch('getFolder', this.$route.params.id)

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('context-menu:current-folder', folder => this.item = folder)
			events.$on('mobile-context-menu:show', item => this.item = item)
		}
	}
</script>
