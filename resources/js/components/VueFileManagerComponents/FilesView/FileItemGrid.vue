<template>
    <div
            class="file-wrapper"
            @click.stop="clickedItem"
            @dblclick="goToItem"
            spellcheck="false"
    >
        <!--Grid preview-->
        <div
                draggable="true"
                @dragstart="$emit('dragstart')"
                @drop="
				$emit('drop')
				area = false
			"
                @dragleave="dragLeave"
                @dragover.prevent="dragEnter"
                class="file-item"
                :class="{ 'is-clicked': isClicked, 'is-dragenter': area }"
        >
            <!--Thumbnail for item-->
            <div class="icon-item" :class="data.type">
                <!--If is file or image, then link item-->
                <span v-if="isFile" class="file-icon-text">{{
					data.mimetype
				}}</span>

                <!--Folder thumbnail-->
                <FontAwesomeIcon v-if="isFile" class="file-icon" icon="file"/>

                <!--Image thumbnail-->
                <img v-if="isImage" :src="data.thumbnail" :alt="data.name"/>

                <!--Else show only folder icon-->
                <FontAwesomeIcon
                        v-if="isFolder"
                        :class="{'is-deleted': isDeleted}"
                        class="folder-icon"
                        icon="folder"
                />
            </div>

            <!--Name-->
            <div class="item-name">
                <!--Name-->
                <span
                        ref="name"
                        @input="changeItemName"
                        :contenteditable="!$isMobile()"
                        class="name"
                >{{ item.name }}</span
                >

                <!--Other attributes-->
                <span v-if="isFile || isImage" class="item-size">{{
					data.filesize
				}}</span>

                <span v-if="isFolder" class="item-length">
					{{ folderItems == 0 ? 'Empty' : (folderItems + ' item') | pluralize(folderItems) }}
				</span>
            </div>

            <span @click.stop="showItemActions" class="show-actions" v-if="$isMobile()">
					<FontAwesomeIcon icon="ellipsis-h" class="icon-action"></FontAwesomeIcon>
				</span>
        </div>
    </div>
</template>

<script>
    import {debounce} from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FileItem',
        props: ['data'],
        computed: {
            ...mapGetters(['preview_type']),
            isFolder() {
                return this.data.type === 'folder'
            },
            isFile() {
                return this.data.type === 'file'
            },
            isImage() {
                return this.data.type === 'image'
            },
            timeStamp() {
                return this.data.deleted_at ? 'Deleted ' + this.data.deleted_at : this.data.created_at
            },
            folderItems() {
                return this.data.deleted_at ? this.data.trashed_items : this.data.items
            },
            isDeleted() {
                return this.data.deleted_at ? true : false
            }
        },
        filters: {
            pluralize(word, amount) {
                return amount > 1 ? word + 's' : word
            }
        },
        data() {
            return {
                isClicked: false,
                area: false,
                item: undefined
            }
        },
        methods: {
            showItemActions() {
                // Load file info detail
                this.$store.dispatch('loadFileInfoDetail', this.data)

                events.$emit('mobileMenu:show')
            },
            dragEnter() {
                if (this.data.type !== 'folder') return

                this.area = true
            },
            dragLeave() {
                this.area = false
            },
            clickedItem(e) {
                events.$emit('contextMenu:hide')

                // Open in mobile version on first click
                if (this.$isMobile() && this.isFolder) {

                    // Go to folder
                    this.$store.dispatch('goToFolder', [this.data, false])
                }

                // Load file info detail
                this.$store.dispatch('loadFileInfoDetail', this.data)

                // Get target classname
                let itemClass = e.target.className

                if (
                    ['name', 'icon', 'file-link', 'file-icon-text'].includes(
                        itemClass
                    )
                )
                    return
            },
            goToItem() {
                if (this.isImage) {
                    this.$openImageOnNewTab(this.data.file_url)
                }

                if (this.isFile) {
                    this.$downloadFile(
                        this.data.file_url,
                        this.data.name + '.' + this.data.mimetype
                    )
                }

                if (this.isFolder) {
                    // Go to folder
                    this.$store.dispatch('goToFolder', [this.data, false])
                }
            },
            changeItemName: debounce(function (e) {
                // Prevent submit empty string
                if (e.target.innerText === '') return

                this.$store.dispatch('changeItemName', {
                    unique_id: this.data.unique_id,
                    type: this.data.type,
                    name: e.target.innerText
                })
            }, 300)
        },
        created() {
            this.item = this.data

            events.$on('fileItem:clicked', unique_id => {
                if (this.data.unique_id == unique_id) {
                    this.isClicked = true
                } else {
                    this.isClicked = false
                }
            })

            events.$on('fileItem:deselect', () => {
                // Deselect file
                this.isClicked = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .show-actions {
        cursor: pointer;
        padding: 4px 26px;

        .icon-action {
            @include font-size(12);
        }

        path {
            fill: $theme;
        }
    }


    .file-wrapper {
        position: relative;
        text-align: center;
        display: inline-block;
        vertical-align: text-top;
        width: 100%;

        .item-name {
            display: block;
            padding-left: 10px;
            padding-right: 10px;
            line-height: 20px;

            .item-size,
            .item-length {
                @include font-size(12);
                font-weight: 100;
                color: $text-muted;
                display: block;
            }

            .name {
                display: block;

                &[contenteditable='true']:hover {
                    text-decoration: underline;
                }
            }

            .name {
                color: $text;
                @include font-size(15);
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

        .file-item {
            border: 2px dashed transparent;
            width: 165px;
            margin: 0 auto;
            cursor: pointer;
            position: relative;
            padding: 15px 0;

            &.is-dragenter {
                border: 2px dashed $theme;
                border-radius: 8px;
            }

            &:hover,
            &.is-clicked {
                border-radius: 8px;
                background: $light_background;

                .item-name .name {
                    color: $theme;
                }
            }
        }

        .icon-item {
            position: relative;
            height: 110px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;

            .file-link {
                display: block;
            }

            .file-icon {
                @include font-size(100);
                margin: 0 auto;

                path {
                    fill: #fafafc;
                    stroke: #dfe0e8;
                    stroke-width: 1;
                }
            }

            &.file {

                .file-icon-text {
                    margin: 5px auto 0;
                    position: absolute;
                    text-align: center;
                    left: 0;
                    right: 0;
                    color: $theme;
                    font-weight: 600;
                    user-select: none;
                    max-width: 65px;
                    max-height: 20px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            }

            &.image {
                img {
                    max-width: 95%;
                    object-fit: cover;
                    user-select: none;
                    height: 110px;
                    border-radius: 5px;
                    margin: 0 auto;
                }
            }

            &.folder {
                align-items: flex-end;
            }

            .folder-icon {
                @include font-size(80);
                margin: 0 auto;

                path {
                    fill: $theme;
                }

                &.is-deleted {
                    path {
                        fill: $dark_background;
                    }
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .file-wrapper {

            .icon-item .file-icon {

                path {
                    fill: $dark_mode_foreground;
                    stroke: #2F3C54;
                }
            }

            .file-item {

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

            .item-name .name {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
