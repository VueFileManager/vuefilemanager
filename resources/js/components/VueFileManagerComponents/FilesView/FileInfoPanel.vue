<template>
    <div v-if="fileInfoDetail">
        <div class="file-headline" spellcheck="false">
            <!--Image thumbnail-->
            <div v-if="fileInfoDetail.type == 'image'" class="image-preview">
                <img
                        @dblclick="$openImageOnNewTab(fileInfoDetail.file_url)"
                        :src="fileInfoDetail.thumbnail"
                        :alt="fileInfoDetail.name"
                />
            </div>

            <!--File info-->
            <div class="flex">
                <div class="icon">
                    <div class="icon-preview" @dblclick="getItemAction">
                        <FontAwesomeIcon
                                v-if="fileInfoDetail.type == 'folder'"
                                icon="folder"
                        ></FontAwesomeIcon>
                        <FontAwesomeIcon
                                v-if="fileInfoDetail.type == 'file'"
                                icon="file"
                        ></FontAwesomeIcon>
                        <FontAwesomeIcon
                                v-if="fileInfoDetail.type == 'image'"
                                icon="file-image"
                        ></FontAwesomeIcon>
                    </div>
                </div>
                <div class="file-info">
					<span ref="name" contenteditable="false" class="name">{{
						fileInfoDetail.name
					}}</span>
                    <span class="mimetype">{{ fileInfoDetail.mimetype }}</span>
                </div>
            </div>
        </div>

        <!--Info list-->
        <ul class="list-info">
            <!--Filesize-->
            <li v-if="fileInfoDetail.filesize" class="list-info-item">
                <b>{{ $t('file_detail.size') }}</b>
                <span>{{ fileInfoDetail.filesize }}</span>
            </li>

            <!--Latest change-->
            <li v-if="fileInfoDetail.created_at" class="list-info-item">
                <b>{{ $t('file_detail.created_at') }}</b>
                <span>{{ fileInfoDetail.created_at }}</span>
            </li>

            <!--Parent-->
            <li class="list-info-item">
                <b>{{ $t('file_detail.where') }}</b>
                <div class="action-button" @click="moveItem">
                    <FontAwesomeIcon class="icon" icon="pencil-alt" />
                    <span>{{ fileInfoDetail.parent ? fileInfoDetail.parent.name : $t('locations.home') }}</span>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from "@/bus"

    export default {
        name: 'FilesInfoPanel',
        computed: {
            ...mapGetters(['fileInfoDetail'])
        },
        methods: {
            moveItem() {
                // Move item fire popup
                events.$emit('popup:move-item', this.fileInfoDetail);
            },
            getItemAction() {
                // Open image on new tab
                if (this.fileInfoDetail.type == 'image') {
                    this.$openImageOnNewTab(this.fileInfoDetail.file_url)
                }

                // Download file
                if (this.fileInfoDetail.type == 'file') {
                    this.$downloadFile(
                        this.fileInfoDetail.file_url,
                        this.fileInfoDetail.name +
                        '.' +
                        this.fileInfoDetail.mimetype
                    )
                }

                // Open folder
                if (this.fileInfoDetail.type == 'folder') {
                    // Todo: open folder
                }
            },
            changeItemName: debounce(function (e) {
                // Prevent submit empty string
                if (e.target.innerText === '') return

                this.$store.dispatch('changeItemName', {
                    unique_id: this.fileInfoDetail.unique_id,
                    type: this.fileInfoDetail.type,
                    name: e.target.innerText
                })
            }, 300)
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .file-headline {
        background: $light_background;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 8px;

        .image-preview {
            width: 100%;
            display: block;
            margin-bottom: 7px;

            img {
                border-radius: 4px;
                overflow: hidden;
                width: 100%;
                object-fit: cover;
                cursor: pointer;
            }
        }

        .flex {
            display: flex;
            align-items: top;
        }

        .icon-preview {
            height: 42px;
            width: 42px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: white;
            text-align: center;
            cursor: pointer;
            white-space: nowrap;
            outline: none;
            border: none;

            /deep/ svg {
                @include font-size(22);

                path {
                    fill: $theme;
                }
            }

            &:hover {
                .icon path {
                    fill: $theme;
                }
            }
        }

        .file-info {
            padding-left: 12px;
            width: 100%;
            word-break: break-all;

            .name {
                @include font-size(14);
                font-weight: 700;
                color: $text;
            }

            .mimetype {
                @include font-size(14);
                font-weight: 600;
                color: $theme;
                display: block;
            }
        }
    }

    .list-info {
        padding-left: 12px;

        .list-info-item {
            display: block;
            padding-top: 20px;

            &:first-child {
                padding-top: 0;
            }

            .action-button {
                cursor: pointer;

                .icon {
                    @include font-size(11);
                    display: inline-block;
                    margin-right: 2px;
                }
            }

            b {
                display: block;
                @include font-size(13);
                color: $theme;
            }

            span {
                display: inline-block;
                @include font-size(14);
                font-weight: bold;
                color: $text;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .file-headline {
            background: $dark_mode_foreground;

            .icon-preview {
                background: $dark_mode_background;
            }

            .file-info {

                .name {
                    color: $dark_mode_text_primary;
                }
            }
        }

        .list-info {

            .list-info-item {

                span {
                    color: $dark_mode_text_primary
                }
            }
        }
    }
</style>
