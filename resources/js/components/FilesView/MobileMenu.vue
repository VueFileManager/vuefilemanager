<template>
    <div class="options-wrapper">
        <transition name="context-menu">
            <div v-if="isVisible" ref="contextmenu" class="options" @click="closeAndResetContextMenu">

                <div class="menu-wrapper">

                    <!--Item Thumbnail-->
                    <ThumbnailItem class="item-thumbnail" :item="fileInfoDetail[0]" info="metadata"/>

                    <!--Mobile for trash location-->
                    <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" class="menu-options">
                        <ul class="menu-option-group">
                            <li class="menu-option" @click="$store.dispatch('restoreItem', fileInfoDetail[0])" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <life-buoy-icon size="17"></life-buoy-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.restore') }}
                                </div>
                            </li>
                            <li class="menu-option delete" @click="deleteItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <trash-2-icon size="17"></trash-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.delete') }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group" >
                            <li class="menu-option" @click="downloadItem" v-if="!isFolder">
                                <div class="icon">
                                    <download-cloud-icon size="17"></download-cloud-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.download') }}
                                </div>
                            </li>

                             <li class="menu-option" @click="downloadFolder" v-if="isFolder">
                                <div class="icon">
                                    <paperclip-icon size="17"></paperclip-icon>
                                </div>
                                <div class="text-label">
                                   {{ $t('context_menu.zip_folder') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!--Mobile for Base location-->
                    <div v-if="$isThisLocation(['shared']) && $checkPermission('master')" class="menu-options">
                        <ul class="menu-option-group">
                            <li class="menu-option" @click="addToFavourites" v-if="fileInfoDetail[0] && isFolder">
                                <div class="icon">
                                    <star-icon size="17"></star-icon>
                                </div>
                                <div class="text-label">
                                    {{
                                        isInFavourites
                                            ? $t('context_menu.remove_from_favourites')
                                            : $t('context_menu.add_to_favourites')
                                    }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group">
                            <li class="menu-option" @click="renameItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <edit-2-icon size="17"></edit-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.rename') }}
                                </div>
                            </li>
                            <li class="menu-option" @click="shareItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <link-icon size="17"></link-icon>
                                </div>
                                <div class="text-label">
                                    {{
                                        fileInfoDetail[0].shared
                                            ? $t('context_menu.share_edit')
                                            : $t('context_menu.share')
                                    }}
                                </div>
                            </li>
                            <li class="menu-option delete" @click="deleteItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <trash-2-icon size="17"></trash-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.delete') }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group">
                            <li class="menu-option" @click="downloadItem" v-if="!isFolder">
                                <div class="icon">
                                    <download-cloud-icon size="17"></download-cloud-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.download') }}
                                </div>
                            </li>

                             <li class="menu-option" @click="downloadFolder" v-if="isFolder">
                                <div class="icon">
                                    <paperclip-icon size="17"></paperclip-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.zip_folder') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!--Mobile for Base location-->
                    <div v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master')" class="menu-options">
                        <ul class="menu-option-group" v-if="fileInfoDetail[0] && isFolder">
                            <li class="menu-option" @click="addToFavourites">
                                <div class="icon">
                                    <star-icon size="17"></star-icon>
                                </div>
                                <div class="text-label">
                                    {{
                                        isInFavourites
                                            ? $t('context_menu.remove_from_favourites')
                                            : $t('context_menu.add_to_favourites')
                                    }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group">
                            <li class="menu-option" @click="renameItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <edit-2-icon size="17"></edit-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.rename') }}
                                </div>
                            </li>
                            <li class="menu-option" @click="moveItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <corner-down-right-icon size="17"></corner-down-right-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.move') }}
                                </div>
                            </li>
                            <li class="menu-option" @click="shareItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <link-icon size="17"></link-icon>
                                </div>
                                <div class="text-label">
                                    {{
                                        fileInfoDetail[0].shared
                                            ? $t('context_menu.share_edit')
                                            : $t('context_menu.share')
                                    }}
                                </div>
                            </li>
                            <li class="menu-option delete" @click="deleteItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <trash-2-icon size="17"></trash-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.delete') }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group">
                            <li class="menu-option" @click="downloadItem" v-if="!isFolder">
                                <div class="icon">
                                    <download-cloud-icon size="17"></download-cloud-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.download') }}
                                </div>
                            </li>

                            <li class="menu-option" @click="downloadFolder" v-if="isFolder">
                                <div class="icon">
                                    <paperclip-icon size="17"></paperclip-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.zip_folder') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!--Mobile for Base location with EDITOR permission-->
                    <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor')" class="menu-options">
                        <ul class="menu-option-group">
                            <li class="menu-option" @click="renameItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <edit-2-icon size="17"></edit-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.rename') }}
                                </div>
                            </li>
                            <li class="menu-option" @click="moveItem" v-if="fileInfoDetail[0]">
                                <div class="icon">
                                    <corner-down-right-icon size="17"></corner-down-right-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.move') }}
                                </div>
                            </li>
                            <li class="menu-option" @click="deleteItem">
                                <div class="icon">
                                    <trash-2-icon size="17"></trash-2-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.delete') }}
                                </div>
                            </li>
                        </ul>

                        <ul class="menu-option-group">
                            <li class="menu-option" @click="downloadItem" v-if="!isFolder">
                                <div class="icon">
                                    <download-cloud-icon size="17"></download-cloud-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.download') }}
                                </div>
                            </li>

                             <li class="menu-option" @click="downloadFolder" v-if="isFolder">
                                <div class="icon">
                                    <paperclip-icon size="17"></paperclip-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.zip_folder') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!--Mobile for Base location with VISITOR permission-->
                    <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor')" class="menu-options">
                        <ul class="menu-option-group">
                            <li class="menu-option" @click="downloadItem" v-if="!isFolder">
                                <div class="icon">
                                    <download-cloud-icon size="17"></download-cloud-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.download') }}
                                </div>
                            </li>

                            <li class="menu-option" @click="downloadFolder" v-if="isFolder">
                                <div class="icon">
                                    <paperclip-icon size="17"></paperclip-icon>
                                </div>
                                <div class="text-label">
                                    {{ $t('context_menu.zip_folder') }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div v-show="isVisible" class="vignette" @click="closeAndResetContextMenu"></div>
        </transition>
    </div>
</template>

<script>
import ThumbnailItem from '@/components/Others/ThumbnailItem'

import {
    CornerDownRightIcon,
    DownloadCloudIcon,
    FolderPlusIcon,
    PaperclipIcon,
    LifeBuoyIcon,
    Trash2Icon,
    Edit2Icon,
    TrashIcon,
    StarIcon,
    LinkIcon,
    EyeIcon
} from 'vue-feather-icons'
import { events } from '@/bus'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileMenu',
    components: {
        CornerDownRightIcon,
        DownloadCloudIcon,
        FolderPlusIcon,
        PaperclipIcon,
        ThumbnailItem,
        LifeBuoyIcon,
        Trash2Icon,
        Edit2Icon,
        TrashIcon,
        LinkIcon,
        StarIcon,
        EyeIcon
    },
    computed: {
        ...mapGetters(['fileInfoDetail', 'user']),
        favourites() {
            return this.user.relationships.favourites.data.attributes.folders
        },
        isInFavourites() {
            return this.favourites.find(
                (el) => el.unique_id == this.fileInfoDetail[0].unique_id
            )
        },
        isFile() {
            return (
                this.fileInfoDetail[0] &&
                this.fileInfoDetail[0].type !== 'folder' &&
                this.fileInfoDetail[0] &&
                this.fileInfoDetail[0].type !== 'image'
            )
        },
        isImage() {
            return this.fileInfoDetail[0] && this.fileInfoDetail[0].type === 'image'
        },
        isFolder() {
            return this.fileInfoDetail[0] && this.fileInfoDetail[0].type === 'folder'
        }
    },
    data() {
        return {
            isVisible: false,
            showFromMediaPreview: false
        }
    },
    methods: {
        downloadFolder(){
            this.$store.dispatch( 'downloadFolder' , this.fileInfoDetail[0] )
        },
        moveItem() {
            events.$emit('popup:open', { name: 'move', item: [this.fileInfoDetail[0]] })
        },
        shareItem() {
            if (this.fileInfoDetail[0].shared) {
                // Open share item popup
                events.$emit('popup:open', {
                    name: 'share-edit',
                    item: this.fileInfoDetail[0]
                })
            } else {
                // Open share item popup
                events.$emit('popup:open', {
                    name: 'share-create',
                    item: this.fileInfoDetail[0]
                })
            }
        },
        addToFavourites() {
            if (
                this.favourites &&
                !this.favourites.find(
                    (el) => el.unique_id == this.fileInfoDetail[0].unique_id
                )
            ) {
                this.$store.dispatch('addToFavourites', this.fileInfoDetail[0])
            } else {
                this.$store.dispatch('removeFromFavourites', this.fileInfoDetail[0])
            }
        },
        downloadItem() {
            this.$downloadFile(
                this.fileInfoDetail[0].file_url,
                this.fileInfoDetail[0].name + '.' + this.fileInfoDetail[0].mimetype
            )
        },
        deleteItem() {
            this.$store.dispatch('deleteItem')
        },
        renameItem() {
            events.$emit('popup:open', { name: 'rename-item', item: this.fileInfoDetail[0] })
        },
        closeAndResetContextMenu() {
            //If emit to show menu coming from MediaFullPreview dont reset data
            this.isVisible = false
            this.showFromMediaPreview = false
            events.$emit('hide:mobile-navigation')
        }
    },
    created() {
        events.$on('mobileMenu:show', showFromMedia => {
            // If emit come from MediaFullPreview
            if (showFromMedia) {
                this.isVisible = true
                this.showFromMediaPreview = true
            } else {
                this.isVisible = !this.isVisible
            }
        })

        // Hide mobile menu
        events.$on('mobileMenu:hide', () => {
            this.isVisible = false
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.mobile-selected-menu {
    display: flex;
    margin-left: 15px;
    margin-right: 15px;

    .close-icon {
        margin-left: auto !important;
    }
}

.menu-option {
    display: flex;
    align-items: center;

    .icon {
        margin-right: 20px;
        line-height: 0;
    }

    .text-label {
        @include font-size(16);
    }
}

.vignette {
    background: rgba(0, 0, 0, 0.35);
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 9;
    cursor: pointer;
    opacity: 1;
}

.options {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99;
    overflow: hidden;
    background: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;

    &.showed {
        display: block;
    }

    .item-thumbnail {
        padding: 20px 20px 10px;
        margin-bottom: 0px;
    }

    .menu-options {
        margin-top: 10px;
        list-style: none;
        width: 100%;

        .menu-option-group {
            padding: 5px 0;
            border-bottom: 1px solid $light_mode_border;

            &:first-child {
                padding-top: 0;
            }

            &:last-child {
                padding-bottom: 0;
                border-bottom: none;
            }
        }

        .menu-option {
            font-weight: 700;
            letter-spacing: 0.15px;
            @include font-size(14);
            cursor: pointer;
            width: 100%;
            padding: 17px 20px;
            text-align: center;

            &:last-child {
                border: none;
            }
        }
    }
}

@media (prefers-color-scheme: dark) {
    .vignette {
        background: $dark_mode_vignette;
    }

    .options {
        background: $dark_mode_foreground;

        .menu-options {
            background: $dark_mode_foreground;

            .menu-option-group {
                border-color: $dark_mode_border_color;
            }

            .menu-option {
                color: $dark_mode_text_primary;
            }
        }
    }
}

// Transition
.context-menu-enter-active,
.fade-enter-active {
    transition: all 200ms;
}

.context-menu-leave-active,
.fade-leave-active {
    transition: all 200ms;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.context-menu-enter,
.context-menu-leave-to {
    opacity: 0;
    transform: translateY(100%);
}

.context-menu-leave-active {
    position: absolute;
}
</style>
