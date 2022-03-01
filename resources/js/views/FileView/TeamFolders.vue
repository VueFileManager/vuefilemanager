<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item && isFolder">
                <Option
                    @click.native="$toggleFavourites(item)"
                    :title="
                        isInFavourites
                            ? $t('context_menu.remove_from_favourites')
                            : $t('context_menu.add_to_favourites')
                    "
                    icon="favourites"
                />
            </OptionGroup>

            <OptionGroup v-if="item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup v-if="item">
                <Option
                    @click.native="$shareFileOrFolder(item)"
                    :title="item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')"
                    icon="share"
                />
                <Option
                    @click.native="$updateTeamFolder(item)"
                    v-if="isFolder"
                    :title="$t('Edit Team Members')"
                    icon="users"
                />
            </OptionGroup>

            <OptionGroup v-if="item">
                <Option @click.native="$downloadSelection(item)" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('Upload')">
                <OptionUpload :title="$t('actions.upload')" type="file" :is-hover-disabled="true" />
                <OptionUpload :title="$t('actions.upload_folder')" type="folder" :is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup :title="$t('Create')">
                <Option
                    @click.stop.native="$createTeamFolder"
                    :title="$t('Create Team Folder')"
                    icon="users"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.stop.native="$createFolderByPopup"
                    :title="$t('actions.create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MobileCreateMenu>

        <MobileTeamContextMenu>
            <OptionGroup>
                <Option @click.native="$updateTeamFolder(teamFolder)" :title="$t('Edit Members')" icon="rename" />
                <Option @click.native="$dissolveTeamFolder(teamFolder)" :title="$t('Dissolve Team')" icon="trash" />
            </OptionGroup>
        </MobileTeamContextMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
                @click.native="$moveFileOrFolder(clipboard)"
                class="action-btn"
                source="move"
                :action="$t('actions.move')"
                :class="{ 'is-inactive': clipboard.length < 1 }"
            />
            <ToolbarButton
                @click.native="$deleteFileOrFolder(clipboard)"
                class="action-btn"
                source="trash"
                :class="{ 'is-inactive': clipboard.length < 1 }"
                :action="$t('actions.delete')"
            />
            <ToolbarButton
                @click.native="$downloadSelection(item)"
                class="action-btn"
                source="download"
                :action="$t('actions.download')"
            />
        </MobileMultiSelectToolbar>

        <ContextMenu>
            <template v-slot:empty-select>
                <OptionGroup v-if="!isTeamFolderHomepage">
                    <OptionUpload :title="$t('actions.upload')" type="file" />
                    <OptionUpload :title="$t('actions.upload_folder')" type="folder" />
                    <Option
                        @click.native="$createFolder"
                        :title="$t('actions.create_folder')"
                        icon="folder-plus"
                    />
                </OptionGroup>
                <OptionGroup v-if="isTeamFolderHomepage">
                    <Option @click.native="$createTeamFolder" :title="$t('Create Team Folder')" icon="users" />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup v-if="isFolder">
                    <Option
                        @click.native="$toggleFavourites(item)"
                        :title="
                            isInFavourites
                                ? $t('context_menu.remove_from_favourites')
                                : $t('context_menu.add_to_favourites')
                        "
                        icon="favourites"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$renameFileOrFolder(item)"
                        :title="$t('context_menu.rename')"
                        icon="rename"
                    />
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$shareFileOrFolder(item)"
                        :title="
                            item.data.relationships.shared ? $t('context_menu.share_edit') : $t('context_menu.share')
                        "
                        icon="share"
                    />
                    <Option
                        @click.native="$updateTeamFolder(item)"
                        v-if="isFolder"
                        :title="$t('Edit Team Members')"
                        icon="users"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
                    <Option
                        @click.native="$downloadSelection(item)"
                        :title="$t('context_menu.download')"
                        icon="download"
                    />
                </OptionGroup>
            </template>

            <template v-slot:multiple-select v-if="item">
                <OptionGroup v-if="!hasFile">
                    <Option
                        @click.native="$toggleFavourites(item)"
                        :title="
                            isInFavourites
                                ? $t('context_menu.remove_from_favourites')
                                : $t('context_menu.add_to_favourites')
                        "
                        icon="favourites"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('context_menu.download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile>
            <MobileActionButton @click.native="$openSpotlight()" icon="search">
                {{ $t('Spotlight') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-filter')" :icon="$getCurrentSectionIcon()">
                {{ $getCurrentSectionName() }}
            </MobileActionButton>
            <MobileActionButton
                @click.native="$showMobileMenu('create-list')"
                v-if="$checkPermission(['master', 'editor'])"
                icon="cloud-plus"
            >
                {{ $t('mobile.create') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
                {{ $t('context_menu.select') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </FileActionsMobile>

        <EmptyFilePage>
            <template v-if="isTeamFolderHomepage">
                <h1 class="title">
                    {{ $t('Create your Team Folder') }}
                </h1>
                <p class="description">
                    {{ $t('Collaborate on your files with your team easily by creating new team folder.') }}
                </p>
                <ButtonBase @click.native="$createTeamFolder" button-style="theme" class="m-center">
                    {{ $t('Create Team Folder') }}
                </ButtonBase>
            </template>

            <template v-if="!isTeamFolderHomepage">
                <h1 class="title">
                    {{ $t('empty_page.title') }}
                </h1>
                <p class="description">
                    {{ $t('empty_page.description') }}
                </p>
                <ButtonUpload button-style="theme">
                    {{ $t('empty_page.call_to_action') }}
                </ButtonUpload>
            </template>
        </EmptyFilePage>

        <FileBrowser />
    </div>
</template>

<script>
import MobileTeamContextMenu from '../../components/FilesView/MobileTeamContextMenu'
import EmptyFilePage from '../../components/FilesView/EmptyFilePage'
import FileActionsMobile from '../../components/FilesView/FileActionsMobile'
import MobileActionButtonUpload from '../../components/FilesView/MobileActionButtonUpload'
import MobileMultiSelectToolbar from '../../components/FilesView/MobileMultiSelectToolbar'
import MobileActionButton from '../../components/FilesView/MobileActionButton'
import MobileContextMenu from '../../components/FilesView/MobileContextMenu'
import MobileCreateMenu from '../../components/FilesView/MobileCreateMenu'
import ButtonUpload from '../../components/FilesView/ButtonUpload'
import ToolbarButton from '../../components/FilesView/ToolbarButton'
import OptionUpload from '../../components/FilesView/OptionUpload'
import FileBrowser from '../../components/FilesView/FileBrowser'
import ContextMenu from '../../components/FilesView/ContextMenu'
import OptionGroup from '../../components/FilesView/OptionGroup'
import ButtonBase from '../../components/FilesView/ButtonBase'
import Option from '../../components/FilesView/Option'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'TeamFolders',
    components: {
        MobileActionButtonUpload,
        MobileMultiSelectToolbar,
        MobileTeamContextMenu,
        MobileActionButton,
        MobileContextMenu,
        MobileCreateMenu,
        ToolbarButton,
        ButtonUpload,
        OptionUpload,
        OptionGroup,
        FileBrowser,
        ContextMenu,
        ButtonBase,
        Option,
        FileActionsMobile,
        EmptyFilePage,
    },
    computed: {
        ...mapGetters(['currentTeamFolder', 'clipboard', 'config', 'user']),
        teamFolder() {
            return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
        },
        isTeamFolderHomepage() {
            return this.$isThisRoute(this.$route, ['TeamFolders']) && !this.$route.params.id
        },
        isFolder() {
            return this.item && this.item.data.type === 'folder'
        },
        isInFavourites() {
            return this.user.data.relationships.favourites.data.find((el) => el.id === this.item.id)
        },
        hasFile() {
            return this.clipboard.find((item) => item.type !== 'folder')
        },
    },
    data() {
        return {
            item: undefined,
        }
    },
    mounted() {
        this.$store.dispatch('getTeamFolder', this.$route.params.id)

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
        events.$on('context-menu:current-folder', (folder) => (this.item = folder))

        events.$on('action:confirmed', (data) => {
            if (data.operation === 'dissolve-team-folder')
                axios
                    .delete(`/api/teams/folders/${data.id}`)
                    .then(() => {
                        if (this.$route.params.id) {
                            this.$router.push({ name: 'TeamFolders' })
                        } else {
                            this.$store.commit('REMOVE_ITEM', data.id)
                        }

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('Your Team Folder was moved into your files.'),
                        })
                    })
                    .catch(() => this.$isSomethingWrong())
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
