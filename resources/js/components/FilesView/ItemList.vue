<template>
	<div :class="{'dark:bg-dark-foreground bg-light-background': isClicked}" class="flex items-center px-2.5 py-2 rounded-lg select-none border-2 border-transparent border-dashed dark:hover:bg-dark-foreground hover:bg-light-background" :draggable="canDrag" spellcheck="false">

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
			<div v-if="isFile || isVideo || (isImage && !entry.data.attributes.thumbnail)" class="flex items-center justify-center pr-2">
				<span class="text-theme dark-text-theme text-xs font-semibold absolute z-10 inline-block mx-auto mt-2 w-7 overflow-ellipsis overflow-hidden text-center">
					{{ entry.data.attributes.mimetype }}
				</span>

				<svg width="38px" height="51px" viewBox="0 0 38 51" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<path
						stroke-width="0"
						fill="#F8F9FA"
						d="M22.1666667,13.546875 L22.1666667,0 L2.375,0 C1.05885417,0 0,1.06582031 0,2.390625 L0,48.609375 C0,49.9341797 1.05885417,51 2.375,51 L35.625,51 C36.9411458,51 38,49.9341797 38,48.609375 L38,15.9375 L24.5416667,15.9375 C23.2354167,15.9375 22.1666667,14.8617187 22.1666667,13.546875 Z M38,12.1423828 L38,12.75 L25.3333333,12.75 L25.3333333,0 L25.9369792,0 C26.5703125,0 27.1739583,0.249023438 27.6192708,0.697265625 L37.3072917,10.4589844 C37.7526042,10.9072266 38,11.5148437 38,12.1423828 Z"></path>
				</svg>
			</div>

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
	import {LinkIcon, MoreVerticalIcon} from 'vue-feather-icons'
	import FolderIcon from '/resources/js/components/FilesView/FolderIcon'
	import MemberAvatar from "./MemberAvatar";
	import CheckBox from "./CheckBox";
	import {debounce} from "lodash";
	import {mapGetters} from "vuex";
	import {events} from "../../bus";

	export default {
		name: 'ItemList',
		components: {
			MoreVerticalIcon,
			MemberAvatar,
			FolderIcon,
			CheckBox,
			LinkIcon,
		},
		props: [
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

				events.$emit('mobile-menu:show', 'file-menu')
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
