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
		name: 'Files',
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

			events.$on('contextMenu:show', (event, item) => this.item = item)
		}
	}
</script>
