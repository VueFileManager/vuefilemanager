<template>
    <div>
        <MobileContextMenu>
            <template v-slot:editor>
                <OptionGroup>
                    <Option
                        v-if="item"
                        @click.native="$renameFileOrFolder(item)"
                        :title="$t('rename')"
                        icon="rename"
                    />
                    <Option
                        v-if="item"
                        @click.native="$moveFileOrFolder(item)"
                        :title="$t('move')"
                        icon="move-item"
                    />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option
                        @click.native="$downloadSelection(item)"
                        :title="$t('download')"
                        icon="download"
                    />
                </OptionGroup>
            </template>
            <template v-slot:visitor>
                <OptionGroup>
                    <Option
                        @click.native="$downloadSelection(item)"
                        :title="$t('download')"
                        icon="download"
                    />
                </OptionGroup>
            </template>
        </MobileContextMenu>

        <MobileCreateMenu>
            <OptionGroup :title="$t('upload')">
                <OptionUpload :title="$t('upload_files')" type="file" :is-hover-disabled="true" />
                <OptionUpload :title="$t('upload_folder')" type="folder" :is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup :title="$t('create')">
                <Option
                    @click.stop.native="createFolder"
                    :title="$t('create_folder')"
                    icon="folder-plus"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MobileCreateMenu>

        <MobileMultiSelectToolbar>
            <template v-slot:visitor>
                <ToolbarButton
                    @click.native="$downloadSelection()"
                    class="mr-4"
                    source="download"
                    :action="$t('download_item')"
                />
            </template>
            <template v-slot:editor>
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
                    @click.native="$downloadSelection()"
                    source="download"
                    :action="$t('download_item')"
                />
            </template>
        </MobileMultiSelectToolbar>

        <ContextMenu>
            <template v-slot:empty-select v-if="$checkPermission('editor')">
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
                <OptionGroup v-if="$checkPermission('editor')">
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
                <OptionGroup v-if="$checkPermission('editor')">
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <FileActionsMobile>
            <template v-if="$checkPermission('editor')">
                <MobileActionButton @click.native="$openSpotlight()" icon="search">
                    {{ $t('spotlight') }}
                </MobileActionButton>
                <MobileActionButton @click.native="$showMobileMenu('create-list')" icon="cloud-plus">
                    {{ $t('upload_or_create') }}
                </MobileActionButton>
                <MobileActionButton @click.native="$enableMultiSelectMode" icon="check-square">
                    {{ $t('select') }}
                </MobileActionButton>
                <MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
                    {{ $t('preview_sorting.preview_sorting_button') }}
                </MobileActionButton>
            </template>
            <template v-if="$checkPermission('visitor')">
                <MobileActionButton @click.native="$openSpotlight()" icon="search">
                    {{ $t('spotlight') }}
                </MobileActionButton>
                <MobileActionButton @click.native="$enableMultiSelectMode()" icon="check-square">
                    {{ $t('select') }}
                </MobileActionButton>
                <MobileActionButton @click.native="$showMobileMenu('file-sorting')" icon="preview-sorting">
                    {{ $t('preview_sorting.preview_sorting_button') }}
                </MobileActionButton>
            </template>
        </FileActionsMobile>

        <EmptyFilePage>
            <template v-if="$checkPermission('editor')">
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
            <template v-if="$checkPermission('visitor')">
                <h1 class="title">
                    {{ $t('there_is_nothing') }}
                </h1>
            </template>
        </EmptyFilePage>

        <FileBrowser />
    </div>
</template>

<script>
import EmptyFilePage from '../../components/FilesView/EmptyFilePage'
import FileActionsMobile from '../../components/FilesView/FileActionsMobile'
import MobileMultiSelectToolbar from '../../components/FilesView/MobileMultiSelectToolbar'
import MobileActionButton from '../../components/FilesView/MobileActionButton'
import MobileContextMenu from '../../components/FilesView/MobileContextMenu'
import MobileCreateMenu from '../../components/FilesView/MobileCreateMenu'
import ToolbarButton from '../../components/FilesView/ToolbarButton'
import OptionUpload from '../../components/FilesView/OptionUpload'
import ButtonUpload from '../../components/FilesView/ButtonUpload'
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
        MobileContextMenu,
        MobileCreateMenu,
        ToolbarButton,
        OptionUpload,
        ButtonUpload,
        OptionGroup,
        FileBrowser,
        ContextMenu,
        Option,
        FileActionsMobile,
        EmptyFilePage,
    },
    computed: {
        ...mapGetters(['clipboard']),
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
        this.$store.dispatch('getSharedFolder', this.$route.params.id)

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
        events.$on('context-menu:current-folder', (folder) => (this.item = folder))
    },
}
</script>
