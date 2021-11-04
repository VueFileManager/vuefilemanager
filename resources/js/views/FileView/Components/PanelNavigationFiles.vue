<template>
	<ContentSidebar>
		<!--Empty storage warning-->
		<ContentGroup v-if="user && config.storageLimit && storage.used > 95">
			<UpgradeSidebarBanner/>
		</ContentGroup>

		<!--Locations-->
		<ContentGroup :title="$t('sidebar.locations_title')">
			<div class="menu-list-wrapper vertical">
				<router-link @click.native="resetData" :to="{name: 'Files'}" class="menu-list-item link">
					<div class="icon text-theme">
						<home-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('sidebar.home') }}
					</div>
				</router-link>
				<router-link @click.native="resetData" :to="{name: 'RecentUploads'}" class="menu-list-item link">
					<div class="icon text-theme">
						<upload-cloud-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('sidebar.latest') }}
					</div>
				</router-link>
				<router-link @click.native="resetData" :to="{name: 'MySharedItems'}" class="menu-list-item link">
					<div class="icon text-theme">
						<link-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('sidebar.my_shared') }}
					</div>
				</router-link>
				<router-link @click.native="resetData" :to="{name: 'Trash'}" class="menu-list-item link">
					<div class="icon text-theme">
						<trash2-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('locations.trash') }}
					</div>
				</router-link>
			</div>
		</ContentGroup>

		<!--Locations-->
		<ContentGroup :title="$t('Collaboration')" slug="collaboration" :can-collapse="true">
			<div class="menu-list-wrapper vertical">
				<router-link @click.native="resetData" :to="{name: 'TeamFolders'}" class="menu-list-item link">
					<div class="icon text-theme">
						<users-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('Team Folders') }}
					</div>
				</router-link>
				<router-link @click.native="resetData" :to="{name: 'SharedWithMe'}" class="menu-list-item link">
					<div class="icon text-theme">
						<user-check-icon size="17" />
					</div>
					<div class="label text-theme">
						{{ $t('Shared with Me') }}
					</div>
				</router-link>
			</div>
		</ContentGroup>

		<!--Navigator-->
		<ContentGroup v-if="user" :title="$t('sidebar.navigator_title')" slug="navigator" :can-collapse="true" class="navigator">
			<span v-if="tree.length === 0" class="empty-note navigator">
				{{ $t('sidebar.folders_empty') }}
			</span>
			<TreeMenuNavigator class="folder-tree" :depth="0" :nodes="folder" v-for="folder in tree" :key="folder.id"/>
		</ContentGroup>

		<!--Favourites-->
		<ContentGroup v-if="user" :title="$t('sidebar.favourites')" slug="favourites" :can-collapse="true">

			<div @dragover.prevent="dragEnter" @dragleave="dragLeave" @drop="dragFinish($event)" :class="{ 'is-dragenter': area }" class="menu-list-wrapper vertical favourites">
				<transition-group tag="div" class="menu-list" name="folder">
					<span v-if="favourites.length === 0" class="empty-note favourites" :key="0">
						{{ $t('sidebar.favourites_empty') }}
					</span>

					<div @click="goToFolder(folder)" v-for="folder in favourites" :key="folder.data.id" class="menu-list-item folder-item">
						<div class="text-theme flex">
							<folder-icon size="17" class="folder-icon" />
							<span class="label text-theme">{{ folder.data.attributes.name }}</span>
						</div>
						<x-icon @click.stop="$removeFavourite(folder)" size="17" class="delete-icon" />
					</div>
				</transition-group>
			</div>
		</ContentGroup>
	</ContentSidebar>
</template>

<script>
	import {FolderIcon, HomeIcon, LinkIcon, Trash2Icon, UploadCloudIcon, UserCheckIcon, UsersIcon, XIcon} from "vue-feather-icons";
	import UpgradeSidebarBanner from '/resources/js/components/Others/UpgradeSidebarBanner'
	import TreeMenuNavigator from '/resources/js/components/Others/TreeMenuNavigator'
	import ContentSidebar from '/resources/js/components/Sidebar/ContentSidebar'
	import ContentGroup from '/resources/js/components/Sidebar/ContentGroup'
	import {events} from "../../../bus";
	import {mapGetters} from "vuex";

export default {
	name: "PanelNavigationFiles",
	components: {
		UpgradeSidebarBanner,
		TreeMenuNavigator,
		ContentSidebar,
		ContentGroup,
		UploadCloudIcon,
		UserCheckIcon,
		FolderIcon,
		Trash2Icon,
		UsersIcon,
		HomeIcon,
		LinkIcon,
		XIcon,
	},
	computed: {
		...mapGetters([
			'clipboard',
			'config',
			'user',
		]),
		favourites() {
			return this.user.data.relationships.favourites.data
		},
		storage() {
			return this.$store.getters.user.data.attributes.storage
		},
		tree() {
			return this.user.data.attributes.folders
		},
	},
	data() {
		return {
			draggedItem: undefined,
			area: false,
		}
	},
	methods: {
		resetData() {
			this.$store.commit('SET_CURRENT_TEAM_FOLDER', null)
			this.$store.commit('CLIPBOARD_CLEAR')
		},
		goToFolder(folder) {
			this.$router.push({name: 'Files', params: {id: folder.data.id}})
		},
		dragLeave() {
			this.area = false
		},
		dragEnter() {
			if (this.draggedItem && this.draggedItem.type !== 'folder') return

			if (this.clipboard.length > 0 && this.clipboard.find(item => item.type !== 'folder')) return

			this.area = true
		},
		dragFinish() {
			this.area = false

			events.$emit('drop')

			// Check if dragged item is folder
			if (this.draggedItem && this.draggedItem.type !== 'folder') return

			// Check if folder exist in favourites
			if (this.favourites.find(folder => folder.id === this.draggedItem.id)) return

			// Prevent to move folders to self
			if (this.clipboard.length > 0 && this.clipboard.find(item => item.type !== 'folder')) return

			//Add to favourites non selected folder
			if (!this.clipboard.includes(this.draggedItem)) {
				this.$store.dispatch('addToFavourites', this.draggedItem)
			}

			//Add to favourites selected folders
			if (this.clipboard.includes(this.draggedItem)) {
				this.$store.dispatch('addToFavourites', null)
			}
		},
	},
	created() {
		// Listen for dragstart folder items
		events.$on('dragstart', item => this.draggedItem = item)
	}
}
</script>

<style lang="scss" scoped>

	.empty-note {

		&.navigator {
			padding: 5px 25px 10px;
		}

		&.favourites {
			padding: 5px 23px 10px;
		}
	}

	.navigator {
		width: 100%;
		overflow-x: auto;
	}

	@media only screen and (max-width: 1024px) {

		.empty-note {

			&.navigator {
				padding: 5px 20px 10px;
			}

			&.favourites {
				padding: 5px 18px 10px;
			}
		}
	}

	.folder-item {
		transition: all 300ms;
		display: inline-block;
		margin-right: 10px;
	}
	.folder-enter, .folder-leave-to {
		opacity: 0;
		transform: translateY(-20px);
	}
	.folder-leave-active {
		position: absolute;
	}
</style>