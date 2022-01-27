<template>
	<div>
		<ContextMenu>
			<template v-slot:single-select v-if="item">
				<OptionGroup v-if="isFolder">
					<Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$shareFileOrFolder(item)" :title="item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
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
					<Option @click.native="$shareCancel" :title="$t('context_menu.share_cancel')" icon="share" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$downloadSelection()" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<MobileContextMenu>
			<OptionGroup v-if="isFolder">
				<Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
				<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.native="$shareFileOrFolder(item)" :title="item && item.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
			</OptionGroup>
		</MobileContextMenu>

		<FileActionsMobile>
			<MobileActionButton @click.native="$openSpotlight()" icon="search">
				{{ $t('Spotlight')}}
			</MobileActionButton>
			<MobileActionButton @click.native="$showMobileMenu('file-filter')" :icon="$getCurrentSectionIcon()">
				{{ $getCurrentSectionName() }}
			</MobileActionButton>
			<MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
				{{ $t('context_menu.select') }}
			</MobileActionButton>
			<MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
				{{ $t('preview_sorting.preview_sorting_button') }}
			</MobileActionButton>
		</FileActionsMobile>

		<EmptyFilePage>
			<h1 class="title">{{ $t('shared.empty_shared') }}</h1>
		</EmptyFilePage>

		<FileBrowser />

		<MobileMultiSelectToolbar>
			<ToolbarButton @click.native="$downloadSelection(item)" class="action-btn" source="download" :action="$t('actions.download')" />
			<ToolbarButton @click.native="$shareCancel" class="action-btn" source="shared-off" />
		</MobileMultiSelectToolbar>
	</div>
</template>

<script>
	import EmptyFilePage from "../../components/FilesView/EmptyFilePage";
	import FileActionsMobile from "../../components/FilesView/FileActionsMobile";
    import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
	import MobileMultiSelectToolbar from "/resources/js/components/FilesView/MobileMultiSelectToolbar"
	import MobileContextMenu from "/resources/js/components/FilesView/MobileContextMenu"
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../bus";

	export default {
		name: 'MySharedItems',
		components: {
			MobileActionButtonUpload,
			MobileActionButton,
			MobileMultiSelectToolbar,
			MobileContextMenu,
			ToolbarButton,
			OptionGroup,
			FileBrowser,
			ContextMenu,
			Option,
			FileActionsMobile,
			EmptyFilePage,
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
				return this.favourites.find(el => el.id === this.item.id)
			},
			hasFile() {
				return this.clipboard.find(item => item.type !== 'folder')
			},
			favourites() {
				return this.user.data.relationships.favourites.data.attributes.folders
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
		},
		created() {
			this.$store.dispatch('getMySharedItems')

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('mobile-context-menu:show', item => this.item = item)
		}
	}
</script>
