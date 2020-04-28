<template>
    <div class="file-item" v-if="item">

        <!--Thumbnail for item-->
        <div class="icon-item">

            <!--If is file or image, then link item-->
            <span v-if="isFile" class="file-icon-text">{{ item.mimetype }}</span>

            <!--Folder thumbnail-->
            <FontAwesomeIcon v-if="isFile" class="file-icon" icon="file"/>

            <!--Image thumbnail-->
            <img v-if="isImage" class="image" :src="item.thumbnail" :alt="item.name"/>

            <!--Else show only folder icon-->
            <FontAwesomeIcon v-if="isFolder" class="folder-icon" icon="folder"/>
        </div>

        <!--Name-->
        <div class="item-name">

            <!--Name-->
            <span class="name">{{ item.name }}</span>

            <div v-if="info === 'location'">
                <span class="subtitle">{{ $t('item_thumbnail.original_location') }}: {{ currentFolder.name }}</span>
            </div>

            <div v-if="info === 'metadata'">
                <span v-if="! isFolder" class="item-size">{{ item.filesize }}, {{ item.created_at }}</span>

                <span v-if="isFolder" class="item-length">
                    {{ item.items == 0 ? $t('folder.empty') : $tc('folder.item_counts', item.items) }}, {{ item.created_at }}
                </span>
            </div>

        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'ThumbnailItem',
        props: ['item', 'info'],
        computed: {
            ...mapGetters(['currentFolder']),
            isFolder() {
                return this.item.type === 'folder'
            },
            isFile() {
                return this.item.type !== 'folder' && this.item.type !== 'image'
            },
            isImage() {
                return this.item.type === 'image'
            },
        },
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .file-item {
        display: flex;
        align-items: center;
        padding: 0 20px;

        .item-name {
            display: block;
            margin-left: 10px;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            .item-size,
            .item-length {
                @include font-size(12);
                font-weight: 400;
                color: $text-muted;
                display: block;
            }

            .subtitle {
                @include font-size(11);
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
            min-width: 40px;
            text-align: center;
            line-height: 0;

            .file-icon {
                @include font-size(35);

                path {
                    fill: #fafafc;
                    stroke: #dfe0e8;
                    stroke-width: 1;
                }
            }

            .folder-icon {
                @include font-size(36);

                path {
                    fill: $theme;
                }
            }

            .file-icon-text {
                line-height: 1;
                top: 40%;
                @include font-size(9);
                margin: 0 auto;
                position: absolute;
                text-align: center;
                left: 0;
                right: 0;
                color: $theme;
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
                width: 36px;
                height: 36px;
            }
        }
    }

    .small {
        .file-item {
            padding: 0 15px;
            margin-bottom: 15px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .file-item {

            .icon-item .file-icon {

                path {
                    fill: $dark_mode_background;
                    stroke: #2F3C54;
                }
            }

            .item-name .name {
                color: $dark_mode_text_primary;
            }
        }
    }

    @media (max-width: 690px) and (prefers-color-scheme: dark) {
        .file-item {

            .icon-item .file-icon {

                path {
                    fill: $dark_mode_foreground;
                }
            }
        }
    }
</style>
