<template>
    <div id="desktop-toolbar">
        <div class="toolbar-wrapper">

			<div v-if="homeDirectory" @click="goBack" class="location">
				<chevron-left-icon :class="{'is-active': browseHistory.length > 1 }" class="icon-back" size="17" />

				<span class="location-title">
					{{ directoryName }}
				</span>

				<span @click.stop="folderActions" v-if="browseHistory.length > 1 && $isThisLocation(['base', 'public'])" class="location-more group" id="folder-actions">
					<more-horizontal-icon size="14" class="icon-more group-hover-text-theme" />
				</span>
			</div>

			<ToolbarWrapper>

				<!--Search bar-->
				<ToolbarGroup style="margin-left: 0">
					<SearchBar />
				</ToolbarGroup>

				<!--Creating controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor'])">
					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showCreateMenu" source="cloud-plus" :action="$t('actions.create')" />
						<PopoverItem name="desktop-create" side="left">
							<OptionGroup>
								<OptionUpload :class="{'is-inactive': canUploadInView || !hasCapacity }" :title="$t('actions.upload')" />
								<Option @click.stop.native="createFolder" :class="{'is-inactive': canCreateFolderInView }" :title="$t('actions.create_folder')" icon="folder-plus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
				</ToolbarGroup>

				<!--Share Controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isMobile() && !$isThisLocation(['public'])">
                    <ToolbarButton @click.native="shareItem" :class="{'is-inactive': ! canCreateTeamFolderInView }" source="user-plus" :action="$t('actions.convert_into_team_folder')" />
                    <ToolbarButton @click.native="shareItem" :class="{'is-inactive': canShareInView }" source="share" :action="$t('actions.share')" />
				</ToolbarGroup>

				<!--File Controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isMobile()">
					<ToolbarButton @click.native="moveItem" :class="{'is-inactive': canMoveInView }" source="move" :action="$t('actions.move')" />
                    <ToolbarButton @click.native="deleteItem" :class="{'is-inactive': canDeleteInView }" source="trash" :action="$t('actions.delete')" />
				</ToolbarGroup>

				<!--View Controls-->
				<ToolbarGroup>
					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showSortingMenu" source="preview-sorting" :action="$t('actions.sorting_view')" />
						<PopoverItem name="desktop-sorting" side="left">
							<FileSortingOptions />
						</PopoverItem>
					</PopoverWrapper>
                    <ToolbarButton @click.native="$store.dispatch('fileInfoToggle')" :class="{'active': isVisibleSidebar }" :action="$t('actions.info_panel')" source="info" />
				</ToolbarGroup>
			</ToolbarWrapper>
        </div>

		<UploadProgress />
    </div>
</template>

