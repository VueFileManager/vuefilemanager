<template>
    <div class="hidden lg:block">
        <div class="flex items-center justify-between py-3">
            <NavigationBar />

            <div class="flex items-center">
                <!--Create button-->
                <PopoverWrapper v-if="canEdit">
                    <ToolbarButton
                        @click.stop.native="showCreateMenu"
                        source="cloud-plus"
                        :action="$t('actions.create')"
                    />
                    <PopoverItem name="desktop-create" side="left">
                        <OptionGroup :title="$t('Upload')">
                            <OptionUpload :title="$t('actions.upload')" type="file" />
                            <OptionUpload :title="$t('actions.upload_folder')" type="folder" />
                        </OptionGroup>
                        <OptionGroup :title="$t('Create')">
                            <Option
                                @click.stop.native="$createFolder"
                                :title="$t('actions.create_folder')"
                                icon="folder-plus"
                            />
                        </OptionGroup>
                    </PopoverItem>
                </PopoverWrapper>

                <!--Search bar-->
                <SearchBar class="ml-5 hidden lg:block xl:ml-8" />

                <!--File Controls-->
                <div class="ml-5 flex items-center xl:ml-8">
                    <!--Action buttons-->
                    <div v-if="canEdit && !$isMobile()" class="flex items-center">
                        <ToolbarButton
                            @click.native="$moveFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': !canManipulate,
                            }"
                            source="move"
                            :action="$t('actions.move')"
                        />
                        <ToolbarButton
                            @click.native="$deleteFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': !canManipulate,
                            }"
                            source="trash"
                            :action="$t('actions.delete')"
                        />
                    </div>
                </div>

                <!--View Controls-->
                <div class="ml-5 flex items-center xl:ml-8">
                    <PopoverWrapper>
                        <ToolbarButton
                            @click.stop.native="showSortingMenu"
                            source="preview-sorting"
                            :action="$t('actions.sorting_view')"
                        />
                        <PopoverItem name="desktop-sorting" side="left">
                            <FileSortingOptions />
                        </PopoverItem>
                    </PopoverWrapper>
                    <ToolbarButton
                        @click.native="$store.dispatch('fileInfoToggle')"
                        :action="$t('actions.info_panel')"
                        source="info"
                    />
                </div>
            </div>
        </div>

        <UploadProgress />
    </div>
</template>

<script>
import PopoverWrapper from '../Desktop/PopoverWrapper'
import FileSortingOptions from './FileSortingOptions'
import PopoverItem from '../Desktop/PopoverItem'
import UploadProgress from './UploadProgress'
import NavigationBar from './NavigationBar'
import ToolbarButton from './ToolbarButton'
import OptionUpload from './OptionUpload'
import OptionGroup from './OptionGroup'
import SearchBar from './SearchBar'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import Option from './Option'

export default {
    name: 'DesktopSharepageToolbar',
    components: {
        FileSortingOptions,
        UploadProgress,
        PopoverWrapper,
        NavigationBar,
        ToolbarButton,
        OptionUpload,
        OptionGroup,
        PopoverItem,
        SearchBar,
        Option,
    },
    computed: {
        ...mapGetters(['isVisibleNavigationBars', 'currentTeamFolder', 'currentFolder', 'sharedDetail', 'clipboard']),
        canEdit() {
            return this.sharedDetail && this.sharedDetail.data.attributes.permission === 'editor'
        },
        canManipulate() {
            return this.clipboard[0]
        },
    },
    methods: {
        showCreateMenu() {
            events.$emit('popover:open', 'desktop-create')
        },
        showSortingMenu() {
            events.$emit('popover:open', 'desktop-sorting')
        },
    },
}
</script>
