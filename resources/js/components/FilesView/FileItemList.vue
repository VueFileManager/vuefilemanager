<template>
    <div class="file-wrapper" @mouseup.stop="clickedItem" @dblclick="goToItem" spellcheck="false">
        <div
            :draggable="canDrag"
            @dragstart="$emit('dragstart')"
            @drop="drop()"
            @dragleave="dragLeave"
            @dragover.prevent="dragEnter"
            class="file-item" :class="{'is-clicked' : isClicked , 'no-clicked' : !isClicked && $isMobile(), 'is-dragenter': area }"
        >
            <!-- MultiSelecting for the mobile version -->
            <transition name="slide-from-left">
                <CheckBox v-if="mobileMultiSelect" :is-clicked="isClicked" class="check-box"/>
            </transition>

            <!--Thumbnail for item-->
            <div class="icon-item">

				<MemberAvatar
					v-if="canShowAuthor"
					:size="28"
					:is-border="true"
					:member="item.data.relationships.user"
					class="absolute -right-2 -bottom-2"
				/>

                <!--If is file or image, then link item-->
                <span v-if="isFile || isVideo || (isImage && !item.data.attributes.thumbnail)" class="file-icon-text text-theme dark-text-theme">
                    {{ item.data.attributes.mimetype | limitCharacters }}
                </span>

                <!--Folder thumbnail-->
                <FontAwesomeIcon v-if="isFile || isVideo || (isImage && !item.data.attributes.thumbnail)" class="file-icon" icon="file" />

                <!--Image thumbnail-->
                <img loading="lazy" v-if="isImage && item.data.attributes.thumbnail" class="image" :src="item.data.attributes.thumbnail" :alt="item.data.attributes.name" />

                <!--Else show only folder icon-->
                <FolderIcon v-if="isFolder" :item="item" location="file-item-list" class="folder svg-color-theme" />
            </div>

            <!--Name-->
            <div class="item-name">
                <b :ref="item.data.id" @input="renameItem" @keydown.delete.stop @click.stop :contenteditable="canEditName" class="name">
                    {{ itemName }}
                </b>

                <div class="item-info">
                    <!--Shared Icon-->
                    <div v-if="$checkPermission('master') && item.data.relationships.shared" class="item-shared">
                        <link-icon size="12" class="shared-icon text-theme dark-text-theme"/>
                    </div>

                    <!--Filesize and timestamp-->
                    <span v-if="!isFolder" class="item-size">{{ item.data.attributes.filesize }}, {{ timeStamp }}</span>

                    <!--Folder item counts-->
                    <span v-if="isFolder" class="item-length"> {{ folderItems === 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems) }}, {{ timeStamp }} </span>
                </div>
            </div>

            <!--Show item actions-->
            <transition name="slide-from-right">
                <div class="actions" v-if="$isMobile() && ! mobileMultiSelect">
                    <span @mousedown.stop="showItemActions" class="show-actions">
                        <MoreVerticalIcon size="16" class="icon-action text-theme dark-text-theme" />
                    </span>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import {LinkIcon, MoreVerticalIcon} from 'vue-feather-icons'
import FolderIcon from '/resources/js/components/FilesView/FolderIcon'
import CheckBox from '/resources/js/components/FilesView/CheckBox'
import MemberAvatar from "./MemberAvatar";
import {events} from '/resources/js/bus'
import {debounce} from 'lodash'
import {mapGetters} from 'vuex'

