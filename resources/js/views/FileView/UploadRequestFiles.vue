<template>
    <div>
        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('Upload')">
                <OptionUpload :title="$t('actions.upload')" type="file" :is-hover-disabled="true" />
                <OptionUpload :title="$t('actions.upload_folder')" type="folder" />
            </OptionGroup>
            <OptionGroup :title="$t('Create')">
                <Option
                    @click.stop.native="createFolder"
                    :title="$t('actions.create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MobileCreateMenu>

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
        </MobileMultiSelectToolbar>

        <ContextMenu v-if="entries.length">
            <template v-slot:empty-select>
                <OptionGroup>
                    <OptionUpload :title="$t('actions.upload')" type="file" />
                    <OptionUpload :title="$t('actions.upload_folder')" type="folder" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$createFolder"
                        :title="$t('context_menu.create_folder')"
                        icon="create-folder"
                    />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
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
                    <Option @click.native="$openInDetailPanel(item)" :title="$t('context_menu.detail')" icon="detail" />
                </OptionGroup>
            </template>

            <template v-slot:multiple-select v-if="item">
                <OptionGroup>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('context_menu.download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile v-if="entries.length">
            <MobileActionButton @click.native="$openSpotlight()" icon="search">
                {{ $t('Spotlight') }}
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
            <div v-if="uploadRequest" class="relative mx-auto mb-8 w-24 text-center">
                <VueFolderIcon class="inline-block w-28" />
                <MemberAvatar
                    :member="uploadRequest.data.relationships.user"
                    class="absolute -bottom-2.5 -right-2"
                    :is-border="true"
                    :size="32"
                />
            </div>

            <h1 class="title">
                {{ $t('Jane Request You for File Upload') }}
            </h1>
            <p class="description max-w-[420px]">
                {{
                    $t(
                        'Your files will be uploaded automatically and after that, you can organize your files in folders.'
                    )
                }}
            </p>
            <ButtonUpload button-style="theme">
                {{ $t('empty_page.call_to_action') }}
            </ButtonUpload>
        </EmptyFilePage>

        <FileBrowser />
    </div>
</template>

<script>
import MobileMultiSelectToolbar from '../../components/FilesView/MobileMultiSelectToolbar'
import MobileActionButton from '../../components/FilesView/MobileActionButton'
import FileActionsMobile from '../../components/FilesView/FileActionsMobile'
import MobileContextMenu from '../../components/FilesView/MobileContextMenu'
import VueFolderIcon from '../../components/FilesView/Icons/VueFolderIcon'
import MobileCreateMenu from '../../components/FilesView/MobileCreateMenu'
import EmptyFilePage from '../../components/FilesView/EmptyFilePage'
import ToolbarButton from '../../components/FilesView/ToolbarButton'
import MemberAvatar from '../../components/FilesView/MemberAvatar'
import ButtonUpload from '../../components/FilesView/ButtonUpload'
import OptionUpload from '../../components/FilesView/OptionUpload'
import FileBrowser from '../../components/FilesView/FileBrowser'
import ContextMenu from '../../components/FilesView/ContextMenu'
import OptionGroup from '../../components/FilesView/OptionGroup'
import Option from '../../components/FilesView/Option'
import { events } from '../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'Files',
    components: {
        MobileMultiSelectToolbar,
        MobileActionButton,
        FileActionsMobile,
        MobileContextMenu,
        MobileCreateMenu,
        EmptyFilePage,
        VueFolderIcon,
        ToolbarButton,
        MemberAvatar,
        ButtonUpload,
        OptionUpload,
        OptionGroup,
        FileBrowser,
        ContextMenu,
        Option,
    },
    computed: {
        ...mapGetters(['fastPreview', 'clipboard', 'config', 'user', 'entries', 'uploadRequest']),
        isFolder() {
            return this.item && this.item.data.type === 'folder'
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
    created() {
		events.$on('context-menu:show', (event, item) => (this.item = item))
		events.$on('context-menu:current-folder', (folder) => (this.item = folder))
		events.$on('mobile-context-menu:show', (item) => (this.item = item))

        this.$store.dispatch('getUploadRequestDetail')

    },
}
</script>
