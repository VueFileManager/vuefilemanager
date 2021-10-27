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
					<Option @click.native="$shareFileOrFolder(item)" :title="item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
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

		<FileActionsMobile>
			<MobileActionButton @click.native="$openSpotlight" icon="search">
				{{ $t('actions.search')}}
			</MobileActionButton>
			<MobileActionButton @click.native="$showLocations" icon="filter">
				{{ $getCurrentSectionName() }}
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

		<MobileMultiSelectToolbar>
			<ToolbarButton @click.native="$deleteFileOrFolder(clipboard)" class="action-btn" source="trash" :class="{'is-inactive' : clipboard.length < 1}" :action="$t('actions.delete')" />
			<ToolbarButton @click.native="$downloadSelection(item)" class="action-btn" source="download" :action="$t('actions.download')" />
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
	import ButtonUpload from '/resources/js/components/FilesView/ButtonUpload'
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
			MobileMultiSelectToolbar,
			MobileActionButton,
			MobileContextMenu,
			ToolbarButton,
			ButtonUpload,
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
