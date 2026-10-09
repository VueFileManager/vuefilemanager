<template>
    <div>
        <ContextMenu>
            <template v-slot:empty-select>
                <OptionGroup>
                    <Option @click="$emptyTrash" :title="$t('empty_trash')" icon="empty-trash" />
                </OptionGroup>
            </template>

            <template v-slot:single-select v-if="item">
                <OptionGroup>
                    <Option
                        @click="$restoreFileOrFolder(item)"
                        v-if="item"
                        :title="$t('restore')"
                        icon="restore"
                    />
                    <Option
                        @click="$deleteFileOrFolder(item)"
                        v-if="item"
                        :title="$t('delete')"
                        icon="trash"
                    />
                    <Option @click="$emptyTrash" :title="$t('empty_trash')" icon="empty-trash" />
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
                <OptionGroup>
                    <Option
                        @click="$restoreFileOrFolder(item)"
                        v-if="item"
                        :title="$t('restore')"
                        icon="restore"
                    />
                    <Option @click="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
                    <Option @click="$emptyTrash" :title="$t('empty_trash')" icon="empty-trash" />
                </OptionGroup>
                <OptionGroup>
                    <Option @click="$downloadSelection()" :title="$t('download')" icon="download" />
                </OptionGroup>
            </template>
        </ContextMenu>

        <MobileContextMenu>
            <OptionGroup v-if="item">
                <Option
                    @click="$restoreFileOrFolder(item)"
                    v-if="item"
                    :title="$t('restore')"
                    icon="restore"
                />
                <Option @click="$deleteFileOrFolder(item)" :title="$t('delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click="$downloadSelection(item)" :title="$t('download')" icon="download" />
            </OptionGroup>
        </MobileContextMenu>

        <FileActionsMobile>
            <MobileActionButton @click="$openSpotlight()" icon="search">
                {{ $t('spotlight') }}
            </MobileActionButton>
            <MobileActionButton @click="$showMobileMenu('file-filter')" icon="filter">
                {{ $getCurrentSectionName() }}
            </MobileActionButton>
            <MobileActionButton @click="$emptyTrash" icon="trash">
                {{ $t('empty_trash') }}
            </MobileActionButton>
            <MobileActionButton @click="$enableMultiSelectMode" icon="check-square">
                {{ $t('select') }}
            </MobileActionButton>
            <MobileActionButton @click="$showMobileMenu('file-sorting')" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </FileActionsMobile>

        <EmptyFilePage>
            <h1 class="title">{{ $t('your_trash_is_empty') }}</h1>
			<p class="description">
				{{ $t('your_trash_is_empty_desc') }}
			</p>
        </EmptyFilePage>

        <FileBrowser />

        <MobileMultiSelectToolbar>
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
    </div>
</template>

<script>
import EmptyFilePage from '../../components/EntriesView/EmptyFilePage.vue'
import FileActionsMobile from '../../components/Mobile/FileActionsMobile.vue'
import MobileActionButtonUpload from '../../components/UI/Buttons/MobileActionButtonUpload.vue'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton.vue'
import MobileMultiSelectToolbar from '../../components/Layout/Toolbars/MobileMultiSelectToolbar.vue'
import MobileContextMenu from '../../components/Menus/MobileContextMenu.vue'
import ToolbarButton from '../../components/UI/Buttons/ToolbarButton.vue'
import FileBrowser from '../../components/EntriesView/FileBrowser.vue'
import ContextMenu from '../../components/Menus/ContextMenu.vue'
import OptionGroup from '../../components/Menus/Components/OptionGroup.vue'
import Option from '../../components/Menus/Components/Option.vue'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'Trash',
    components: {
        MobileActionButtonUpload,
        MobileMultiSelectToolbar,
        MobileActionButton,
        MobileContextMenu,
        ToolbarButton,
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
    created() {
		this.$store.dispatch('getTrash', {page: 1, id: this.$route.params.id})

        events.$on('context-menu:show', (event, item) => (this.item = item))
        events.$on('mobile-context-menu:show', (item) => (this.item = item))
    },
}
</script>
