<template>
    <div
            class="file-wrapper"
            @click.stop="clickedItem"
            @dblclick="goToItem"
            spellcheck="false"
    >
        <!--List preview-->
        <div
                :draggable="canDrag"
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
            <div class="icon-item">
                <!--If is file or image, then link item-->
                <span v-if="isFile" class="file-icon-text">
                    {{ data.mimetype | limitCharacters }}
                </span>

                <!--Folder thumbnail-->
                <FontAwesomeIcon v-if="isFile" class="file-icon" icon="file"/>

                <!--Image thumbnail-->
                <img v-if="isImage" class="image" :src="data.thumbnail" :alt="data.name"/>

                <!--Else show only folder icon-->
                <FontAwesomeIcon v-if="isFolder" :class="{'is-deleted': isDeleted}" class="folder-icon" icon="folder"/>
            </div>

            <!--Name-->
            <div class="item-name">
                <!--Name-->
                <b
                        ref="name"
                        @input="renameItem"
                        :contenteditable="canEditName"
                        class="name"
                >
                    {{ itemName }}
                </b>

                <div class="item-info">

                    <!--Shared Icon-->
                    <div v-if="$checkPermission('master') && data.shared" class="item-shared">
                        <link-icon size="12" class="shared-icon"></link-icon>
                    </div>

                    <!--Participant owner Icon-->
                    <div v-if="$checkPermission('master') && data.user_scope !== 'master'" class="item-shared">
                        <user-plus-icon size="12" class="shared-icon"></user-plus-icon>
                    </div>

                    <!--Filesize and timestamp-->
                    <span v-if="! isFolder" class="item-size">{{ data.filesize }}, {{ timeStamp }}</span>

                    <!--Folder item counts-->
                    <span v-if="isFolder" class="item-length">
                        {{ folderItems == 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems) }}, {{ timeStamp }}
                    </span>
                </div>
            </div>

            <!--Go Next icon-->
            <div class="actions" v-if="$isMobile() && ! ( $checkPermission('visitor') && isFolder )">
				<span @click.stop="showItemActions" class="show-actions">
					<FontAwesomeIcon icon="ellipsis-v" class="icon-action"></FontAwesomeIcon>
				</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { LinkIcon, UserPlusIcon } from 'vue-feather-icons'
    import {debounce} from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FileItemList',
        props: ['data'],
        components: {
            UserPlusIcon,
            LinkIcon,
        },
        computed: {
            ...mapGetters(['FilePreviewType']),
            isFolder() {
                return this.data.type === 'folder'
            },
            isFile() {
                return this.data.type !== 'folder' && this.data.type !== 'image'
            },
            isImage() {
                return this.data.type === 'image'
            },
            canEditName() {
                return !this.$isMobile()
                    && !this.$isThisLocation(['trash', 'trash-root'])
                    && !this.$checkPermission('visitor')
                    && !(this.sharedDetail && this.sharedDetail.type === 'file')
            },
            canDrag() {
                return !this.isDeleted && this.$checkPermission(['master', 'editor'])
            },
            timeStamp() {
                return this.data.deleted_at ? this.$t('item_thumbnail.deleted_at', {time: this.data.deleted_at}) : this.data.created_at
            },
            folderItems() {
                return this.data.deleted_at ? this.data.trashed_items : this.data.items
            },
            isDeleted() {
                return this.data.deleted_at ? true : false
            }
        },
        filters: {
            limitCharacters(str) {

                if (str.length > 3) {
                    return str.substring(0, 3) + '...';
                } else {
                    return str.substring(0, 3);
                }

            }
        },
        data() {
            return {
                isClicked: false,
                area: false,
                itemName: undefined,
            }
        },
        methods: {
            showItemActions() {
                // Load file info detail
                this.$store.commit('GET_FILEINFO_DETAIL', this.data)

                //this.isClicked = true

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
                events.$emit('fileItem:deselect')

                // Set clicked item
                this.isClicked = true

                // Open in mobile version on first click
                if (this.$isMobile() && this.isFolder) {

                    // Go to folder
                    if (this.$isThisLocation('public')) {
                        this.$store.dispatch('browseShared', [{folder: this.data, back: false, init: false}])
                    } else {
                        this.$store.dispatch('getFolder', [{folder: this.data, back: false, init: false}])
                    }
                }

                // Load file info detail
                this.$store.commit('GET_FILEINFO_DETAIL', this.data)

                // Get target classname
                let itemClass = e.target.className

                if (['name', 'icon', 'file-link', 'file-icon-text'].includes(itemClass))
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

                    if (this.$isThisLocation('public')) {
                        this.$store.dispatch('browseShared', [{folder: this.data, back: false, init: false}])
                    } else {
                        this.$store.dispatch('getFolder', [{folder: this.data, back: false, init: false}])
                    }
                }
            },
            renameItem: debounce(function (e) {

                // Prevent submit empty string
                if (e.target.innerText.trim() === '') return

                this.$store.dispatch('renameItem', {
                    unique_id: this.data.unique_id,
                    type: this.data.type,
                    name: e.target.innerText
                })
            }, 300)
        },
        created() {
            this.itemName = this.data.name

            events.$on('fileItem:deselect', () => {
                // Deselect file
                this.isClicked = false
            })

            // Change item name
            events.$on('change:name', (item) => {
                if (this.data.unique_id == item.unique_id) this.itemName = item.name
            })
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
                padding: 12px 6px 12px;

                .icon-action {
                    @include font-size(14);

                    path {
                        fill: $theme;
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

                    path, circle, line {
                        stroke: $theme;
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

            .folder-icon {
                @include font-size(52);

                path {
                    fill: $theme;
                }

                &.is-deleted {
                    path {
                        fill: $dark_background;
                    }
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
                color: $theme;
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
    }

    @media (prefers-color-scheme: dark) {

        .file-wrapper {

            .icon-item {
                .file-icon {

                    path {
                        fill: $dark_mode_foreground;
                        stroke: #2F3C54;
                    }
                }

                .folder-icon {

                    &.is-deleted {
                        path {
                            fill: lighten($dark_mode_foreground, 5%);
                        }
                    }
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