<script>
	import FileSortingOptions from '/resources/js/components/FilesView/FileSortingOptions'
	import {ChevronLeftIcon, MoreHorizontalIcon} from 'vue-feather-icons'
	import UploadProgress from '/resources/js/components/FilesView/UploadProgress'
	import PopoverWrapper from '/resources/js/components/Desktop/PopoverWrapper'
	import ToolbarWrapper from '/resources/js/components/Desktop/ToolbarWrapper'
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
	import OptionUpload from '/resources/js/components/FilesView/OptionUpload'
	import ToolbarGroup from '/resources/js/components/Desktop/ToolbarGroup'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import PopoverItem from '/resources/js/components/Desktop/PopoverItem'
	import SearchBar from '/resources/js/components/FilesView/SearchBar'
	import Option from '/resources/js/components/FilesView/Option'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import {last} from 'lodash'

	export default {
		name: 'ToolBar',
		components: {
			FileSortingOptions,
			MoreHorizontalIcon,
			ChevronLeftIcon,
			ToolbarWrapper,
			UploadProgress,
			PopoverWrapper,
			ToolbarButton,
			OptionUpload,
			ToolbarGroup,
			OptionGroup,
			PopoverItem,
			SearchBar,
			Option,
		},
		computed: {
			...mapGetters([
				'isVisibleSidebar',
				'FilePreviewType',
				'currentFolder',
				'browseHistory',
				'homeDirectory',
				'clipboard',
			]),
			hasCapacity() {
				// Check if storage limitation is set
				if (!this.$store.getters.config.storageLimit) return true

				// Check if user is loaded
				if (!this.$store.getters.user) return true

				// Check if user has storage
				return this.$store.getters.user.data.attributes.storage.used <= 100
			},
			directoryName() {
				return this.currentFolder
					? this.currentFolder.name
					: this.homeDirectory.name
			},
			preview() {
				return this.FilePreviewType === 'list'
					? 'th'
					: 'th-list'
			},
			canCreateFolderInView() {
				return !this.$isThisLocation(['base', 'public'])
			},
			canDeleteInView() {
				let locations = [
					'trash-root',
					'latest',
					'shared',
					'public',
					'trash',
					'base',
				]
				return !this.$isThisLocation(locations) || this.clipboard.length === 0
			},
			canUploadInView() {
				return !this.$isThisLocation(['base', 'public'])
			},
			canMoveInView() {
				let locations = [
					'latest',
					'shared',
					'public',
					'base',
				]
				return !this.$isThisLocation(locations) || this.clipboard.length === 0
			},
			canShareInView() {
				let locations = [
					'latest',
					'shared',
					'public',
					'base',
				]
				return !this.$isThisLocation(locations) || this.clipboard.length > 1 || this.clipboard.length === 0
			},
			canCreateTeamFolderInView() {
				let locations = [
					'shared',
					'base',
				]
				return this.$isThisLocation(locations) && this.clipboard.length === 1 && this.clipboard[0].type === 'folder'
			}
		},
		methods: {
			showCreateMenu() {
				events.$emit('popover:open', 'desktop-create')
			},
			showSortingMenu() {
				events.$emit('popover:open', 'desktop-sorting')
			},
			goBack() {
				let previousFolder = last(this.browseHistory)

				if (!previousFolder) return

				if (previousFolder.location === 'trash-root') {
					this.$store.dispatch('getTrash')

				} else if (previousFolder.location === 'shared') {
					this.$store.dispatch('getShared')

				} else {
					if (this.$isThisLocation('public')) {
						this.$store.dispatch('browseShared', [
							{folder: previousFolder, back: true, init: false}
						])
					} else {
						this.$store.dispatch('getFolder', [
							{folder: previousFolder, back: true, init: false}
						])
					}
				}
			},
			folderActions() {
				events.$emit('folder:actions', this.currentFolder)
			},
			deleteItem() {
				if (this.clipboard.length > 0)
					this.$store.dispatch('deleteItem')
			},
			createFolder() {
				this.$store.dispatch('createFolder', {name: this.$t('popup_create_folder.folder_default_name')})
				events.$emit('popover:close', 'desktop-create')
			},
			moveItem() {
				if (this.clipboard.length > 0)
					events.$emit('popup:open', {name: 'move', item: this.clipboard})
			},
			shareItem() {
				let event = this.clipboard[0].shared
					? 'share-edit'
					: 'share-create'

				events.$emit('popup:open', {
					name: event,
					item: this.clipboard[0]
				})
			}
		},
	}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

.is-inactive {
	opacity: 0.25;
	pointer-events: none;
}

.toolbar-wrapper {
	padding-top: 10px;
	padding-bottom: 10px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	position: relative;
	z-index: 2;
}

.location {
	align-items: center;
	cursor: pointer;
	display: flex;

	.icon-back {
		@include transition(150ms);
		pointer-events: none;
		margin-right: 6px;
		flex-shrink: 0;
		opacity: 0.15;

		&.is-active {
			opacity: 1;
			pointer-events: initial;
		}
	}

	.location-title {
		@include font-size(15);
		line-height: 1;
		font-weight: 700;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		color: $text;
	}

	.location-more {
		margin-left: 6px;
		padding: 1px 4px;
		line-height: 0;
		border-radius: 3px;
		@include transition(150ms);

		svg circle {
			@include transition(150ms);
		}

		&:hover {
			background: $light_background;

			svg circle {
				color: inherit;
			}
		}
	}
}

.toolbar-position {
	text-align: center;

	span {
		@include font-size(17);
		font-weight: 600;
	}
}

@media only screen and (max-width: 1024px) {
	.location {

		.location-title {
			max-width: 120px;
		}
	}

	.toolbar-tools {
		.button {
			margin-left: 0;
			height: 40px;
			width: 40px;
		}
	}
}

@media only screen and (max-width: 960px) {
	#desktop-toolbar {
		display: none;
	}
}

.dark-mode {
	.toolbar .directory-name {
		color: $dark_mode_text_primary;
	}

	.location {
		.location-title {
			color: $dark_mode_text_primary;
		}

		.location-more {
			&:hover {
				background: $dark_mode_foreground;
			}
		}
	}
}
</style>
