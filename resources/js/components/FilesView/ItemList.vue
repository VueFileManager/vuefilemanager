<template>
    <div
        :class="{
            'bg-light-background dark:bg-dark-foreground': isClicked && highlight,
            'hover:bg-light-background dark:hover:bg-dark-foreground': highlight,
        }"
        class="flex select-none items-center rounded-xl border-2 border-dashed border-transparent px-2.5 py-2"
        :draggable="canDrag"
        spellcheck="false"
    >
        <!--MultiSelecting for the mobile version-->
        <CheckBox v-if="isMultiSelectMode" v-model="isChecked" :is-clicked="isClicked" class="mr-5" />

        <!--Item thumbnail-->
        <div class="relative w-16 shrink-0">
            <!--Member thumbnail for team folders-->
            <MemberAvatar
                v-if="user && canShowAuthor"
                :size="28"
                :is-border="true"
                :member="entry.data.relationships.creator"
                class="absolute right-1.5 -bottom-2 z-10"
            />

            <!--Emoji Icon-->
            <Emoji
                v-if="entry.data.attributes.emoji"
                :emoji="entry.data.attributes.emoji"
                class="ml-1 scale-110 transform text-5xl"
            />

            <!--Folder Icon-->
            <FolderIcon v-if="isFolder && !entry.data.attributes.emoji" :item="entry" />

            <!--File Icon-->
            <FileIconThumbnail
                v-if="isFile || isVideo || isAudio || (isImage && !entry.data.attributes.thumbnail)"
                :entry="entry"
                class="pr-2"
            />

            <!--Image thumbnail-->
            <img
                v-if="isImage && entry.data.attributes.thumbnail"
                class="ml-0.5 h-12 w-12 rounded object-cover pointer-events-none"
                :src="imageSrc"
				alt=""
                loading="lazy"
				@error="replaceByOriginal"
            />
        </div>

        <!--Item Info-->
        <div class="pl-2">
            <!--Item Title-->
            <span
                class="mb-0.5 block overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                :class="{ 'hover:underline': canEditName }"
                style="max-width: 240px"
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
            <div class="flex items-center">
                <!--Shared Icon-->
                <div v-if="$checkPermission('master') && entry.data.relationships.shared">
                    <link-icon size="12" class="text-theme dark-text-theme vue-feather mr-1.5" />
                </div>

                <!--File & Image sub line-->
                <small v-if="!isFolder" class="block text-xs text-gray-500 dark:text-gray-500">
                    {{ entry.data.attributes.filesize }}, {{ timeStamp }}
                </small>

                <!--Folder sub line-->
                <small v-if="isFolder" class="block text-xs text-gray-500 dark:text-gray-500">
                    {{ folderItems === 0 ? $t('empty') : $tc('folder.item_counts', folderItems) }},
                    {{ timeStamp }}
                </small>
            </div>
        </div>

        <!-- Mobile item action button-->
        <div v-if="mobileHandler && !isMultiSelectMode && $isMobile()" class="relative flex-grow pr-1 text-right">
            <div
                @mouseup.stop="$openInDetailPanel(entry)"
                class="absolute right-10 -mr-4 hidden -translate-y-2/4 transform p-2.5 lg:block"
            >
                <eye-icon size="18" class="vue-feather inline-block opacity-30" />
            </div>
            <div @mouseup.stop="showItemActions" class="absolute right-0 -mr-4 -translate-y-2/4 transform p-2.5">
                <MoreVerticalIcon size="18" class="vue-feather text-theme dark-text-theme inline-block" />
            </div>
        </div>
    </div>
</template>

<script>
import Emoji from '../Others/Emoji'
import FolderIcon from './FolderIcon'
import { LinkIcon, MoreVerticalIcon, EyeIcon } from 'vue-feather-icons'
import FileIconThumbnail from './FileIconThumbnail'
import MemberAvatar from './MemberAvatar'
import CheckBox from './CheckBox'
import { debounce } from 'lodash'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'ItemList',
    components: {
        FileIconThumbnail,
        MoreVerticalIcon,
        MemberAvatar,
        FolderIcon,
        CheckBox,
        LinkIcon,
        EyeIcon,
        Emoji,
    },
    props: ['mobileHandler', 'highlight', 'entry'],
	watch: {
		isChecked: function (val) {
			if (val) {
				this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entry)
			} else {
				this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.entry.data.id)
			}
		}
	},
    data() {
        return {
            mobileMultiSelect: false,
            itemName: undefined,
            isSelected: false,
			isChecked: false,
			imageSrc: undefined,
		}
    },
    computed: {
        ...mapGetters(['isMultiSelectMode', 'clipboard', 'user']),
        isClicked() {
            return this.clipboard.some((element) => element.data.id === this.entry.data.id)
        },
        isVideo() {
            return this.entry.data.type === 'video'
        },
        isAudio() {
            return this.entry.data.type === 'audio'
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
                !this.$isThisRoute(this.$route, ['Trash']) &&
                !this.$checkPermission('visitor') &&
                !(this.sharedDetail && this.sharedDetail.attributes.type === 'file')
            )
        },
        folderItems() {
            return this.entry.data.attributes.deleted_at
                ? this.entry.data.attributes.trashed_items
                : this.entry.data.attributes.items
        },
        canShowAuthor() {
            return !this.isFolder && (this.entry.data.relationships.creator && this.user.data.id !== this.entry.data.relationships.creator.data.id)
        },
        canDrag() {
            return !this.isDeleted && this.$checkPermission(['master', 'editor'])
        },
    },
    methods: {
		getImageSrc() {
			this.imageSrc = this.entry.data.attributes.mimetype === 'svg'
				? this.entry.data.attributes.file_url
				: this.entry.data.attributes.thumbnail.xs
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
            if (this.entry.data.id === item.id) {
                this.itemName = item.name
            }
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
