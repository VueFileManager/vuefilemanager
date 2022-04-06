<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="canEdit && item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup v-if="item">
                <Option @click.native="$downloadSelection(item)" :title="$t('download')" icon="download" />
            </OptionGroup>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('frequently_used')">
                <OptionUpload :title="$t('upload_files')" type="file" :is-hover-disabled="true" />
                <Option
                    @click.stop.native="createFolder"
                    :title="$t('create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MobileCreateMenu>

        <MobileTeamContextMenu>
            <OptionGroup>
                <Option
                    @click.native="$detachMeFromTeamFolder(teamFolder)"
                    :title="$t('leave_team_folder')"
                    icon="user-minus"
                />
            </OptionGroup>
        </MobileTeamContextMenu>

        <MobileMultiSelectToolbar>
            <ToolbarButton
                v-if="canEdit"
                @click.native="$moveFileOrFolder(clipboard)"
                class="mr-4"
                source="move"
                :action="$t('move')"
                :class="{ 'is-inactive': clipboard.length < 1 }"
            />
            <ToolbarButton
                v-if="canEdit"
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
            <template v-slot:empty-select v-if="canEdit">
                <OptionGroup v-if="!isTeamFolderHomepage">
                    <OptionUpload :title="$t('upload_files')" type="file" />
                    <OptionUpload :title="$t('upload_folder')" type="folder" />
                </OptionGroup>
                <OptionGroup v-if="!isTeamFolderHomepage">
                    <Option
                        @click.native="$createFolder"
                        :title="$t('create_folder')"
                        icon="folder-plus"
                    />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup v-if="canEdit">
                    <Option
                        @click.native="$renameFileOrFolder(item)"
                        :title="$t('rename')"
                        icon="rename"
                    />
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
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
                <OptionGroup v-if="canEdit">
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
            <MobileActionButton v-if="canEdit" @click.native="$showMobileMenu('create-list')" icon="cloud-plus">
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
            <!--Homepage-->
            <template v-if="isTeamFolderHomepage">
                <h1 class="title">
                    {{ $t('nothing_shared_with_you') }}
                </h1>
                <p class="description">
                    {{ $t('nothing_shared_with_you_description') }}
                </p>
            </template>

            <!--Empty folder wit can-edit privileges -->
            <template v-if="canEdit && !isTeamFolderHomepage">
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

            <!--Empty folder wit can-view privileges -->
            <template v-if="!canEdit && !isTeamFolderHomepage">
                <h1 class="title">
                    {{ $t('there_is_nothing') }}
                </h1>
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
    name: 'SharedWithMe',
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
        canEdit() {
            if (this.currentTeamFolder && this.user) {
                let member = this.currentTeamFolder.data.relationships.members.data.find(
                    (member) => member.data.id === this.user.data.id
                )

                return member.data.attributes.permission === 'can-edit'
            }

            return false
        },
        isTeamFolderHomepage() {
            return this.$isThisRoute(this.$route, ['SharedWithMe']) && !this.$route.params.id
        },
        isFolder() {
            return this.item && this.item.data.type === 'folder'
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
    methods: {
        createFolder() {
            events.$emit('popup:open', { name: 'create-folder' })
        },
    },
    mounted() {
        this.$store.dispatch('getSharedWithMeFolder', this.$route.params.id)

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
        events.$on('context-menu:current-folder', (folder) => (this.item = folder))

        events.$on('action:confirmed', (data) => {
            // Leave team folder after popup confirmation
            if (data.operation === 'leave-team-folder')
                axios
                    .delete(`/api/teams/folders/${data.id}/leave`)
                    .then(() => {
                        if (this.$route.params.id) {
                            this.$router.push({ name: 'SharedWithMe' })
                        } else {
                            this.$store.dispatch('getSharedWithMeFolder', undefined)
                        }

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('you_left_team_folder'),
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