export default {
    name: 'FileItemList',
    props: [
		'disableHighlight',
		'item',
	],
    components: {
        MoreVerticalIcon,
		MemberAvatar,
        FolderIcon,
        CheckBox,
        LinkIcon,
    },
    computed: {
        ...mapGetters([
            'FilePreviewType',
            'clipboard',
            'entries',
            'user',
        ]),
		canShowAuthor() {
			return this.$isThisRoute(this.$route, ['SharedWithMe', 'TeamFolders'])
				&& !this.isFolder
				&& this.user.data.id !== this.item.data.relationships.user.data.id
		},
        isClicked() {
            return !this.disableHighlight && this.clipboard.some(element => element.data.id === this.item.data.id)
        },
        isFolder() {
            return this.item.data.type === 'folder'
        },
        isFile() {
            return this.item.data.type === 'file'
        },
        isImage() {
            return this.item.data.type === 'image'
        },
        isPdf() {
            return this.item.data.attributes.mimetype === 'pdf'
        },
        isVideo() {
            return this.item.data.type === 'video'
        },
        isAudio() {
            let mimetypes = ['mpeg', 'mp3', 'mp4', 'wan', 'flac']
            return mimetypes.includes(this.item.data.attributes.mimetype) && this.item.data.type === 'audio'
        },
        canEditName() {
            return !this.$isMobile() && !this.$isThisRoute(this.$route, ['Trash']) && !this.$checkPermission('visitor') && !(this.sharedDetail && this.sharedDetail.attributes.type === 'file')
        },
        canDrag() {
            return !this.isDeleted && this.$checkPermission(['master', 'editor'])
        },
        timeStamp() {
            return this.item.data.attributes.deleted_at ? this.$t('item_thumbnail.deleted_at', {time: this.item.data.attributes.deleted_at}) : this.item.data.attributes.created_at
        },
        folderItems() {
            return this.item.data.attributes.deleted_at ? this.item.data.attributes.trashed_items : this.item.data.attributes.items
        },
        isDeleted() {
            return this.item.data.attributes.deleted_at
        }
    },
    filters: {
        limitCharacters(str) {
            if (str.length > 3) {
                return str.substring(0, 3) + '...'
            } else {
                return str.substring(0, 3)
            }
        }
    },
    data() {
        return {
            area: false,
            itemName: undefined,
            mobileMultiSelect: false
        }
    },
    methods: {
        drop() {
            this.area = false
            events.$emit('drop')
        },
        showItemActions() {
            this.$store.commit('CLIPBOARD_CLEAR')
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

            events.$emit('mobile-menu:show', 'file-menu')
			events.$emit('mobile-context-menu:show', this.item)
        },
        dragEnter() {
            if (this.item.data.type !== 'folder') return

            this.area = true
        },
        dragLeave() {
            this.area = false
        },
        clickedItem(e) {
			// Disabled right click
			if (e.button === 2) return

            if (!this.$isMobile()) {

                // After click deselect new folder rename input
				if (document.getSelection().toString().length) {
					document.getSelection().removeAllRanges();
				}

                if ((e.ctrlKey || e.metaKey) && !e.shiftKey) {

                	// Click + Ctrl
                    if (this.clipboard.some(item => item.data.id === this.item.data.id)) {
                        this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item)
                    } else {
                        this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                    }
                } else if (e.shiftKey) {

                	// Click + Shift
                    let lastItem = this.entries.indexOf(this.clipboard[this.clipboard.length - 1])
                    let clickedItem = this.entries.indexOf(this.item)

                    // If Click + Shift + Ctrl dont remove already selected items
                    if (!e.ctrlKey && !e.metaKey) {
                        this.$store.commit('CLIPBOARD_CLEAR')
                    }

                    //Shift selecting from top to bottom
                    if (lastItem < clickedItem) {
                        for (let i = lastItem; i <= clickedItem; i++) {
                            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entries[i])
                        }
                        //Shift selecting from bottom to top
                    } else {
                        for (let i = lastItem; i >= clickedItem; i--) {
                            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entries[i])
                        }
                    }
                } else {

                	// Click
                    this.$store.commit('CLIPBOARD_CLEAR')
                    this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                }
            }

            if (!this.mobileMultiSelect && this.$isMobile()) {

                if (this.isFolder) {
					this.$goToFileView(this.item.data.id)
                } else {

                    if (this.isImage || this.isVideo || this.isAudio || this.isPdf) {

                        this.$store.commit('CLIPBOARD_CLEAR')
                        this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

                        events.$emit('file-preview:show')
                    }
                }
            }

            if (this.mobileMultiSelect && this.$isMobile()) {
                if (this.clipboard.some(item => item.data.id === this.item.data.id)) {
                    this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item)
                } else {
                    this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                }
            }
        },
        goToItem() {
            if (this.isImage || this.isVideo || this.isAudio || this.isPdf) {
                events.$emit('file-preview:show')

            } else if (this.isFile || !this.isFolder && !this.isVideo && !this.isAudio && !this.isImage) {
                this.$downloadFile(this.item.data.attributes.file_url, this.item.data.attributes.name + '.' + this.item.data.attributes.mimetype)

            } else if (this.isFolder) {

                // Clear selected items after open another folder
                this.$store.commit('CLIPBOARD_CLEAR')

				this.$goToFileView(this.item.data.id)
            }
        },
        renameItem: debounce(function (e) {
            // Prevent submit empty string
            if (e.target.innerText.trim() === '') return

            this.$store.dispatch('renameItem', {
                id: this.item.data.id,
                type: this.item.data.type,
                name: e.target.innerText
            })
        }, 300)
    },
    created() {

        this.itemName = this.item.data.attributes.name

        events.$on('newFolder:focus', (id) => {

            if (this.item.data.id === id && !this.$isMobile()) {
                this.$refs[id].focus()
                document.execCommand('selectAll')
            }
        })

        events.$on('mobile-select:start', () => {
            this.mobileMultiSelect = true
            this.$store.commit('CLIPBOARD_CLEAR')
        })

        events.$on('mobile-select:stop', () => {
            this.mobileMultiSelect = false
            this.$store.commit('CLIPBOARD_CLEAR')
        })

        // Change item name
        events.$on('change:name', item => {
            if (this.item.data.id === item.id) this.itemName = item.name
        })
    }
}
</script>

