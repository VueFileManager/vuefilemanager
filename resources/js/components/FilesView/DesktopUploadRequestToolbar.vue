<template>
    <div class="hidden lg:block">
        <div class="flex items-center justify-between py-3">
            <NavigationBar />

            <div class="flex items-center">
                <div class="bg-theme-200 mr-6 flex cursor-pointer items-center rounded-lg py-1 pr-1 pl-4">
                    <b @click="uploadingDone" class="text-theme mr-3 text-xs">
                        {{ isDone ? $t('Awesome!') : $t('Tell Jane you are done!') }}
                    </b>
                    <img
                        class="w-8 rounded-lg"
                        src="http://192.168.1.112:8000/avatars/md-f45abbe5-962c-4229-aef2-9991e96d54d9.png"
                        alt="Avatar"
                    />
                </div>

                <!--Create button-->
                <PopoverWrapper>
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

                <!--File Controls-->
                <div v-if="!$isMobile()" class="ml-5 flex items-center xl:ml-8">
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
    name: 'DesktopUploadRequestToolbar',
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
    data() {
        return {
            isDone: false,
        }
    },
    methods: {
        uploadingDone() {
            // TODO: add name to the message
            if (!this.isDone) {
                events.$emit('toaster', {
                    type: 'success',
                    message: this.$t('We notified Jane about your new uploads successfully.'),
                })
            }

            this.isDone = true
        },
        showCreateMenu() {
            events.$emit('popover:open', 'desktop-create')
        },
        showSortingMenu() {
            events.$emit('popover:open', 'desktop-sorting')
        },
    },
}
</script>
