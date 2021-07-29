<template>
    <MenuMobile name="file-menu">
        <ThumbnailItem class="item-thumbnail" :item="clipboard[0]" info="metadata" />

        <!--Trash location-->
        <MenuMobileGroup v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')">
            <OptionGroup v-if="clipboard[0]">
                <Option @click.native="$restoreFileOrFolder(clipboard[0])" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="$deleteFileOrFolder(clipboard[0])" :title="$t('context_menu.delete')" icon="delete" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Shared location-->
        <MenuMobileGroup v-if="$isThisLocation(['shared']) && $checkPermission('master')">
            <OptionGroup v-if="clipboard[0] && isFolder">
                <Option @click.native="addToFavourites" :title="favouritesTitle" icon="star" />
            </OptionGroup>

            <OptionGroup v-if="clipboard[0]">
                <Option @click.native="$renameFileOrFolder(clipboard[0])" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$shareFileOrFolder(clipboard[0])" :title="clipboard[0].shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
                <Option @click.native="$deleteFileOrFolder(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for user-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master')">
            <OptionGroup v-if="clipboard[0] && isFolder">
                <Option @click.native="addToFavourites" :title="favouritesTitle" icon="star" />
            </OptionGroup>

            <OptionGroup v-if="clipboard[0]">
                <Option @click.native="$renameFileOrFolder(clipboard[0])" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(clipboard[0])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$shareFileOrFolder(clipboard[0])" :title="clipboard[0].shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
                <Option @click.native="$deleteFileOrFolder(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for guest-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor')">
            <OptionGroup>
                <Option v-if="clipboard[0]" @click.native="$renameFileOrFolder(clipboard[0])" :title="$t('context_menu.rename')" icon="rename" />
                <Option v-if="clipboard[0]" @click.native="$moveFileOrFolder(clipboard[0])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MenuMobileGroup>

        <!--Base location for guest with visit permission-->
        <MenuMobileGroup v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor')">
            <OptionGroup>
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
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
            'clipboard',
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
            return this.favourites.find(el => el.id === this.clipboard[0].id)
        },
        isFile() {
            return !this.isImage && !this.isFolder
        },
        isImage() {
            return this.clipboard[0] && this.clipboard[0].type === 'image'
        },
        isFolder() {
            return this.clipboard[0] && this.clipboard[0].type === 'folder'
        }
    },
    data() {
        return {
            isVisible: false,
        }
    },
    methods: {
        addToFavourites() {
            if (this.favourites && !this.favourites.find(el => el.id === this.clipboard[0].id)) {
                this.$store.dispatch('addToFavourites', this.clipboard[0])
            } else {
                this.$store.dispatch('removeFromFavourites', this.clipboard[0])
            }
        },
		downloadItem() {
			if (this.clipboard.length > 1 || (this.clipboard.length === 1 && this.clipboard[0].type === 'folder'))
				this.$store.dispatch('downloadZip')
			else {
				this.$downloadFile(this.clipboard[0].file_url, this.clipboard[0].name + '.' + this.clipboard[0].mimetype)
			}
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
