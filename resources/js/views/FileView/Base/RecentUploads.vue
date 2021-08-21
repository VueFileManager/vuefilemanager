<template>
	<div>
		<ContextMenu>
			<template v-slot:single-select v-if="item">
				<OptionGroup>
					<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$shareFileOrFolder(item)" :title="item.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>

			<template v-slot:multiple-select v-if="item">
				<OptionGroup>
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<!--Show files & folders-->
		<FileBrowser>
			<template v-slot:file-actions-mobile>
				<MobileActionButton @click.native="$openSpotlight" icon="search">
					{{ $t('actions.search')}}
				</MobileActionButton>
				<MobileActionButton @click.native="$showLocations" icon="filter">
					{{ filterLocationTitle }}
				</MobileActionButton>
				<MobileActionButtonUpload>
					{{ $t('context_menu.upload') }}
				</MobileActionButtonUpload>
				<MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
					{{ $t('context_menu.select') }}
				</MobileActionButton>
				<MobileActionButton @click.native="$showViewOptions" icon="preview-sorting">
					{{ $t('preview_sorting.preview_sorting_button') }}
				</MobileActionButton>
			</template>
		</FileBrowser>
	</div>
</template>

<script>
    import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../../bus";

	export default {
		name: 'RecentUploads',
		components: {
			MobileActionButtonUpload,
			MobileActionButton,
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
			filterLocationTitle() {
				return {
					'RecentUploads': this.$t('Recent'),
					'MySharedItems': this.$t('Shared'),
					'Trash': this.$t('Trash'),
					'Public': this.$t('Files'),
					'Files': this.$t('Files'),
				}[this.$route.name]
			},
		},
		data() {
			return {
				item: undefined,
			}
		},
		methods: {
			downloadItem() {
				if (this.clipboard.length > 1 || (this.clipboard.length === 1 && this.clipboard[0].type === 'folder'))
					this.$store.dispatch('downloadZip')
				else {
					this.$downloadFile(this.item.file_url, this.item.name + '.' + this.item.mimetype)
				}
			},
		},
		created() {
			this.$store.dispatch('getRecentUploads')

			events.$on('contextMenu:show', (event, item) => this.item = item)
		}
	}
</script>
