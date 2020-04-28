<template>
    <div class="file-info-content" v-if="fileInfoDetail">
        <div class="file-headline" spellcheck="false">

            <FilePreview />

            <!--File info-->
            <div class="flex">
                <div class="icon">
                    <div class="icon-preview">
                        <FontAwesomeIcon :icon="filePreviewIcon"></FontAwesomeIcon>
                    </div>
                </div>
                <div class="file-info">
					<span ref="name" class="name">{{ fileInfoDetail.name }}</span>
                    <span class="mimetype" v-if="fileInfoDetail.mimetype">{{ fileInfoDetail.mimetype }}</span>
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
            <li v-if="$checkPermission(['master']) && fileInfoDetail.user_scope !== 'master'" class="list-info-item">
                <b>Author</b>
                <span>Public Participant</span>
            </li>

            <!--Latest change-->
            <li class="list-info-item">
                <b>{{ $t('file_detail.created_at') }}</b>
                <span>{{ fileInfoDetail.created_at }}</span>
            </li>

            <!--Parent-->
            <li v-if="$checkPermission(['master'])" class="list-info-item">
                <b>{{ $t('file_detail.where') }}</b>
                <div class="action-button" @click="moveItem">
                    <FontAwesomeIcon class="icon" icon="pencil-alt" />
                    <span>{{ fileInfoDetail.parent ? fileInfoDetail.parent.name : $t('locations.home') }}</span>
                </div>
            </li>

            <!--Parent-->
            <li v-if="$checkPermission('master') && fileInfoDetail.shared" class="list-info-item">
                <b>Shared</b>
                <div class="action-button" @click="shareItemOptions">
                    <FontAwesomeIcon class="icon" :icon="sharedIcon" />
                    <span>{{ sharedInfo }}</span>
                </div>
                <div class="sharelink">
                    <FontAwesomeIcon class="lock-icon" :icon="lockIcon" @click="shareItemOptions" />
                    <CopyInput class="copy-sharelink" size="small" :value="fileInfoDetail.shared.link" />
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import FilePreview from '@/components/FilesView/FilePreview'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"

    export default {
        name: 'FileInfoPanel',
        components: {
            FilePreview,
            CopyInput,
        },
        computed: {
            ...mapGetters(['fileInfoDetail']),
            filePreviewIcon() {
                switch (this.fileInfoDetail.type) {
                    case 'folder':
                        return 'folder'
                    break;
                    case 'file':
                        return 'file'
                    break;
                    case 'image':
                        return 'file-image'
                    break;
                    case 'video':
                        return 'file-video'
                    break;
                    case 'file':
                        return 'file-audio'
                    break;
                }
            },
            sharedInfo() {
                switch (this.fileInfoDetail.shared.permission) {
                    case 'editor':
                        return 'Can edit and upload files'
                    break
                    case 'visitor':
                        return 'Can only view and download'
                    break
                    default:
                        return 'Can download file'
                }
            },
            sharedIcon() {
                switch (this.fileInfoDetail.shared.permission) {
                    case 'editor':
                        return 'user-edit'
                    break
                    case 'visitor':
                        return 'user'
                    break
                    default:
                        return 'download'
                }
            },
            lockIcon() {
                return this.fileInfoDetail.shared.protected ? 'lock' : 'lock-open'
            }
        },
        methods: {
            shareItemOptions() {
                // Open share item popup
                events.$emit('popup:open', {name: 'share-edit', item: this.fileInfoDetail})
            },
            moveItem() {
                // Move item fire popup
                events.$emit('popup:open', {name: 'move', item: this.fileInfoDetail})

            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .file-info-content {
        padding-bottom: 20px;
    }

    .file-headline {
        background: $light_background;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 8px;

        .flex {
            display: flex;
            align-items: flex-start;
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
                line-height: 1.4;
                display: block;
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
            padding-top: 15px;

            &:first-child {
                padding-top: 0;
            }

            .action-button {
                cursor: pointer;

                .icon {
                    @include font-size(10);
                    display: inline-block;
                    margin-right: 2px;

                    path {
                        fill: $theme_light;
                    }
                }
            }

            b {
                display: block;
                @include font-size(13);
                color: $theme;
                margin-bottom: 2px;
            }

            span {
                display: inline-block;
                @include font-size(14);
                font-weight: bold;
                color: $text;
            }
        }
    }

    .sharelink {
        display: flex;
        width: 100%;
        align-items: center;
        margin-top: 10px;

        .lock-icon {
            @include font-size(10);
            display: inline-block;
            width: 10px;
            margin-right: 9px;
            cursor: pointer;

            path {
                fill: $text;
            }

            &:hover {

                path {
                    fill: $theme;
                }
            }
        }

        .copy-sharelink {
            width: 100%;
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

                .action-button {

                    .icon {
                        color: $dark_mode_text_primary;
                    }
                }
            }
        }
    }
</style>
