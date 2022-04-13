<template>
    <div>
        <ItemList
            v-if="itemViewType === 'list'"
            :entry="item"
            :highlight="true"
            :mobile-handler="true"
            @mouseup.stop.native="clickFilter"
            @dragstart.native="$emit('dragstart')"
            @drop.native="drop()"
            @dragleave.native="dragLeave"
            @dragover.prevent.native="dragEnter"
            :class="{ 'border-theme': area }"
        />

        <ItemGrid
            v-if="itemViewType === 'grid'"
            :entry="item"
            :highlight="true"
            :mobile-handler="true"
            :can-hover="true"
            @mouseup.stop.native="clickFilter"
            @dragstart.native="$emit('dragstart')"
            @drop.native="drop()"
            @dragleave.native="dragLeave"
            @dragover.prevent.native="dragEnter"
            :class="{ 'border-theme': area }"
        />
    </div>
</template>

<script>
import { events } from '../../bus'
import ItemList from '../UI/Entries/ItemList'
import ItemGrid from '../UI/Entries/ItemGrid'
import { mapGetters } from 'vuex'

export default {
    name: 'ItemHandler',
    props: ['disableHighlight', 'item'],
    components: {
        ItemList,
        ItemGrid,
    },
    computed: {
        ...mapGetters(['isMultiSelectMode', 'itemViewType', 'clipboard', 'entries', 'user']),
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
    },
    data() {
        return {
            area: false,

            delay: 220,
            clicks: 0,
            timer: null,
        }
    },
    methods: {
        clickFilter(e) {
            // Handle click for mobile device
            if (this.$isMobile()) {
                this.clickedItem(e)
            }

            // Handle click & double click for desktop
            if (!this.$isMobile()) {
                this.clicks++

                if (this.clicks === 1) {
                    let self = this

                    this.timer = setTimeout(() => {
                        this.clickedItem(e)
                        self.clicks = 0
                    }, this.delay)
                } else {
                    clearTimeout(this.timer)

                    this.goToItem(e)
                    this.clicks = 0
                }
            }
        },
        drop() {
            this.area = false
            events.$emit('drop')
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
                    document.getSelection().removeAllRanges()
                }

                if ((e.ctrlKey || e.metaKey) && !e.shiftKey) {
                    // Click + Ctrl
                    if (this.clipboard.some((item) => item.data.id === this.item.data.id)) {
                        this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item.data.id)
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

            if (!this.isMultiSelectMode && this.$isMobile()) {
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

            if (this.isMultiSelectMode && this.$isMobile()) {
                if (this.clipboard.some((item) => item.data.id === this.item.data.id)) {
                    this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item.data.id)
                } else {
                    this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                }
            }
        },
        goToItem() {
            if (this.isImage || this.isVideo || this.isAudio || this.isPdf) {
                this.$store.commit('CLIPBOARD_CLEAR')
                this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

                events.$emit('file-preview:show')
            } else if (this.isFile || (!this.isFolder && !this.isVideo && !this.isAudio && !this.isImage)) {
                this.$downloadFile(
                    this.item.data.attributes.file_url,
                    this.item.data.attributes.name + '.' + this.item.data.attributes.mimetype
                )
            } else if (this.isFolder) {
                this.$goToFileView(this.item.data.id)
            }
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

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
                @include font-size(52);
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

.dark {
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
                        stroke: #2f3c54;
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
