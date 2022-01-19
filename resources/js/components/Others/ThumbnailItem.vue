<template>
    <div class="file-item" v-if="item">

        <!--Thumbnail for item-->
        <div class="icon-item">

            <!--If is file or image, then link item-->
            <span v-if="isFile || (isImage && !item.data.attributes.thumbnail) " class="file-icon-text text-theme">{{ item.data.attributes.mimetype }}</span>

            <!--Folder thumbnail-->
            <FontAwesomeIcon v-if="isFile || (isImage && !item.data.attributes.thumbnail)" class="file-icon" :class="{'file-icon-mobile' : $isMobile()}" icon="file"/>

            <!--Image thumbnail-->
            <img v-if="isImage && item.data.attributes.thumbnail" class="image" :src="item.data.attributes.thumbnail.xs"/>

            <!--Else show only folder icon-->
            <FolderIcon v-if="isFolder" :item="item" :folder-icon="setFolderIcon" location="thumbnail-item" class="folder svg-color-theme" />
        </div>

        <!--Name-->
        <div class="item-name">

            <!--Name-->
            <span class="name">{{ item.data.attributes.name }}</span>

            <div v-if="info === 'location'">
                <span class="subtitle">{{ $t('item_thumbnail.original_location') }}: {{ itemLocation }}</span>
            </div>

            <div v-if="info === 'metadata'">
                <span v-if="! isFolder" class="item-size">{{ item.data.attributes.filesize }}, {{ item.data.attributes.created_at }}</span>

                <span v-if="isFolder" class="item-length">
                    {{ item.data.attributes.items === 0 ? $t('folder.empty') : $tc('folder.item_counts', item.data.attributes.items) }}, {{ item.data.attributes.created_at }}
                </span>
            </div>

        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import FolderIcon from '/resources/js/components/FilesView/FolderIcon'

    export default {
        name: 'ThumbnailItem',
        props: ['item', 'info', 'setFolderIcon'],
        components: {FolderIcon},
        computed: {
            ...mapGetters(['currentFolder']),
            isFolder() {
                return this.item.data.type === 'folder'
            },
            isFile() {
                return this.item.data.type !== 'folder' && this.item.data.type !== 'image'
            },
            isImage() {
                return this.item.data.type === 'image'
            },
            itemLocation() {
                return this.item.data.attributes.parent ? this.item.data.attributes.parent.name : this.$t('locations.home')
            }
        },
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .file-item {
        display: flex;
        align-items: center;

        .item-name {
            display: block;
            margin-left: 10px;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            .item-size,
            .item-length,
            .subtitle {
                @include font-size(12);
                font-weight: 400;
                color: $text-muted;
                display: block;
            }

            .name {
                white-space: nowrap;
                color: $text;
                @include font-size(14);
                font-weight: 700;
                max-height: 40px;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        .icon-item {
            position: relative;
            min-width: 52px;
            display: flex;
            text-align: center;
            justify-content: center;
            line-height: 0;

            .file-icon {
                @include font-size(35);

                path {
                    fill: #fafafc;
                    stroke: #dfe0e8;
                    stroke-width: 1;
                }
            }

            .folder {
                width: 36px;
                height: 36px;

                /deep/ .folder-icon {
					transform: scale(0.8) translate(-10px, -11px);
                }
            }

            .file-icon-text {
                line-height: 1;
                top: 40%;
                @include font-size(8);
                margin: 0 auto;
                position: absolute;
                text-align: center;
                left: 0;
                right: 0;
                font-weight: 600;
                user-select: none;
                max-width: 20px;
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
                width: 52px;
                height: 52px;
            }
        }
    }

    .dark {
        .file-item {
            .icon-item .file-icon {
                path {
                    fill: $dark_mode_foreground;
                    stroke: #2F3C54;
                }
            }

            .icon-item .file-icon-mobile {
                path {
                    fill: $dark_mode_background !important;
                    // stroke: ;
                }
            }

            .item-name {
                .name {
                    color: $dark_mode_text_primary;
                }

                .item-size,
                .item-length,
                .subtitle {
                    color: $dark_mode_text_secondary;
                }
            }
        }

        .popup-wrapper {
            .file-item {
                .icon-item .file-icon {
                    path {
                        fill: lighten($dark_mode_foreground, 3%);
                    }
                }
            }
        }
    }

    @media (max-width: 690px) {
		.dark .file-item {
            .icon-item .file-icon {
                path {
                    fill: $dark_mode_foreground;
                }
            }
        }
    }
</style>
