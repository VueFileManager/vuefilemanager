<template>
    <div id="desktop-toolbar">
        <div class="toolbar-wrapper">
			<div @click="goBack" class="location">
				<chevron-left-icon :class="{'is-active': isLoadedFolder }" class="icon-back" size="17" />

				<span class="location-title">
					{{ directoryName }}
				</span>

				<span v-if="isLoadedFolder" @click.stop="folderActions" class="location-more group" id="folder-actions">
					<more-horizontal-icon size="14" class="icon-more group-hover-text-theme" />
				</span>
			</div>

			<ToolbarWrapper>

				<!--Search bar-->
				<ToolbarGroup v-if="false" style="margin-left: 0">
					<SearchBar />
				</ToolbarGroup>

				<!--Creating controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor'])">
					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showCreateMenu" source="cloud-plus" :action="$t('actions.create')" />
						<PopoverItem name="desktop-create" side="left">
							<OptionGroup>
								<OptionUpload :class="{'is-inactive': canUploadInView || !hasCapacity }" :title="$t('actions.upload')" />
							</OptionGroup>
							<OptionGroup>
								<Option @click.stop.native="$createTeamFolder" :title="$t('Create Team Folder')" icon="users" />
								<Option @click.stop.native="$createFolder" :class="{'is-inactive': canCreateFolderInView }" :title="$t('actions.create_folder')" icon="folder-plus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
				</ToolbarGroup>

				<!--Share Controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isMobile() && !$isThisRoute($route, ['Public'])">

					<!--Team Folder Icon-->
					<PopoverWrapper>
						<TeamMembersPreview @click.stop.native="showTeamFolderMenu" count="3+" :members="members" class="team-preview" />
						<PopoverItem name="team-folder" side="left">
							<TeamFolderPreview />
							<OptionGroup>
								<Option @click.native="$updateTeamFolder(clipboard[0])" :title="$t('Edit Members')" icon="rename" />
								<Option @click.native="$dissolveTeamFolder(clipboard[0])" :title="$t('Dissolve Team')" icon="trash" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>

					<ToolbarButton v-if="false" @click.native="$createTeamFolder" source="user-plus" :action="$t('actions.convert_into_team_folder')" />
					<ToolbarButton @click.native="$shareFileOrFolder(clipboard[0])" :class="{'is-inactive': canShareInView }" source="share" :action="$t('actions.share')" />
				</ToolbarGroup>

				<!--File Controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isMobile()">
					<ToolbarButton @click.native="$moveFileOrFolder(clipboard[0])" :class="{'is-inactive': canMoveInView }" source="move" :action="$t('actions.move')" />
                    <ToolbarButton @click.native="$deleteFileOrFolder(clipboard[0])" :class="{'is-inactive': canDeleteInView }" source="trash" :action="$t('actions.delete')" />
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
	import UploadProgress from '/resources/js/components/FilesView/UploadProgress'
	import PopoverWrapper from '/resources/js/components/Desktop/PopoverWrapper'
	import ToolbarWrapper from '/resources/js/components/Desktop/ToolbarWrapper'
	import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
	import OptionUpload from '/resources/js/components/FilesView/OptionUpload'
	import ToolbarGroup from '/resources/js/components/Desktop/ToolbarGroup'
	import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
	import TeamMembersPreview from "../Teams/Components/TeamMembersPreview"
	import PopoverItem from '/resources/js/components/Desktop/PopoverItem'
	import TeamFolderPreview from "../Teams/Components/TeamFolderPreview"
	import {ChevronLeftIcon, MoreHorizontalIcon} from 'vue-feather-icons'
	import SearchBar from '/resources/js/components/FilesView/SearchBar'
	import Option from '/resources/js/components/FilesView/Option'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import {last} from 'lodash'

	export default {
		name: 'DesktopToolbar',
		components: {
			TeamMembersPreview,
			FileSortingOptions,
			MoreHorizontalIcon,
			TeamFolderPreview,
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
				'clipboard',
			]),
			isLoadedFolder() {
				return this.$route.params.id
			},
			hasCapacity() {
				// Check if storage limitation is set
				if (!this.$store.getters.config.storageLimit) return true

				// Check if user is loaded
				if (!this.$store.getters.user) return true

				// Check if user has storage
				return this.$store.getters.user.data.attributes.storage.used <= 100
			},
			directoryName() {
				if (this.currentFolder) {
					return this.currentFolder.name
				} else {
					return {
						'RecentUploads': this.$t('Recent'),
						'MySharedItems': this.$t('Shared'),
						'Trash': this.$t('Trash'),
						'Public': this.$t('Files'),
						'Files': this.$t('Files'),
					}[this.$route.name]
				}
			},
			preview() {
				return this.FilePreviewType === 'list'
					? 'th'
					: 'th-list'
			},
			canCreateFolderInView() {
				return ! this.$isThisRoute(this.$route, ['Files', 'Public'])
			},
			canDeleteInView() {
				let routes = [
					'RecentUploads',
					'MySharedItems',
					'Trash',
					'Public',
					'Files',
				]
				return !this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
			},
			canUploadInView() {
				return ! this.$isThisRoute(this.$route, ['Files', 'Public'])
			},
			canMoveInView() {
				let routes = [
					'RecentUploads',
					'MySharedItems',
					'Public',
					'Files',
				]
				return ! this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
			},
			canShareInView() {
				let routes = [
					'RecentUploads',
					'MySharedItems',
					'Public',
					'Files',
				]
				return ! this.$isThisRoute(this.$route, routes) || this.clipboard.length > 1 || this.clipboard.length === 0
			},
			canCreateTeamFolderInView() {
				let routes = [
					'MySharedItems',
					'Files',
				]
				return this.$isThisRoute(this.$route, routes) && this.clipboard.length === 1 && this.clipboard[0].type === 'folder'
			}
		},
		data() {
			return {
				members: [
					'/temp/avatar-01.png',
					'/temp/avatar-02.png',
					'/temp/avatar-03.png',
				],
			}
		},
		methods: {
			goBack() {
				if (this.isLoadedFolder) this.$router.back()
			},
			showTeamFolderMenu() {
				events.$emit('popover:open', 'team-folder')
			},
			showCreateMenu() {
				events.$emit('popover:open', 'desktop-create')
			},
			showSortingMenu() {
				events.$emit('popover:open', 'desktop-sorting')
			},
			folderActions() {
				// todo: add current folder
				events.$emit('folder:actions', this.currentFolder)
			},
		},
	}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

.team-preview {
	padding: 3px 3px 3px 10px;
	border-radius: 8px;
	cursor: pointer;

	&:hover {
		background: $light_background;

		/deep/ .members .member {
			border-color: $light_background;
		}
	}
}

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
	.team-preview:hover {
		background: $dark_mode_foreground;

		/deep/ .members .member {
			border-color: $dark_mode_foreground;
		}
	}

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
