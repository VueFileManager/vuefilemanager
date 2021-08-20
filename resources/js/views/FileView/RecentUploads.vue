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
				<!-- todo: Implement mobile buttons-->
			</template>
		</FileBrowser>
	</div>
</template>

<script>
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import Option from '/resources/js/components/FilesView/Option'
	import { mapGetters } from 'vuex'
	import {events} from "../../bus";

	export default {
		name: 'RecentUploads',
		components: {
			OptionGroup,
			FileBrowser,
			ContextMenu,
			Option,
		},
		computed: {
			...mapGetters([
				'clipboard',
				'user',
			])
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
