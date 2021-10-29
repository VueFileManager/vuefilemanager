<template>
	<div :class="{'dark:bg-dark-foreground bg-light-background': isClicked && highlight, 'dark:hover:bg-dark-foreground hover:bg-light-background': highlight}" class="flex items-center px-2.5 py-2 rounded-lg select-none border-2 border-transparent border-dashed" :draggable="canDrag" spellcheck="false">

		<!--MultiSelecting for the mobile version-->
		<CheckBox v-if="isMultiSelectMode" :is-clicked="isClicked" class="mr-5"/>

		<!--Item thumbnail-->
		<div class="w-16 relative">

			<!--Member thumbnail for team folders-->
			<MemberAvatar
				v-if="user && canShowAuthor"
				:size="28"
				:is-border="true"
				:member="entry.data.relationships.user"
				class="absolute right-1.5 -bottom-2 z-10"
			/>

			<!--Folder Icon-->
			<FolderIcon v-if="isFolder" :item="entry" location="file-item-list" />

			<!--File Icon-->
			<FileIconThumbnail v-if="isFile || isVideo || (isImage && !entry.data.attributes.thumbnail)" :entry="entry" class="pr-2" />

			<!--Image thumbnail-->
			<img v-if="isImage && entry.data.attributes.thumbnail" class="w-12 h-12 rounded ml-0.5" :src="entry.data.attributes.thumbnail" :alt="entry.data.attributes.name" loading="lazy" />
		</div>

		<!--Item Info-->
		<div class="pl-2">

			<!--Item Title-->
			<b class="block text-sm mb-0.5 hover:underline" ref="name" @input="renameItem" @keydown.delete.stop @click.stop :contenteditable="canEditName">
				{{ itemName }}
			</b>

			<!--Item sub line-->
			<div class="flex items-center">

				<!--Shared Icon-->
				<div v-if="$checkPermission('master') && entry.data.relationships.shared">
					<link-icon size="12" class="mr-1.5 text-theme dark-text-theme vue-feather"/>
				</div>

				<!--File & Image sub line-->
				<small v-if="! isFolder" class="block text-xs text-gray-500">
					{{ entry.data.attributes.filesize }}, {{ timeStamp }}
				</small>

				<!--Folder sub line-->
				<small v-if="isFolder" class="block text-xs text-gray-500">
					{{ folderItems === 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems) }}, {{ timeStamp }}
				</small>
			</div>
		</div>

		<!-- Mobile item action button-->
		<div v-if="! isMultiSelectMode" class="block pr-1 flex-grow text-right md:hidden">
			<MoreVerticalIcon @mousedown.stop="showItemActions" size="16" class="vue-feather text-theme dark-text-theme inline-block transform scale-110" />
		</div>
	</div>
</template>

<script>
	import FolderIcon from '/resources/js/components/FilesView/FolderIcon'
	import {LinkIcon, MoreVerticalIcon} from 'vue-feather-icons'
	import FileIconThumbnail from "./FileIconThumbnail";
	import MemberAvatar from "./MemberAvatar";
	import CheckBox from "./CheckBox";
	import {debounce} from "lodash";
	import {mapGetters} from "vuex";
	import {events} from "../../bus";

	export default {
		name: 'ItemList',
		components: {
			FileIconThumbnail,
			MoreVerticalIcon,
			MemberAvatar,
			FolderIcon,
			CheckBox,
			LinkIcon,
		},
		props: [
			'highlight',
			'entry',
		],
		data() {
			return {
				mobileMultiSelect: false,
				itemName: undefined,
			}
		},
		computed: {
			...mapGetters([
				'isMultiSelectMode',
				'clipboard',
				'user',
			]),
			isClicked() {
				return this.clipboard.some(element => element.data.id === this.entry.data.id)
			},
			isVideo() {
				return this.entry.data.type === 'video'
			},
			isFile() {
				return this.entry.data.type === 'file'
			},
			isImage() {
				return this.entry.data.type === 'image'
			},
			isFolder() {
				return this.entry.data.type === 'folder'
			},
			timeStamp() {
				return this.entry.data.attributes.deleted_at
					? this.$t('entry_thumbnail.deleted_at', {time: this.entry.data.attributes.deleted_at})
					: this.entry.data.attributes.created_at
			},
			canEditName() {
				return !this.$isMobile()
					&& !this.$isThisRoute(this.$route, ['Trash'])
					&& !this.$checkPermission('visitor')
					&& !(this.sharedDetail && this.sharedDetail.attributes.type === 'file')
			},
			folderItems() {
				return this.entry.data.attributes.deleted_at
					? this.entry.data.attributes.trashed_items
					: this.entry.data.attributes.items
			},
			canShowAuthor() {
				return this.$isThisRoute(this.$route, ['SharedWithMe', 'TeamFolders'])
					&& !this.isFolder
					&& this.user.data.id !== this.entry.data.relationships.user.data.id
			},
			canDrag() {
				return !this.isDeleted && this.$checkPermission(['master', 'editor'])
			},
		},
		methods: {
			showItemActions() {
				this.$store.commit('CLIPBOARD_CLEAR')
				this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entry)

				this.$showMobileMenu('file-menu')
				events.$emit('mobile-context-menu:show', this.entry)
			},
			renameItem: debounce(function (e) {

				// Prevent submit empty string
				if (e.target.innerText.trim() === '') return

				this.$store.dispatch('renameItem', {
					id: this.entry.data.id,
					type: this.entry.data.type,
					name: e.target.innerText
				})
			}, 300)
		},
		created() {

			// Set item name to own component variable
			this.itemName = this.entry.data.attributes.name

			// Change item name
			events.$on('change:name', item => {
				if (this.item.data.id === item.id) this.itemName = item.name
			})

			// Autofocus after newly created folder
			events.$on('newFolder:focus', id => {

				if ( !this.$isMobile() && this.entry.data.id === id) {
					this.$refs.name.focus()
					document.execCommand('selectAll')
				}
			})
		}
	}
</script>
