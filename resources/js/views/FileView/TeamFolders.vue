<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
            </OptionGroup>
            <OptionGroup v-if="item">
                <Option
                    @click.native="$shareFileOrFolder(item)"
                    :title="item.data.relationships.shared ? $t('edit_sharing') : $t('share')"
                    icon="share"
                />
                <Option
                    @click.native="$updateTeamFolder(item)"
                    v-if="isFolder && (isTeamFolderHomepage || currentTeamFolder.data.id === item.data.id)"
                    :title="$t('edit_team_members')"
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
                <OptionUpload :title="$t('upload_files')" type="file" :is-hover-disabled="true" :class="{'is-inactive': isTeamFolderHomepage}" />
                <Option
                    @click.stop.native="$createFolderByPopup"
                    :title="$t('create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
					:class="{'is-inactive': isTeamFolderHomepage}"
                />
            </OptionGroup>
            <OptionGroup :title="$t('others')">
				<Option
					@click.stop.native="$openRemoteUploadPopup"
					:title="$t('remote_upload')"
					icon="remote-upload"
					:class="{'is-inactive': isTeamFolderHomepage}"
				/>
                <Option
                    @click.stop.native="$createTeamFolder"
                    :title="$t('create_team_folder')"
                    icon="users"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MobileCreateMenu>

        <MobileTeamContextMenu>
            <OptionGroup>
                <Option @click.native="$updateTeamFolder(teamFolder)" :title="$t('edit_members')" icon="rename" />
                <Option @click.native="$dissolveTeamFolder(teamFolder)" :title="$t('dissolve_team')" icon="trash" />
            </OptionGroup>
        </MobileTeamContextMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
				v-if="! isTeamFolderHomepage"
                @click.native="$moveFileOrFolder(clipboard)"
                class="mr-4"
                source="move"
                :action="$t('move')"
                :class="{ 'is-inactive': clipboard.length < 1 }"
            />
            <ToolbarButton
				v-if="! isTeamFolderHomepage"
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
                <OptionGroup v-if="!isTeamFolderHomepage">
                    <OptionUpload :title="$t('upload_files')" type="file" />
                    <OptionUpload :title="$t('upload_folder')" type="folder" />
                    <Option
                        @click.native="$createFolder"
                        :title="$t('create_folder')"
                        icon="folder-plus"
                    />
                </OptionGroup>
                <OptionGroup v-if="isTeamFolderHomepage">
                    <Option @click.native="$createTeamFolder" :title="$t('create_team_folder')" icon="users" />
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
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
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
                        @click.native="$updateTeamFolder(item)"
                        v-if="isFolder"
                        :title="$t('edit_team_members')"
                        icon="users"
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
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" :class="{'is-inactive': isTeamFolderHomepage || isTeamFolder}" />
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
                v-if="$checkPermission(['master', 'editor'])"
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
            <template v-if="isTeamFolderHomepage">
                <h1 class="title">
                    {{ $t('create_team_folder') }}
                </h1>
                <p class="description">
                    {{ $t('create_team_folder_description') }}
                </p>
                <ButtonBase @click.native="$createTeamFolder" button-style="theme" class="m-center">
                    {{ $t('create_team_folder') }}
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
import MobileTeamContextMenu from '../../components/Menus/MobileTeamContextMenu'
import EmptyFilePage from '../../components/EntriesView/EmptyFilePage'
import FileActionsMobile from '../../components/Mobile/FileActionsMobile'
import MobileActionButtonUpload from '../../components/UI/Buttons/MobileActionButtonUpload'
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
import ButtonBase from '../../components/UI/Buttons/ButtonBase'
import Option from '../../components/Menus/Components/Option'
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
		isTeamFolder() {
			return this.teamFolder?.data.id === this.item.data.id
		},
        isTeamFolderHomepage() {
            return this.$isThisRoute(this.$route, ['TeamFolders']) && !this.$route.params.id
        },
        isFolder() {
            return this.item && this.item.data.type === 'folder'
        },
        isInFavourites() {
            return this.user.data.relationships.favourites.find((el) => el.id === this.item.id)
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
        this.$store.dispatch('getTeamFolder', {page: 1, id:this.$route.params.id})

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
