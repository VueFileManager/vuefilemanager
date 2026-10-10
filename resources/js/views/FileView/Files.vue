<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option @click="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                <Option @click="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup v-if="item">
                <Option
                    @click="$shareFileOrFolder(item)"
                    :title="item.data.relationships.shared ? $t('edit_sharing') : $t('share')"
                    icon="share"
                />
                <Option
                    @click="$convertAsTeamFolder(item)"
                    v-if="isFolder"
                    :title="$t('convert_as_team_folder')"
                    icon="users"
                />
				<Option
					@click="$createFileRequest(item)"
					v-if="isFolder"
					:title="$t('file_request')"
					icon="upload-cloud"
				/>
            </OptionGroup>

            <OptionGroup v-if="item">
                <Option @click="$downloadSelection(item)" :title="$t('download')" icon="download" />
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
                    @click.stop="$createFolderByPopup"
                    :title="$t('create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
            <OptionGroup :title="$t('others')">
				<Option
					@click.stop="$openRemoteUploadPopup"
					:title="$t('remote_upload')"
					icon="remote-upload"
				/>
                <Option
                    @click.stop="$createTeamFolder"
                    :title="$t('create_team_folder')"
                    icon="users"
                    :is-hover-disabled="true"
                />
				<Option
					@click="$createFileRequest"
					:title="$t('create_file_request')"
					icon="upload-cloud"
					:is-hover-disabled="true"
				/>
            </OptionGroup>
        </MobileCreateMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
                @click="$moveFileOrFolder(clipboard)"
                class="mr-4"
                source="move"
                :action="$t('move')"
                :class="{ 'is-inactive': clipboard.length < 1 }"
            />
            <ToolbarButton
                @click="$deleteFileOrFolder()"
                class="mr-4"
                source="trash"
                :class="{ 'is-inactive': clipboard.length < 1 }"
                :action="$t('delete')"
            />
            <ToolbarButton
                @click="$downloadSelection(item)"
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
                        @click="$createFolder"
                        :title="$t('create_folder')"
                        icon="create-folder"
                    />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup v-if="isFolder">
                    <Option
                        @click="$toggleFavourites(item)"
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
                        @click="$renameFileOrFolder(item)"
                        :title="$t('rename')"
                        icon="rename"
                    />
                    <Option @click="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click="$shareFileOrFolder(item)"
                        :title="
                            item.data.relationships.shared ? $t('edit_sharing') : $t('share')
                        "
                        icon="share"
                    />
                    <Option
                        @click="$convertAsTeamFolder(item)"
                        v-if="isFolder"
                        :title="$t('convert_as_team_folder')"
                        icon="user-plus"
                    />
                    <Option
                        @click="$createFileRequest(item)"
                        v-if="isFolder"
                        :title="$t('file_request')"
                        icon="upload-cloud"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click="$openInDetailPanel(item)" :title="$t('detail')" icon="detail" />
                    <Option
                        @click="$downloadSelection(item)"
                        :title="$t('download')"
                        icon="download"
                    />
                </OptionGroup>
            </template>

            <template v-slot:multiple-select v-if="item">
                <OptionGroup v-if="!hasFile">
                    <Option
                        @click="$toggleFavourites(item)"
                        :title="
                            isInFavourites
                                ? $t('remove_favourite')
                                : $t('add_to_favourites')
                        "
                        icon="favourites"
                    />
                </OptionGroup>
                <OptionGroup>
                    <Option @click="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile>
            <MobileActionButton @click="$openSpotlight()" icon="search">
                {{ $t('spotlight') }}
            </MobileActionButton>
            <MobileActionButton @click="$showMobileMenu('file-filter')" icon="filter">
                {{ $getCurrentSectionName() }}
            </MobileActionButton>
            <MobileActionButton
                @click="$showMobileMenu('create-list')"
                icon="cloud-plus"
            >
                {{ $t('upload_or_create') }}
            </MobileActionButton>
            <MobileActionButton @click="$enableMultiSelectMode" icon="check-square">
                {{ $t('select') }}
            </MobileActionButton>
            <MobileActionButton @click="$showMobileMenu('file-sorting')" icon="preview-sorting">
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
import EmptyFilePage from '../../components/EntriesView/EmptyFilePage.vue'
import FileActionsMobile from '../../components/Mobile/FileActionsMobile.vue'
import MobileMultiSelectToolbar from '../../components/Layout/Toolbars/MobileMultiSelectToolbar.vue'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton.vue'
import MobileContextMenu from '../../components/Menus/MobileContextMenu.vue'
import MobileCreateMenu from '../../components/Menus/MobileCreateMenu.vue'
import ButtonUpload from '../../components/UI/Buttons/ButtonUpload.vue'
import ToolbarButton from '../../components/UI/Buttons/ToolbarButton.vue'
import OptionUpload from '../../components/Menus/Components/OptionUpload.vue'
import FileBrowser from '../../components/EntriesView/FileBrowser.vue'
import ContextMenu from '../../components/Menus/ContextMenu.vue'
import OptionGroup from '../../components/Menus/Components/OptionGroup.vue'
import Option from '../../components/Menus/Components/Option.vue'
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
