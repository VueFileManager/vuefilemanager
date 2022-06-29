<template>
    <div
        :class="{
            'bg-light-background dark:bg-dark-foreground': isClicked && canHover,
            'dark:hover:bg-dark-foreground lg:hover:bg-light-background': canHover,
        }"
        class="relative z-0 flex h-48 select-none flex-wrap items-center justify-center rounded-lg border-2 border-dashed border-transparent px-1 pt-2 text-center sm:h-56 lg:h-60 cursor-pointer"
        :draggable="canDrag"
        spellcheck="false"
    >
        <!--MultiSelecting for the mobile version-->
        <CheckBox v-if="isMultiSelectMode" :is-clicked="isClicked" class="mr-5" />

        <div class="w-full">
            <!--Item thumbnail-->
            <div class="relative mx-auto">
                <!--Emoji Icon-->
                <Emoji
                    v-if="entry.data.attributes.emoji"
                    :emoji="entry.data.attributes.emoji"
                    class="mb-10 inline-block scale-150 transform text-5xl"
                />

                <!--Folder Icon-->
                <FolderIcon
                    v-if="isFolder && !entry.data.attributes.emoji"
                    :item="entry"
                    class="mt-3 mb-5 inline-block scale-150 transform lg:mt-2 lg:mb-8"
                />

                <!--File Icon-->
                <div
                    v-if="isFile || isVideo || isAudio || (isImage && !entry.data.attributes.thumbnail)"
                    class="relative mx-auto w-24"
                >
                    <!--Member thumbnail for team folders-->
                    <MemberAvatar
                        v-if="user && canShowAuthor"
                        :size="38"
                        :is-border="true"
                        :member="entry.data.relationships.creator"
                        class="absolute right-2 -bottom-5 z-10 z-10 scale-75 transform lg:-bottom-7 lg:scale-100"
                    />

                    <FileIconThumbnail
                        :entry="entry"
                        class="z-0 mt-5 mb-10 scale-125 transform lg:mb-12 lg:mt-6 lg:scale-150"
                    />
                </div>

                <!--Image thumbnail-->
                <div
                    v-if="isImage && entry.data.attributes.thumbnail"
                    class="relative mb-4 inline-block h-24 w-28 lg:h-28 lg:w-36"
                >
                    <!--Member thumbnail for team folders-->
                    <MemberAvatar
                        v-if="user && canShowAuthor"
                        :size="38"
                        :is-border="true"
                        :member="entry.data.relationships.creator"
                        class="absolute -right-3 -bottom-2.5 z-10 scale-75 transform lg:scale-100"
                    />

                    <img
                        class="h-full w-full rounded-lg object-cover shadow-lg pointer-events-none"
                        :src="imageSrc"
						alt=""
                        loading="lazy"
						@error="replaceByOriginal"
                    />
                </div>
            </div>

            <!--Item Info-->
            <div class="text-center">
                <!--Item Title-->
                <span
                    class="inline-block w-full overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold leading-3 tracking-tight md:px-6"
                    :class="{ 'hover:underline cursor-text': canEditName }"
                    ref="name"
                    @input="renameItem"
                    @keydown.delete.stop
                    @click.stop
					@keydown.enter="$refs.name.blur()"
                    :contenteditable="canEditName"
                >
                    {{ itemName }}
                </span>

                <!--Item sub line-->
                <div class="flex items-center justify-center">

                    <!--File & Image sub line-->
                    <small v-if="!isFolder" class="block text-xs text-gray-500 dark:text-gray-500">
						<link-icon size="12" class="text-theme dark-text-theme vue-feather inline-block mr-0.5 mb-1" />
						{{ entry.data.attributes.filesize }},
                        <span class="hidden text-xs text-gray-500 dark:text-gray-500 lg:inline-block"
                            >{{ timeStamp }}</span
                        >
                    </small>

                    <!--Folder sub line-->
                    <small v-if="isFolder" class="block text-xs text-gray-500 dark:text-gray-500">
						<link-icon v-if="canShowLinkIcon" size="12" class="text-theme dark-text-theme vue-feather mr-0.5 mb-1 inline-block" />
                        {{ folderItems === 0 ? $t('empty') : $tc('folder.item_counts', folderItems)
                        }}, <span class="hidden text-xs text-gray-500 dark:text-gray-500 lg:inline-block"
                            >{{ timeStamp }}</span
                        >
                    </small>
                </div>
            </div>

            <!-- Mobile item action button-->
            <div
                v-if="mobileHandler && !isMultiSelectMode && $isMobile()"
                class="relative flex items-center justify-center py-0.5 px-2"
            >
                <div @mouseup.stop="$openInDetailPanel(entry)" class="hidden p-2.5 sm:block">
                    <eye-icon size="18" class="vue-feather inline-block opacity-30" />
                </div>

                <div @mouseup.stop="showItemActions" class="p-2.5">
                    <MoreHorizontalIcon size="18" class="vue-feather text-theme dark-text-theme inline-block" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FolderIcon from '../../Icons/FolderIcon'
