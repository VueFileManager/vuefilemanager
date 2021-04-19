<template>
    <div class="file-info-content" v-if="clipboard.length === 1">
        <div class="file-headline" spellcheck="false">
            <FilePreviewDetail/>

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
                    <span ref="name" class="name">{{ clipboard[0].name }}</span>
                    <span class="mimetype text-theme" v-if="clipboard[0].mimetype">.{{ clipboard[0].mimetype }}</span>
                </div>
            </div>
        </div>

        <!--Info list-->
        <ListInfo>
            <ListInfoItem v-if="clipboard[0].filesize"
                          :title="$t('file_detail.size')"
                          :content="clipboard[0].filesize">
            </ListInfoItem>

            <ListInfoItem v-if="$checkPermission(['master']) && clipboard[0].author !== 'user'"
                          :title="$t('file_detail.author')"
                          :content="$t('file_detail.author_participant')">
            </ListInfoItem>

            <ListInfoItem
                    :title="$t('file_detail.created_at')"
                    :content="clipboard[0].created_at">
            </ListInfoItem>

            <ListInfoItem v-if="$checkPermission(['master'])"
                          :title="$t('file_detail.where')">
                <div class="action-button" @click="moveItem">
                    <span>{{ clipboard[0].parent ? clipboard[0].parent.name : $t('locations.home') }}</span>
                    <edit-2-icon size="10" class="edit-icon"></edit-2-icon>
                </div>
            </ListInfoItem>
            <ListInfoItem v-if="$checkPermission('master') && clipboard[0].shared"
                          :title="$t('file_detail.shared')">
                <div class="action-button" @click="shareItemOptions">
                    <span>{{ sharedInfo }}</span>
                    <edit-2-icon size="10" class="edit-icon"></edit-2-icon>
                </div>
                <div class="sharelink">
                    <lock-icon v-if="isLocked" @click="shareItemOptions" class="lock-icon" size="17"></lock-icon>
                    <unlock-icon v-if="! isLocked" @click="shareItemOptions" class="lock-icon" size="17"></unlock-icon>
                    <CopyInput class="copy-sharelink" size="small" :item="clipboard[0]"/>
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
    import FilePreviewDetail from '@/components/Others/FilePreviewDetail'
    import ImageMetaData from '@/components/FilesView/ImageMetaData'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import ListInfo from '@/components/Others/ListInfo'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"

    export default {
        name: 'FileInfoPanel',
        components: {
            FilePreviewDetail,
            ImageMetaData,
            ListInfoItem,
            ListInfo,
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
            ...mapGetters(['clipboard', 'permissionOptions']),
            fileType() {
                return this.clipboard[0].type
            },
            canShowMetaData() {
                return this.clipboard[0].metadata && this.clipboard[0].metadata.ExifImageWidth
            },
            sharedInfo() {
                // Get permission title
                let title = this.permissionOptions.find(option => {
                    return option.value === this.clipboard[0].shared.permission
                })

                return title ? this.$t(title.label) : this.$t('shared.can_download')
            },
            sharedIcon() {
                switch (this.clipboard[0].shared.permission) {
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
                return this.clipboard[0].shared.is_protected
            }
        },
        methods: {
            shareItemOptions() {
                // Open share item popup
                events.$emit('popup:open', {name: 'share-edit', item: this.clipboard[0]})
            },
            moveItem() {
                // Move item fire popup
               events.$emit("popup:open", { name: "move", item: this.clipboard});
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

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
    }
</style>