<style scoped lang="scss">
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';


.slide-from-left-move {
    transition: transform 300s ease;
}

.slide-from-left-enter-active,
.slide-from-right-enter-active,
.slide-from-left-leave-active,
.slide-from-right-leave-active {
    transition: all 300ms;
}

.slide-from-left-enter,
.slide-from-left-leave-to {
    opacity: 0;
    transform: translateX(-100%);
}

.slide-from-right-enter,
.slide-from-right-leave-to {
    opacity: 0;
    transform: translateX(100%);
}

.check-box {
    margin-right: 15px;
    margin-left: 6px;
}

.file-wrapper {
    user-select: none;
    position: relative;

    &:hover {
        border-color: transparent;
    }

    .actions {
        text-align: right;
        width: 50px;

        .show-actions {
            cursor: pointer;
            padding: 12px 0 12px 6px;

            .icon-action {
                margin-top: 9px;
                @include font-size(14);

                circle {
                    color: inherit;
                }
            }
        }
    }

    .item-name {
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

        .item-info {
            display: block;
            line-height: 1;
        }

        .item-shared {
            display: inline-block;

            .label {
                @include font-size(12);
                font-weight: 400;
                color: $theme;
            }

            .shared-icon {
                vertical-align: middle;

                path,
                circle,
                line {
                    color: inherit;
                }
            }
        }

        .item-size,
        .item-length {
            @include font-size(11);
            font-weight: 400;
            color: rgba($text, 0.7);
        }

        .name {
            white-space: nowrap;

            &[contenteditable] {
                -webkit-user-select: text;
                user-select: text;
            }

            &[contenteditable='true']:hover {
                text-decoration: underline;
            }
        }

        .name {
            color: $text;
            @include font-size(14);
            font-weight: 700;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;

            &.actived {
                max-height: initial;
            }
        }
    }

    &.selected {
        .file-item {
            background: $light_background;
        }
    }

    .icon-item {
        text-align: center;
        position: relative;
        flex: 0 0 50px;
        line-height: 0;
        margin-right: 20px;

        .folder {
            width: 52px;
            height: 52px;

            /deep/ .folder-icon {
                @include font-size(52)
            }
        }

        .file-icon {
            @include font-size(45);

            path {
                fill: #fafafc;
                stroke: #dfe0e8;
                stroke-width: 1;
            }
        }

        .file-icon-text {
            line-height: 1;
            top: 40%;
            @include font-size(11);
            margin: 0 auto;
            position: absolute;
            text-align: center;
            left: 0;
            right: 0;
            font-weight: 600;
            user-select: none;
            max-width: 50px;
            max-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .image {
            object-fit: cover;
            user-select: none;
            max-width: 100%;
            border-radius: 5px;
            width: 50px;
            height: 50px;
            pointer-events: none;
        }
    }

    .file-item {
        border: 2px dashed transparent;
        width: 100%;
        display: flex;
        align-items: center;
        padding: 7px;

        &.is-dragenter {
            border-radius: 8px;
        }

        &.no-clicked {
            background: white !important;

            .item-name {
                .name {
                    color: $text !important;
                }
            }
        }

        &:hover,
        &.is-clicked {
            border-radius: 8px;
            background: $light_background;
        }
    }
}

.dark-mode {

    .file-wrapper {
        .icon-item {
            .file-icon {
                path {
                    fill: $dark_mode_foreground;
                    stroke: #2f3c54;
                }
            }
        }

        .file-item {
            &.no-clicked {
                background: $dark_mode_background !important;

                .file-icon {

                    path {
                        fill: $dark_mode_foreground !important;
                        stroke: #2F3C54;
                    }
                }

                .item-name {

                    .name {
                        color: $dark_mode_text_primary !important;
                    }
                }
            }

            &:hover,
            &.is-clicked {
                background: $dark_mode_foreground;

                .file-icon {
                    path {
                        fill: $dark_mode_background;
                    }
                }
            }
        }

        .item-name {
            .name {
                color: $dark_mode_text_primary;
            }

            .item-size,
            .item-length {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>