import { LinkIcon, MoreHorizontalIcon, EyeIcon } from 'vue-feather-icons'
import FileIconThumbnail from '../../Icons/FileIconThumbnail'
import MemberAvatar from '../Others/MemberAvatar'
import Emoji from '../../Emoji/Emoji'
import CheckBox from '../../Inputs/CheckBox'
import { debounce } from 'lodash'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'

export default {
    name: 'ItemGrid',
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
    props: ['mobileHandler', 'entry', 'canHover'],
    data() {
        return {
            mobileMultiSelect: false,
            itemName: undefined,
			imageSrc: undefined,
        }
    },
    computed: {
        ...mapGetters(['isMultiSelectMode', 'clipboard', 'user']),
        isClicked() {
            return this.clipboard.some((element) => element.data.id === this.entry.data.id)
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
				? this.$t('item_thumbnail.deleted_at', {
					time: this.entry.data.attributes.deleted_at,
				})
				: this.entry.data.attributes.created_at
		},
        canEditName() {
            return (
                !this.$isMobile() &&
                !this.$isThisRoute(this.$route, ['Trash', 'SharedSingleFile']) &&
                !this.$checkPermission('visitor')
            )
        },
        folderItems() {
            return this.entry.data.attributes.deleted_at
                ? this.entry.data.attributes.trashed_items
                : this.entry.data.attributes.items
        },
        canShowAuthor() {
            return (
                this.$isThisRoute(this.$route, ['SharedWithMe', 'TeamFolders'])
				&& !this.isFolder
				&& this.entry.data.relationships.creator
				&& this.user.data.id !== this.entry.data.relationships.creator.data.id
            )
        },
        canShowLinkIcon() {
            return this.entry.data.relationships.shared && !this.$isThisRoute(this.$route, ['SharedSingleFile'])
        },
        canDrag() {
            return !this.isDeleted && this.$checkPermission(['master', 'editor'])
        },
    },
    methods: {
		getImageSrc() {
			this.imageSrc = this.entry.data.attributes.mimetype === 'svg'
				? this.entry.data.attributes.file_url
				: this.entry.data.attributes.thumbnail.sm
		},
		replaceByOriginal() {
			this.imageSrc = this.entry.data.attributes.file_url
		},
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
                name: e.target.innerText,
            })
        }, 300),
    },
    created() {
        // Change item name
        events.$on('change:name', (item) => {
            if (this.entry.data.id === item.id) this.itemName = item.name
        })

        // Autofocus after newly created folder
        events.$on('newFolder:focus', (id) => {
            if (!this.$isMobile() && this.entry.data.id === id) {
                this.$refs.name.focus()
                document.execCommand('selectAll')
            }
        })

		// Set item name to own component variable
		this.itemName = this.entry.data.attributes.name

		if (this.entry.data.type === 'image') {
			this.getImageSrc()
		}
    },
}
</script>
