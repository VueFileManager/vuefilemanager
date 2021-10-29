<template>
    <div id="desktop-toolbar" class="md:block hidden">
        <div class="toolbar-wrapper">
			<div @click="goBack" class="location">
				<chevron-left-icon :class="{'is-active': isNotHomepage }" class="icon-back" size="17" />

				<span class="location-title">
					{{ $getCurrentLocationName() }}
				</span>

				<span v-show="currentFolder" @click.stop="folderActions" class="location-more group" id="folder-actions">
					<more-horizontal-icon size="14" class="icon-more group-hover-text-theme" />
				</span>
			</div>

			<ToolbarWrapper>

				<!--Search bar-->
				<ToolbarGroup class="ml-0">
					<SearchBar class="lg:block hidden" />
				</ToolbarGroup>

				<!--Create button for all pages except SharedWithMe-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isThisRoute($route, ['SharedWithMe'])">

					<span class="lg:hidden block">
						<ToolbarButton @click.stop.native="$openSpotlight" source="search" :action="$t('Search files or folders')" />
					</span>

					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showCreateMenu" source="cloud-plus" :action="$t('actions.create')" />

						<PopoverItem name="desktop-create" side="left">
							<OptionGroup>
								<OptionUpload :class="{'is-inactive': canUploadInView || isTeamFolderHomepage }" :title="$t('actions.upload')" />
							</OptionGroup>
							<OptionGroup>
								<Option @click.stop.native="$createTeamFolder" :title="$t('Create Team Folder')" icon="users" />
								<Option @click.stop.native="$createFolder" :class="{'is-inactive': canCreateFolderInView || isTeamFolderHomepage }" :title="$t('actions.create_folder')" icon="folder-plus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
				</ToolbarGroup>

				<!--Create button for shared with me page-->
				<ToolbarGroup v-if="$isThisRoute($route, ['SharedWithMe'])">
					<span class="lg:hidden block">
						<ToolbarButton @click.stop.native="$openSpotlight" source="search" :action="$t('Search files or folders')" />
					</span>

					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showCreateMenu" source="cloud-plus" :class="{'is-inactive': ! canEdit}" :action="$t('actions.create')" />

						<PopoverItem name="desktop-create" side="left">
							<OptionGroup>
								<OptionUpload :class="{'is-inactive': canUploadInView || isSharedWithMeHomepage }" :title="$t('actions.upload')" />
							</OptionGroup>
							<OptionGroup>
								<Option @click.stop.native="$createFolder" :class="{'is-inactive': canCreateFolderInView || isSharedWithMeHomepage }" :title="$t('actions.create_folder')" icon="folder-plus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
				</ToolbarGroup>

				<!--File Controls-->
				<ToolbarGroup v-if="$checkPermission(['master', 'editor']) && ! $isMobile()">

					<!--Team Heads-->
					<PopoverWrapper v-if="$isThisRoute($route, ['TeamFolders', 'SharedWithMe'])">
						<TeamMembersButton @click.stop.native="showTeamFolderMenu" size="32" class="dark:hover:bg-dark-foreground hover:bg-light-background rounded-lg cursor-pointer py-0.5 pl-2 pr-0.5" />

						<PopoverItem name="team-folder" side="left">
							<TeamFolderPreview />

							<OptionGroup v-if="$isThisRoute($route, ['TeamFolders'])">
								<Option @click.native="$updateTeamFolder(teamFolder)" :title="$t('Edit Members')" icon="rename" />
								<Option @click.native="$dissolveTeamFolder(teamFolder)" :title="$t('Dissolve Team')" icon="trash" />
							</OptionGroup>

							<OptionGroup v-if="$isThisRoute($route, ['SharedWithMe'])">
								<Option @click.native="$detachMeFromTeamFolder(teamFolder)" :title="$t('Leave the Team Folder')" icon="user-minus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>

					<!--Share icons-->
					<ToolbarButton v-if="canShowConvertToTeamFolder" @click.native="$convertAsTeamFolder(clipboard[0])" :class="{'is-inactive': ! canCreateTeamFolderInView }" source="user-plus" :action="$t('actions.convert_into_team_folder')" />
					<ToolbarButton v-if="! $isThisRoute($route, ['SharedWithMe', 'Public'])" @click.native="$shareFileOrFolder(clipboard[0])" :class="{'is-inactive': canShareInView }" source="share" :action="$t('actions.share')" />

					<ToolbarButton @click.native="$moveFileOrFolder(clipboard[0])" :class="{'is-inactive': canMoveInView && ! canEdit }" source="move" :action="$t('actions.move')" />
                    <ToolbarButton @click.native="$deleteFileOrFolder(clipboard[0])" :class="{'is-inactive': canDeleteInView && ! canEdit }" source="trash" :action="$t('actions.delete')" />
				</ToolbarGroup>

				<!--View Controls-->
				<ToolbarGroup>
					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showSortingMenu" source="preview-sorting" :action="$t('actions.sorting_view')" />
						<PopoverItem name="desktop-sorting" side="left">
							<FileSortingOptions />
						</PopoverItem>
					</PopoverWrapper>
                    <ToolbarButton @click.native="$store.dispatch('fileInfoToggle')" :action="$t('actions.info_panel')" source="info" />
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
	import TeamMembersButton from "../Teams/Components/TeamMembersButton"
	import PopoverItem from '/resources/js/components/Desktop/PopoverItem'
	import TeamFolderPreview from "../Teams/Components/TeamFolderPreview"
	import {ChevronLeftIcon, MoreHorizontalIcon} from 'vue-feather-icons'
	import SearchBar from '/resources/js/components/FilesView/SearchBar'
	import Option from '/resources/js/components/FilesView/Option'
	import {events} from '/resources/js/bus'
	import {mapGetters} from 'vuex'

	export default {
		name: 'DesktopToolbar',
		components: {
			TeamMembersButton,
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
				'currentTeamFolder',
				'currentFolder',
				'sharedDetail',
				'clipboard',
				'user',
			]),
			canEdit() {
				if (this.currentTeamFolder && this.user && this.clipboard[0]) {
					let member = this.currentTeamFolder.data.relationships.members.data.find(member => member.data.id === this.user.data.id)

					return member.data.attributes.permission === 'can-edit'
				}

				return false
			},
			teamFolder() {
				return this.currentTeamFolder
					? this.currentTeamFolder
					: this.clipboard[0]
			},
			isNotHomepage() {
				if (this.$isThisRoute(this.$route, ['Public'])) {
					return this.sharedDetail && this.sharedDetail.data.attributes.item_id === this.$route.params.id
				}

				return this.$route.params.id
			},
			isTeamFolderHomepage() {
				return this.$isThisRoute(this.$route, ['TeamFolders'])
					&& ! this.$route.params.id
			},
			isSharedWithMeHomepage() {
				return this.$isThisRoute(this.$route, ['SharedWithMe'])
					&& ! this.$route.params.id
			},
			canCreateFolderInView() {
				return ! this.$isThisRoute(this.$route, ['Files', 'Public', 'TeamFolders', 'SharedWithMe'])
			},
			canShowConvertToTeamFolder() {
				return this.$isThisRoute(this.$route, ['Files', 'MySharedItems'])
			},
			canUploadInView() {
				return ! this.$isThisRoute(this.$route, ['Files', 'Public', 'TeamFolders', 'SharedWithMe'])
			},
			canDeleteInView() {
				let routes = [
					'TeamFolders',
					'SharedWithMe',
					'RecentUploads',
					'MySharedItems',
					'Trash',
					'Public',
					'Files',
				]
				return !this.$isThisRoute(this.$route, routes)
					|| this.clipboard.length === 0
			},
			canMoveInView() {
				let routes = [
					'SharedWithMe',
					'RecentUploads',
					'MySharedItems',
					'Public',
					'Files',
					'TeamFolders',
				]
				return ! this.$isThisRoute(this.$route, routes)
					|| this.clipboard.length === 0
			},
			canShareInView() {
				let routes = [
					'TeamFolders',
					'RecentUploads',
					'MySharedItems',
					'Public',
					'Files',
				]
				return ! this.$isThisRoute(this.$route, routes)
					|| this.clipboard.length > 1
					|| this.clipboard.length === 0
			},
			canCreateTeamFolderInView() {
				let routes = [
					'MySharedItems',
					'Files',
				]

				return this.$isThisRoute(this.$route, routes)
					&& this.clipboard.length === 1
					&& this.clipboard[0].data.type === 'folder'
			}
		},
		methods: {
			goBack() {
				if (this.isNotHomepage) this.$router.back()
			},
			showTeamFolderMenu() {
				if (this.teamFolder)
					events.$emit('popover:open', 'team-folder')
			},
			showCreateMenu() {
				events.$emit('popover:open', 'desktop-create')
			},
			showSortingMenu() {
				events.$emit('popover:open', 'desktop-sorting')
			},
			folderActions() {
				events.$emit('context-menu:current-folder', this.currentFolder)
			},
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

.dark {

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
