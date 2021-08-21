<template>
	<div>
		<ContextMenu>
			<template v-slot:empty-select>
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
					<Option @click.native="$shareFileOrFolder(item)" :title="item.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
					<Option @click.native="$updateTeamFolder(item)" v-if="isFolder" :title="$t('Convert as Team Folder')" icon="users" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
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
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<MobileContextMenu>
			<template v-slot:editor v-if="$checkPermission('editor')">
				<OptionGroup>
					<Option v-if="item" @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
					<Option v-if="item" @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
			<template v-slot:visitor v-if="$checkPermission('visitor')">
				<OptionGroup>
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</MobileContextMenu>

		<FileBrowser>
			<template v-if="$checkPermission('editor')" v-slot:file-actions-mobile>
				<MobileActionButton @click.native="$openSpotlight" icon="search">
					{{ $t('actions.search') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$showLocations" icon="filter">
					{{ filterLocationTitle }}
				</MobileActionButton>
				<MobileActionButton @click.native="$createItems" icon="cloud-plus">
					{{ $t('mobile.create') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
					{{ $t('context_menu.select') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$showViewOptions" icon="preview-sorting">
					{{ $t('preview_sorting.preview_sorting_button') }}
				</MobileActionButton>
			</template>

			<template v-if="$checkPermission('visitor')" v-slot:file-actions-mobile>
				<MobileActionButton @click.native="$openSpotlight" icon="search">
					{{ $t('actions.search')}}
				</MobileActionButton>
				 <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
				   {{ $t('context_menu.select') }}
				</MobileActionButton>
				 <MobileActionButton @click.native="$showViewOptions" icon="preview-sorting">
					{{ $t('preview_sorting.preview_sorting_button') }}
				</MobileActionButton>
			</template>

			<template v-slot:empty-file-page>
				<h1 class="title">
					{{ $t('empty_page.title') }}
				</h1>
                <p v-if="$checkPermission('editor')" class="description">
					{{ $t('empty_page.description') }}
				</p>
                <ButtonUpload v-if="$checkPermission('editor')" button-style="theme">
                    {{ $t('empty_page.call_to_action') }}
                </ButtonUpload>
			</template>
		</FileBrowser>

		<MultiSelectToolbar>
			<template v-slot:visitor>
				<ToolbarButton @click.native="downloadItem" class="action-btn" source="download" :action="$t('actions.download')" />
			</template>

			<template v-slot:editor>
				<ToolbarButton @click.native="$moveFileOrFolder(clipboard)" class="action-btn" source="move" :action="$t('actions.move')" :class="{'is-inactive' : clipboard.length < 1}" />
				<ToolbarButton @click.native="$deleteFileOrFolder(clipboard)" class="action-btn" source="trash" :class="{'is-inactive' : clipboard.length < 1}" :action="$t('actions.delete')" />
				<ToolbarButton @click.native="downloadItem" class="action-btn" source="download" :action="$t('actions.download')" />
			</template>
		</MultiSelectToolbar>
	</div>
</template>

<script>
	import MultiSelectToolbar from "/resources/js/components/FilesView/MultiSelectToolbar"
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'

	import MobileContextMenu from "/resources/js/components/FilesView/MobileContextMenu"
	import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import ButtonUpload from '/resources/js/components/FilesView/ButtonUpload'
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../../bus";

	export default {
		name: 'Files',
		components: {
			MultiSelectToolbar,
			ToolbarButton,
			MobileActionButtonUpload,
			MobileActionButton,
			MobileContextMenu,
			ButtonUpload,
			OptionGroup,
			FileBrowser,
			ContextMenu,
			Option,
		},
		computed: {
			...mapGetters([
				'clipboard',
				'user',
			]),
			isFolder() {
				return this.item && this.item.type === 'folder'
			},
			isInFavourites() {
				return this.favourites.find((el) => el.id === this.item.id)
			},
			hasFile() {
				return this.clipboard.find(item => item.type !== 'folder')
			},
			favourites() {
				return this.user.data.relationships.favourites.data.attributes.folders
			},
			filterLocationTitle() {
				return {
					'RecentUploads': this.$t('Recent'),
					'MySharedItems': this.$t('Shared'),
					'Trash': this.$t('Trash'),
					'Public': this.$t('Files'),
					'Files': this.$t('Files'),
				}[this.$route.name]
			}
		},
		data() {
			return {
				item: undefined,
			}
		},
		methods: {
			addToFavourites() {
				// Check if folder is in favourites and then add/remove from favourites
				if (
					this.favourites &&
					!this.favourites.find(el => el.id === this.item.id)
				) {
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
			downloadItem() {
				if (this.clipboard.length > 1 || (this.clipboard.length === 1 && this.clipboard[0].type === 'folder'))
					this.$store.dispatch('downloadZip')
				else {
					this.$downloadFile(this.item.file_url, this.item.name + '.' + this.item.mimetype)
				}
			},
		},
		created() {
			this.$store.dispatch('getFolder', this.$route.params.id)

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('mobile-context-menu:show', item => this.item = item)
		}
	}
</script>
