<template>
	<div>
		<ContextMenu>
			<template v-slot:empty-select>
				<OptionGroup>
					<Option @click.native="$emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
				</OptionGroup>
			</template>

			<template v-slot:single-select v-if="item">
				<OptionGroup>
					<Option @click.native="$restoreFileOrFolder(item)" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
					<Option @click.native="$deleteFileOrFolder(item)" v-if="item" :title="$t('context_menu.delete')" icon="trash" />
					<Option @click.native="$emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>

			<template v-slot:multiple-select v-if="item">
				<OptionGroup>
					<Option @click.native="$restoreFileOrFolder(item)" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
					<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
					<Option @click.native="$emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
				</OptionGroup>
				<OptionGroup>
					<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
				</OptionGroup>
			</template>
		</ContextMenu>

		<MobileContextMenu>
			<OptionGroup v-if="item">
				<Option @click.native="$restoreFileOrFolder(item)" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
				<Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
		</MobileContextMenu>

		<!--Show files & folders-->
		<FileBrowser>
			<template v-slot:file-actions-mobile>
				<MobileActionButton @click.native="$openSpotlight" icon="search">
					{{ $t('actions.search')}}
				</MobileActionButton>
				<MobileActionButton @click.native="$showLocations" icon="filter">
					{{ filterLocationTitle }}
				</MobileActionButton>
				<MobileActionButton @click.native="$emptyTrash" icon="trash">
					{{ $t('context_menu.empty_trash') }}
				</MobileActionButton>
				 <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
					{{ $t('context_menu.select') }}
				</MobileActionButton>
				 <MobileActionButton @click.native="$showViewOptions" icon="preview-sorting">
					{{ $t('preview_sorting.preview_sorting_button') }}
				</MobileActionButton>
			</template>

			<template v-slot:empty-file-page>
				<h1 class="title">{{ $t('empty_page.title') }}</h1>
			</template>
		</FileBrowser>
	</div>
</template>

<script>
    import MobileActionButtonUpload from '/resources/js/components/FilesView/MobileActionButtonUpload'
	import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
	import MobileContextMenu from "/resources/js/components/FilesView/MobileContextMenu"
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../../bus";

	export default {
		name: 'Trash',
		components: {
			MobileActionButtonUpload,
			MobileActionButton,
			MobileContextMenu,
			OptionGroup,
			FileBrowser,
			ContextMenu,
			Option,
		},
		computed: {
			...mapGetters([
				'clipboard',
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
			this.$store.dispatch('getTrash', this.$route.params.id)

			events.$on('context-menu:show', (event, item) => this.item = item)
			events.$on('mobile-context-menu:show', item => this.item = item)
		}
	}
</script>
