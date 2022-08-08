<template>
    <div>
        <ContextMenu>
            <template v-slot:single-select v-if="item">
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
                <OptionGroup>
                    <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                    <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click.native="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <MobileContextMenu>
            <OptionGroup>
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                <Option
                    @click.native="$shareFileOrFolder(item)"
                    :title="
                        item && item.data.relationships.shared
                            ? $t('edit_sharing')
                            : $t('share')
                    "
                    icon="share"
                />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="$downloadSelection(item)" :title="$t('download')" icon="download" />
            </OptionGroup>
        </MobileContextMenu>

        <FileActionsMobile>
            <MobileActionButton @click.native="$openSpotlight()" icon="search">
                {{ $t('spotlight') }}
            </MobileActionButton>
            <MobileActionButton @click.native="$showMobileMenu('file-filter')" icon="filter">
                {{ $getCurrentSectionName() }}
            </MobileActionButton>
            <MobileActionButtonUpload>
                {{ $t('upload') }}
            </MobileActionButtonUpload>
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

        <MobileMultiSelectToolbar>
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
    </div>
</template>

<script>
import EmptyFilePage from '../../components/EntriesView/EmptyFilePage'
import FileActionsMobile from '../../components/Mobile/FileActionsMobile'
import MobileActionButtonUpload from '../../components/UI/Buttons/MobileActionButtonUpload'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton'
import MobileMultiSelectToolbar from '../../components/Layout/Toolbars/MobileMultiSelectToolbar'
import MobileContextMenu from '../../components/Menus/MobileContextMenu'
import ToolbarButton from '../../components/UI/Buttons/ToolbarButton'
import ButtonUpload from '../../components/UI/Buttons/ButtonUpload'
import FileBrowser from '../../components/EntriesView/FileBrowser'
import ContextMenu from '../../components/Menus/ContextMenu'
import OptionGroup from '../../components/Menus/Components/OptionGroup'
import Option from '../../components/Menus/Components/Option'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'RecentUploads',
    components: {
        MobileActionButtonUpload,
        MobileMultiSelectToolbar,
        MobileActionButton,
        MobileContextMenu,
        ToolbarButton,
        ButtonUpload,
        OptionGroup,
        FileBrowser,
        ContextMenu,
        Option,
        FileActionsMobile,
        EmptyFilePage,
    },
    computed: {
        ...mapGetters(['clipboard', 'user']),
    },
    data() {
        return {
            item: undefined,
        }
    },
    created() {
        this.$store.dispatch('getRecentUploads', 1)

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
    },
}
</script>
