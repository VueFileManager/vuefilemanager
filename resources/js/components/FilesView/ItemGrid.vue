<template>
	<div
		:class="{'dark:bg-dark-foreground bg-light-background': isClicked}"
		class="flex flex-wrap items-center justify-center relative z-0 text-center lg:h-60 sm:h-56 h-48 px-1 pt-2 rounded-lg select-none border-2 border-transparent border-dashed dark:hover:bg-dark-foreground lg:hover:bg-light-background"
		:draggable="canDrag"
		spellcheck="false"
	>

		<!--MultiSelecting for the mobile version-->
		<CheckBox v-if="isMultiSelectMode" :is-clicked="isClicked" class="mr-5"/>

		<div class="w-full">
			<!--Item thumbnail-->
			<div class="relative mx-auto">

				<!--Emoji Icon-->
				<Emoji
					v-if="entry.data.attributes.emoji"
					:emoji="entry.data.attributes.emoji"
					class="text-5xl transform scale-150 inline-block mb-10"
				/>

				<!--Folder Icon-->
				<FolderIcon v-if="isFolder && !entry.data.attributes.emoji" :item="entry" class="inline-block transform scale-150 lg:mt-2 lg:mb-8 mt-3 mb-5" />

				<!--File Icon-->
				<div v-if="isFile || isVideo || isAudio || (isImage && !entry.data.attributes.thumbnail)" class="relative w-24 mx-auto">

					<!--Member thumbnail for team folders-->
					<MemberAvatar
						v-if="user && canShowAuthor"
						:size="38"
						:is-border="true"
						:member="entry.data.relationships.owner"
						class="absolute lg:-bottom-7 right-2 -bottom-5 z-10 transform lg:scale-100 scale-75 z-10"
					/>

					<FileIconThumbnail :entry="entry" class="transform lg:scale-150 scale-125 lg:mb-12 lg:mt-6 mt-5 mb-10 z-0" />
				</div>

				<!--Image thumbnail-->
				<div v-if="isImage && entry.data.attributes.thumbnail" class="relative inline-block lg:w-36 lg:h-28 w-28 h-24 mb-4">

					<!--Member thumbnail for team folders-->
					<MemberAvatar
						v-if="user && canShowAuthor"
						:size="38"
						:is-border="true"
						:member="entry.data.relationships.owner"
						class="absolute -right-3 -bottom-2.5 transform lg:scale-100 scale-75 z-10"
					/>

					<img class="object-cover w-full h-full rounded-lg shadow-lg" :src="entry.data.attributes.thumbnail.sm" :alt="entry.data.attributes.name" loading="lazy" />
				</div>
			</div>

			<!--Item Info-->
			<div class="text-center">

				<!--Item Title-->
				<b class="inline-block leading-3 text-sm hover:underline w-full text-ellipsis overflow-hidden whitespace-nowrap md:px-6 tracking-tigh" ref="name" @input="renameItem" @keydown.delete.stop @click.stop :contenteditable="canEditName">
					{{ itemName }}
				</b>

				<!--Item sub line-->
				<div class="flex items-center justify-center">

					<!--Shared Icon-->
					<div v-if="$checkPermission('master') && entry.data.relationships.shared">
						<link-icon size="12" class="mr-1.5 text-theme dark-text-theme vue-feather"/>
					</div>

					<!--File & Image sub line-->
					<small v-if="! isFolder" class="block text-xs text-gray-500">
						{{ entry.data.attributes.filesize }}<span class="lg:inline-block hidden text-xs text-gray-500">, {{ timeStamp }}</span>
					</small>

					<!--Folder sub line-->
					<small v-if="isFolder" class="block text-xs text-gray-500">
						{{ folderItems === 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems) }}<span class="lg:inline-block hidden text-xs text-gray-500">, {{ timeStamp }}</span>
					</small>
				</div>
			</div>

			<!-- Mobile item action button-->
			<div v-if="mobileHandler && ! isMultiSelectMode && $isMobile()" class="flex items-center justify-center py-0.5 px-2 relative">
				<div @mouseup.stop="$openInDetailPanel(entry)" class="p-2.5 sm:block hidden">
					<eye-icon size="18" class="vue-feather opacity-30 inline-block" />
				</div>

				<div @mouseup.stop="showItemActions" class="p-2.5">
					<MoreHorizontalIcon size="18" class="vue-feather text-theme dark-text-theme inline-block" />
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import FolderIcon from "./FolderIcon";
	import {LinkIcon, MoreHorizontalIcon, EyeIcon} from 'vue-feather-icons'
	import FileIconThumbnail from "./FileIconThumbnail"
	import MemberAvatar from "./MemberAvatar"
	import Emoji from "../Others/Emoji"
	import CheckBox from "./CheckBox"
	import {debounce} from "lodash"
	import {mapGetters} from "vuex"
	import {events} from "../../bus"

	export default {
		name: 'ItemList',
		components: {
			FileIconThumbnail,
			MoreHorizontalIcon,
			MemberAvatar,
			FolderIcon,
			CheckBox,
			LinkIcon,
			EyeIcon,
			Emoji,
		},
		props: [
			'mobileHandler',
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
			isAudio() {
				return this.entry.data.type === 'audio'
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
					&& this.user.data.id !== this.entry.data.relationships.owner.data.id
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
				if (this.entry.data.id === item.id) this.itemName = item.name
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
