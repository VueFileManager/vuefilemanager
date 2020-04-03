<template>
    <div class="file-item">

        <!--Thumbnail for item-->
        <div class="icon-item">

            <!--If is file or image, then link item-->
            <span v-if="isFile" class="file-icon-text">{{ file.mimetype }}</span>

            <!--Folder thumbnail-->
            <FontAwesomeIcon v-if="isFile" class="file-icon" icon="file" />

            <!--Image thumbnail-->
            <img v-if="isImage" class="image" :src="file.thumbnail" :alt="file.name" />

            <!--Else show only folder icon-->
            <FontAwesomeIcon v-if="isFolder" class="folder-icon"  icon="folder" />
        </div>

        <!--Name-->
        <div class="item-name">

            <!--Name-->
            <span class="name" >{{ file.name }}</span>

            <!--Other attributes-->
            <span v-if="! isFolder" class="item-size">{{ file.filesize }}, {{ file.created_at }}</span>

            <span v-if="isFolder" class="item-length">{{ file.items == 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems) }}, {{ file.created_at }}</span>
        </div>
    </div>
</template>

<script>

export default {
	name: 'FileListItemThumbnail',
    props: ['file'],
    computed: {
        isFolder() {
            return this.file.type === 'folder'
        },
        isFile() {
            return this.file.type !== 'folder' && this.file.type !== 'image'
        },
        isImage() {
            return this.file.type === 'image'
        }
    },
}
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .file-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        @include transition(150ms);
        cursor: pointer;

        &:hover {
            background: rgba($theme, .1);

            .item-name .name {
                color: $theme;
            }
        }

        .item-name {
            display: block;
            margin-left: 10px;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            .item-size,
            .item-length {
                @include font-size(11);
                font-weight: 400;
                color: $text-muted;
                display: block;
            }

            .name {
                white-space: nowrap;
            }

            .name {
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

            .file-icon-text {
                top: 40%;
                @include font-size(9);
                line-height: 1;
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

    @media (prefers-color-scheme: dark) {

        .file-item {

            .icon-item .file-icon {

                path {
                    fill: $dark_mode_background;
                    stroke: #2F3C54;
                }
            }

            &:hover,
            &.is-clicked {
                background: rgba($theme, .1);


            }

            .item-name .name {
                color: $dark_mode_text_primary;
            }
        }
    }

    @media (max-width: 690px) and (prefers-color-scheme: dark){
        .file-item {

            .icon-item .file-icon {

                path {
                    fill: $dark_mode_foreground;
                }
            }
        }
    }
</style>
