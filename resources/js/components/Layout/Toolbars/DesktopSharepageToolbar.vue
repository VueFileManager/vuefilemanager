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
                        :action="$t('create_something')"
                    />
                    <PopoverItem name="desktop-create" side="left">
                        <OptionGroup :title="$t('frequently_used')">
                            <OptionUpload :title="$t('upload_files')" type="file" />
                            <Option
                                @click.native="$createFolder"
                                :title="$t('create_folder')"
                                icon="folder-plus"
                            />
                        </OptionGroup>
                        <OptionGroup :title="$t('others')">
                            <OptionUpload :title="$t('upload_folder')" type="folder" />
							<Option
								@click.stop.native="$openRemoteUploadPopup"
								:title="$t('remote_upload')"
								icon="remote-upload"
							/>
                        </OptionGroup>
                    </PopoverItem>
                </PopoverWrapper>

                <!--Search bar-->
                <SearchBarButton class="ml-5 hidden lg:block xl:ml-8" />

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
                            :action="$t('move')"
                        />
                        <ToolbarButton
                            @click.native="$deleteFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': !canManipulate,
                            }"
                            source="trash"
                            :action="$t('delete')"
                        />
                    </div>
                </div>

                <!--View Controls-->
                <div class="ml-5 flex items-center xl:ml-8">
                    <PopoverWrapper>
                        <ToolbarButton
                            @click.stop.native="showSortingMenu"
                            source="preview-sorting"
                            :action="$t('sorting_view')"
                        />
                        <PopoverItem name="desktop-sorting" side="left">
                            <FileSortingOptions />
                        </PopoverItem>
                    </PopoverWrapper>
                    <ToolbarButton
                        @click.native="$store.dispatch('fileInfoToggle')"
                        :action="$t('info_panel')"
                        source="info"
                    />
                </div>
            </div>
        </div>

        <UploadProgress />
    </div>
</template>

<script>
import PopoverWrapper from '../../UI/Popover/PopoverWrapper.vue'
import FileSortingOptions from '../../Menus/FileSortingOptions.vue'
import PopoverItem from '../../UI/Popover/PopoverItem.vue'
import UploadProgress from '../../UI/Others/UploadProgress.vue'
import NavigationBar from './NavigationBar.vue'
import ToolbarButton from '../../UI/Buttons/ToolbarButton.vue'
import OptionUpload from '../../Menus/Components/OptionUpload.vue'
import OptionGroup from '../../Menus/Components/OptionGroup.vue'
import SearchBarButton from '../../UI/Buttons/SearchBarButton.vue'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import Option from '../../Menus/Components/Option.vue'

export default {
    name: 'DesktopSharepageToolbar',
    components: {
        FileSortingOptions,
		SearchBarButton,
        UploadProgress,
        PopoverWrapper,
        NavigationBar,
        ToolbarButton,
        OptionUpload,
        OptionGroup,
        PopoverItem,
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
