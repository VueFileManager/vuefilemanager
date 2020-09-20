<template>
    <div class="file-info-content" v-if="fileInfoDetail">
        <div class="file-headline" spellcheck="false">
            <FilePreview/>

            <!--File info-->
            <div class="flex">
                <div class="icon">
                    <div class="icon-preview">
                        <image-icon v-if="fileType === 'image'" size="21"></image-icon>
                        <video-icon v-if="fileType === 'video'" size="21"></video-icon>
                        <folder-icon v-if="fileType === 'folder'" size="21"></folder-icon>
                        <file-icon v-if="fileType === 'file'" size="21"></file-icon>
                    </div>
                </div>
                <div class="file-info">
                    <span ref="name" class="name">{{ fileInfoDetail.name }}</span>
                    <span class="mimetype" v-if="fileInfoDetail.mimetype">.{{ fileInfoDetail.mimetype }}</span>
                </div>
            </div>
        </div>

        <!--Info list-->
        <ListInfo>
            <ListInfoItem v-if="fileInfoDetail.filesize"
                          :title="$t('file_detail.size')"
                          :content="fileInfoDetail.filesize">
            </ListInfoItem>

            <ListInfoItem v-if="$checkPermission(['master']) && fileInfoDetail.user_scope !== 'master'"
                          :title="$t('file_detail.author')"
                          :content="$t('file_detail.author_participant')">
            </ListInfoItem>

            <ListInfoItem
                    :title="$t('file_detail.created_at')"
                    :content="fileInfoDetail.created_at">
            </ListInfoItem>

            <ListInfoItem v-if="$checkPermission(['master'])"
                          :title="$t('file_detail.where')">
                <div class="action-button" @click="moveItem">
                    <span>{{ fileInfoDetail.parent ? fileInfoDetail.parent.name : $t('locations.home') }}</span>
                    <edit-2-icon size="10" class="edit-icon"></edit-2-icon>
                </div>
            </ListInfoItem>
            <ListInfoItem v-if="$checkPermission('master') && fileInfoDetail.shared"
                          :title="$t('file_detail.shared')">
                <div class="action-button" @click="shareItemOptions">
                    <span>{{ sharedInfo }}</span>
                    <edit-2-icon size="10" class="edit-icon"></edit-2-icon>
                </div>
                <div class="sharelink">
                    <lock-icon v-if="isLocked" @click="shareItemOptions" class="lock-icon" size="17"></lock-icon>
                    <unlock-icon v-if="! isLocked" @click="shareItemOptions" class="lock-icon" size="17"></unlock-icon>
                    <CopyInput class="copy-sharelink" size="small" :value="fileInfoDetail.shared.link"/>
                </div>
            </ListInfoItem>

            <ListInfoItem v-if="canShowMetaData" :title="$t('file_detail_meta.meta_data')">
                <ImageMetaData />
            </ListInfoItem>
        </ListInfo>
    </div>
</template>

<script>
    import {Edit2Icon, LockIcon, UnlockIcon, ImageIcon, VideoIcon, FolderIcon, FileIcon} from 'vue-feather-icons'
    import ImageMetaData from '@/components/FilesView/ImageMetaData'
    import FilePreview from '@/components/FilesView/FilePreview'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import ListInfo from '@/components/Others/ListInfo'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"

    export default {
        name: 'FileInfoPanel',
        components: {
            ImageMetaData,
            ListInfoItem,
            ListInfo,
            FilePreview,
            FolderIcon,
            UnlockIcon,
            VideoIcon,
            CopyInput,
            ImageIcon,
            FileIcon,
            Edit2Icon,
            LockIcon,
        },
        computed: {
            ...mapGetters(['fileInfoDetail', 'permissionOptions']),
            fileType() {
                return this.fileInfoDetail.type
            },
            canShowMetaData() {
                return this.fileInfoDetail.metadata && this.fileInfoDetail.metadata.ExifImageWidth
            },
            sharedInfo() {
                // Get permission title
                let title = this.permissionOptions.find(option => {
                    return option.value === this.fileInfoDetail.shared.permission
                })

                return title ? title.label : this.$t('shared.can_download')
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
            isLocked() {
                return this.fileInfoDetail.shared.protected
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
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .file-info-content {
        padding-bottom: 20px;
    }

    .file-headline {
        margin-bottom: 20px;
        border-radius: 8px;

        .flex {
            display: flex;
            align-items: flex-start;
        }

        .icon-preview {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            text-align: center;
            cursor: pointer;
            white-space: nowrap;
            outline: none;
            border: none;
        }

        .file-info {
            padding-left: 10px;
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
                @include font-size(12);
                font-weight: 600;
                color: $theme;
                display: block;
            }
        }
    }

    .sharelink {
        display: flex;
        width: 100%;
        align-items: center;
        margin-top: 10px;

        .lock-icon {
            display: inline-block;
            width: 15px;
            margin-right: 9px;
            cursor: pointer;
        }

        .copy-sharelink {
            width: 100%;
        }
    }

    @media (prefers-color-scheme: dark) {

        .file-headline {

            .file-info {

                .name {
                    color: $dark_mode_text_primary;
                }
            }
        }

        .sharelink {

            .lock-icon {

                &:hover {

                    path, rect {
                        stroke: $theme;
                    }
                }
            }
        }
    }
</style>
