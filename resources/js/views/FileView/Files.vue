<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup v-if="item">
                <Option
                    @click.native="$shareFileOrFolder(item)"
                    :title="item.data.relationships.shared ? $t('edit_sharing') : $t('share')"
                    icon="share"
                />
                <Option
                    @click.native="$convertAsTeamFolder(item)"
                    v-if="isFolder"
                    :title="$t('convert_as_team_folder')"
                    icon="users"
                />
				<Option
					@click.native="$createFileRequest(item)"
					v-if="isFolder"
					:title="$t('file_request')"
					icon="upload-cloud"
				/>
            </OptionGroup>

            <OptionGroup v-if="item">
                <Option @click.native="$downloadSelection(item)" :title="$t('download')" icon="download" />
            </OptionGroup>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('frequently_used')">
                <OptionUpload
                    :title="$t('upload_files')"
                    type="file"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.stop.native="$createFolderByPopup"
                    :title="$t('create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
            <OptionGroup :title="$t('others')">
				<Option
					@click.stop.native="$openRemoteUploadPopup"
					:title="$t('remote_upload')"
					icon="remote-upload"
				/>
                <Option
                    @click.stop.native="$createTeamFolder"
                    :title="$t('create_team_folder')"
                    icon="users"
                    :is-hover-disabled="true"
                />
				<Option
					@click.native="$createFileRequest"
					:title="$t('create_file_request')"
					icon="upload-cloud"
					:is-hover-disabled="true"
				/>
            </OptionGroup>
        </MobileCreateMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
                @click.native="$moveFileOrFolder(clipboard)"
                class="mr-4"
                source="move"
                :action="$t('move')"
                :class="{ 'is-inactive': clipboard.length < 1 }"
            />
            <ToolbarButton
                @click.native="$deleteFileOrFolder()"
                class="mr-4"
                source="trash"
                :class="{ 'is-inactive': clipboard.length < 1 }"
                :action="$t('delete')"
            />
            <ToolbarButton
                @click.native="$downloadSelection(item)"
                source="download"
                :action="$t('download_item')"
            />
        </MobileMultiSelectToolbar>

        <ContextMenu>
            <template v-slot:empty-select>
                <OptionGroup>
                    <OptionUpload :title="$t('upload_files')" type="file" />
                    <OptionUpload :title="$t('upload_folder')" type="folder" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$createFolder"
                        :title="$t('create_folder')"
                        icon="create-folder"
                    />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup v-if="isFolder">
                    <Option
                        @click.native="$toggleFavourites(item)"
                        :title="
                            isInFavourites
                                ? $t('remove_favourite')
                                : $t('add_to_favourites')
                        "
                        icon="favourites"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$renameFileOrFolder(item)"
                        :title="$t('rename')"
                        icon="rename"
                    />
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$shareFileOrFolder(item)"
                        :title="
                            item.data.relationships.shared ? $t('edit_sharing') : $t('share')
                        "
                        icon="share"
                    />
                    <Option
                        @click.native="$convertAsTeamFolder(item)"
                        v-if="isFolder"
                        :title="$t('convert_as_team_folder')"
                        icon="user-plus"
                    />
                    <Option
                        @click.native="$createFileRequest(item)"
                        v-if="isFolder"
                        :title="$t('file_request')"
                        icon="upload-cloud"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$openInDetailPanel(item)" :title="$t('detail')" icon="detail" />
                    <Option
                        @click.native="$downloadSelection(item)"
                        :title="$t('download')"
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
                                ? $t('remove_favourite')
                                : $t('add_to_favourites')
                        "
                        icon="favourites"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile>
            <MobileActionButton @click.native="$openSpotlight()" icon="search">
                {{ $t('spotlight') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-filter')" icon="filter">
                {{ $getCurrentSectionName() }}
            </MobileActionButton>
            <MobileActionButton
                @click.native="$showMobileMenu('create-list')"
                icon="cloud-plus"
            >
                {{ $t('upload_or_create') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
                {{ $t('select') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </FileActionsMobile>

        <EmptyFilePage>
            <h1 class="title">
                {{ $t('empty_page.title') }}
            </h1>
            <p class="description">
                {{ $t('empty_page.description') }}
            </p>
            <ButtonUpload button-style="theme">
                {{ $t('empty_page.call_to_action') }}
            </ButtonUpload>
        </EmptyFilePage>

        <FileBrowser />
    </div>
</template>

<script>
import EmptyFilePage from '../../components/EntriesView/EmptyFilePage'
import FileActionsMobile from '../../components/Mobile/FileActionsMobile'
import MobileMultiSelectToolbar from '../../components/Layout/Toolbars/MobileMultiSelectToolbar'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton'
import MobileContextMenu from '../../components/Menus/MobileContextMenu'
import MobileCreateMenu from '../../components/Menus/MobileCreateMenu'
import ButtonUpload from '../../components/UI/Buttons/ButtonUpload'
import ToolbarButton from '../../components/UI/Buttons/ToolbarButton'
import OptionUpload from '../../components/Menus/Components/OptionUpload'
import FileBrowser from '../../components/EntriesView/FileBrowser'
import ContextMenu from '../../components/Menus/ContextMenu'
import OptionGroup from '../../components/Menus/Components/OptionGroup'
import Option from '../../components/Menus/Components/Option'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'Files',
    components: {
        EmptyFilePage,
        FileActionsMobile,
        MobileMultiSelectToolbar,
        MobileActionButton,
        MobileContextMenu,
        MobileCreateMenu,
        ToolbarButton,
        ButtonUpload,
        OptionUpload,
        OptionGroup,
        FileBrowser,
        ContextMenu,
        Option,
    },
    computed: {
        ...mapGetters(['fastPreview', 'clipboard', 'config', 'user']),
        isFolder() {
            return this.item && this.item.data.type === 'folder'
        },
        isInFavourites() {
            return this.user.data.relationships.favourites.find((el) => el.data.id === this.item.data.id)
        },
        hasFile() {
            return this.clipboard.find((item) => item.data.type !== 'folder')
        },
    },
    data() {
        return {
            item: undefined,
        }
    },
    created() {
        this.$store.dispatch('getFolder', {page: 1, id:this.$route.params.id})

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('context-menu:current-folder', (folder) => (this.item = folder))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
    },
}
</script>
