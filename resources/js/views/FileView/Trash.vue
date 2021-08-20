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

		<!--Show files & folders-->
		<FileBrowser>
			<template v-slot:file-actions-mobile>
				<!-- todo: Implement mobile buttons-->
			</template>

			<template v-slot:empty-file-page>
				<h1 class="title">{{ $t('empty_page.title') }}</h1>
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
		name: 'Trash',
		components: {
			OptionGroup,
			FileBrowser,
			ContextMenu,
			Option,
		},
		computed: {
			...mapGetters([
				'clipboard',
			]),
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

			events.$on('contextMenu:show', (event, item) => this.item = item)
		}
	}
</script>
