<template>
    <div class="hidden lg:block">
        <div class="flex items-center justify-between py-3">
            <NavigationBar />

            <div class="flex items-center">
                <!--Create button-->
                <PopoverWrapper>
                    <ToolbarButton
                        @click.stop.native="showCreateMenu"
                        source="cloud-plus"
                        :action="$t('create_something')"
                    />
                    <PopoverItem name="desktop-create" side="left">
                        <OptionGroup
                            :title="$t('upload')"
                        >
                            <OptionUpload
								:title="$t('upload_files')"
								type="file"
								:class="{
                                    'is-inactive': canUploadInView,
                                }"
							/>
                            <OptionUpload
								:class="{
                                    'is-inactive': canUploadFolderInView,
                                }"
								:title="$t('upload_folder')"
								type="folder"
							/>
                        </OptionGroup>
                        <OptionGroup
                            :title="$t('create')"
                        >
                            <Option
                                @click.native="$createFolder"
                                :class="{
                                    'is-inactive': canCreateFolder,
                                }"
                                :title="$t('create_folder')"
                                icon="folder-plus"
                            />
                        </OptionGroup>
                    </PopoverItem>
                </PopoverWrapper>

                <!--Search bar-->
                <SearchBarButton class="ml-5 hidden lg:block xl:ml-8" />

                <!--File Controls-->
                <div class="ml-5 flex items-center xl:ml-8">

                    <!--Action buttons-->
                    <div v-if="!$isMobile()" class="flex items-center">
                        <ToolbarButton
                            v-if="!$isThisRoute($route, ['Public'])"
                            @click.native="$shareFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': canShareInView,
                            }"
                            source="share"
                            :action="$t('share_item')"
                        />
                        <ToolbarButton
                            @click.native="$moveFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': canMoveInView,
                            }"
                            source="move"
                            :action="$t('move')"
                        />
                        <ToolbarButton
                            @click.native="$deleteFileOrFolder(clipboard[0])"
                            :class="{
                                'is-inactive': canDeleteInView,
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
import PopoverWrapper from '../../UI/Popover/PopoverWrapper'
import FileSortingOptions from '../../Menus/FileSortingOptions'
import PopoverItem from '../../UI/Popover/PopoverItem'
import UploadProgress from '../../UI/Others/UploadProgress'
import NavigationBar from './NavigationBar'
import ToolbarButton from '../../UI/Buttons/ToolbarButton'
import OptionUpload from '../../Menus/Components/OptionUpload'
import OptionGroup from '../../Menus/Components/OptionGroup'
import SearchBarButton from '../../UI/Buttons/SearchBarButton'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import Option from '../../Menus/Components/Option'

export default {
    name: 'DesktopToolbar',
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
        ...mapGetters([
            'isVisibleNavigationBars',
            'currentFolder',
            'sharedDetail',
            'clipboard',
            'user',
        ]),
        canCreateFolder() {
            return !this.$isThisRoute(this.$route, ['Files', 'Public'])
        },
        canUploadInView() {
            return !this.$isThisRoute(this.$route, ['Files', 'RecentUploads', 'Public'])
        },
        canUploadFolderInView() {
            return !this.$isThisRoute(this.$route, ['Files', 'Public'])
        },
        canDeleteInView() {
            let routes = ['RecentUploads', 'MySharedItems', 'Trash', 'Public', 'Files']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
        },
        canMoveInView() {
            let routes = ['RecentUploads', 'MySharedItems', 'Public', 'Files']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
        },
        canShareInView() {
            let routes = ['RecentUploads', 'MySharedItems', 'Public', 'Files']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length > 1 || this.clipboard.length === 0
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
