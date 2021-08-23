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
					<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>

			<template v-slot:multiple-select v-if="item">
				<OptionGroup>
					<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<MobileContextMenu>
			<OptionGroup>
				<Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
				<Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
				<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
				<Option @click.native="$shareFileOrFolder(item)" :title="item && item.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
			</OptionGroup>
			<OptionGroup>
				<Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
			</OptionGroup>
		</MobileContextMenu>

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

		<MultiSelectToolbar>
			<ToolbarButton @click.native="$deleteFileOrFolder(clipboard)" class="action-btn" source="trash" :class="{'is-inactive' : clipboard.length < 1}" :action="$t('actions.delete')" />
			<ToolbarButton @click.native="$downloadSelection(item)" class="action-btn" source="download" :action="$t('actions.download')" />
		</MultiSelectToolbar>
	</div>
</template>

<script>
    import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
	import MultiSelectToolbar from "/resources/js/components/FilesView/MultiSelectToolbar"
	import MobileContextMenu from "/resources/js/components/FilesView/MobileContextMenu"
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../bus";

	export default {
		name: 'RecentUploads',
		components: {
			MobileActionButtonUpload,
			MultiSelectToolbar,
			MobileActionButton,
			MobileContextMenu,
			ToolbarButton,
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
		created() {
			this.$store.dispatch('getRecentUploads')

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('mobile-context-menu:show', item => this.item = item)
		}
	}
</script>
