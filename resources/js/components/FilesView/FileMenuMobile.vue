<template>
    <MenuMobile name="file-menu">
        <ThumbnailItem class="item-thumbnail" :item="fileInfoDetail[0]" info="metadata" />

        <!--Trash location-->
        <MenuMobileGroup v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')">
            <OptionGroup v-if="fileInfoDetail[0]">
                <Option @click.native="restoreItem" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="delete" />
            </OptionGroup>

            <OptionGroup>
                <Option v-if="!isFolder" @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="isFolder" @click.native="downloadFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Shared location-->
        <MenuMobileGroup v-if="$isThisLocation(['shared']) && $checkPermission('master')">
            <OptionGroup v-if="fileInfoDetail[0] && isFolder">
                <Option @click.native="addToFavourites" :title="favouritesTitle" icon="star" />
            </OptionGroup>

            <OptionGroup v-if="fileInfoDetail[0]">
                <Option @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="shareItem" :title="fileInfoDetail[0].shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option v-if="!isFolder" @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="isFolder" @click.native="downloadFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for user-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master')">
            <OptionGroup v-if="fileInfoDetail[0] && isFolder">
                <Option @click.native="addToFavourites" :title="favouritesTitle" icon="star" />
            </OptionGroup>

            <OptionGroup v-if="fileInfoDetail[0]">
                <Option @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="moveItem" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="shareItem" :title="fileInfoDetail[0].shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option v-if="!isFolder" @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="isFolder" @click.native="downloadFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for guest-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor')">
            <OptionGroup>
                <Option v-if="fileInfoDetail[0]" @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option v-if="fileInfoDetail[0]" @click.native="moveItem" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option v-if="!isFolder" @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="isFolder" @click.native="downloadFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for guest with visit permission-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor')">
            <OptionGroup>
                <Option v-if="!isFolder" @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="isFolder" @click.native="downloadFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'
import ThumbnailItem from '@/components/Others/ThumbnailItem'
import OptionGroup from '@/components/FilesView/OptionGroup'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'FileMenuMobile',
    components: {
        MenuMobileGroup,
        MenuMobile,
        ThumbnailItem,
        OptionGroup,
        Option,
    },
    computed: {
        ...mapGetters([
            'fileInfoDetail',
            'user',
        ]),
        favourites() {
            return this.user.data.relationships.favourites.data.attributes.folders
        },
        favouritesTitle() {
            return this.isInFavourites
                ? this.$t('context_menu.remove_from_favourites')
                : this.$t('context_menu.add_to_favourites')
        },
        isInFavourites() {
            return this.favourites.find(el => el.id === this.fileInfoDetail[0].id)
        },
        isFile() {
            return !this.isImage && !this.isFolder
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
        }
    },
    methods: {
        downloadFolder() {
            this.$store.dispatch('downloadFolder', this.fileInfoDetail[0])
        },
        moveItem() {
            events.$emit('popup:open', {name: 'move', item: [this.fileInfoDetail[0]]})
        },
        shareItem() {
            let event = this.fileInfoDetail[0].shared
                ? 'share-edit'
                : 'share-create'

            events.$emit('popup:open', {
                name: event,
                item: this.fileInfoDetail[0]
            })
        },
        addToFavourites() {
            if (this.favourites && !this.favourites.find(el => el.id === this.fileInfoDetail[0].id)) {
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
        restoreItem() {
            this.$store.dispatch('restoreItem', this.fileInfoDetail[0])
        },
        renameItem() {
            events.$emit('popup:open', {name: 'rename-item', item: this.fileInfoDetail[0]})
        },
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

.item-thumbnail {
    padding: 20px 20px 10px;
    margin-bottom: 0;
}
</style>
