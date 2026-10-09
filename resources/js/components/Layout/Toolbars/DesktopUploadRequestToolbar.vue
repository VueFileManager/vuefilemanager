<template>
    <div class="hidden lg:block">
        <div class="flex items-center justify-between py-3">
            <NavigationBar />

            <div class="flex items-center">

				<!--I am Done-->
                <div @click="uploadingDone" class="bg-theme-200 mr-6 flex cursor-pointer items-center rounded-lg py-1 pr-1 pl-4">
                    <b class="text-theme mr-3 text-sm leading-3">
                        {{ $t('tell_you_are_done', {name: uploadRequest.data.relationships.user.data.attributes.name}) }}
                    </b>
					<MemberAvatar
						:member="uploadRequest.data.relationships.user"
						:size="34"
					/>
                </div>

                <!--Create button-->
                <PopoverWrapper>
                    <ToolbarButton
                        @click.stop="showCreateMenu"
                        source="cloud-plus"
                        :action="$t('create_something')"
                    />
                    <PopoverItem name="desktop-create" side="left">
                        <OptionGroup :title="$t('frequently_used')">
                            <OptionUpload :title="$t('upload_files')" type="file" />
                            <Option
                                @click="$createFolder"
                                :title="$t('create_folder')"
                                icon="folder-plus"
                            />
                        </OptionGroup>
                        <OptionGroup :title="$t('others')">
                            <OptionUpload :title="$t('upload_folder')" type="folder" />
							<Option
								@click.stop="$openRemoteUploadPopup"
								:title="$t('remote_upload')"
								icon="remote-upload"
							/>
                        </OptionGroup>
                    </PopoverItem>
                </PopoverWrapper>

                <!--File Controls-->
                <div v-if="!$isMobile()" class="ml-5 flex items-center xl:ml-8">
                    <ToolbarButton
                        @click="$moveFileOrFolder(clipboard[0])"
                        :class="{
                            'is-inactive': !canManipulate,
                        }"
                        source="move"
                        :action="$t('move')"
                    />
                    <ToolbarButton
                        @click="$deleteFileOrFolder(clipboard[0])"
                        :class="{
                            'is-inactive': !canManipulate,
                        }"
                        source="trash"
                        :action="$t('delete')"
                    />
                </div>

                <!--View Controls-->
                <div class="ml-5 flex items-center xl:ml-8">
                    <PopoverWrapper>
                        <ToolbarButton
                            @click.stop="showSortingMenu"
                            source="preview-sorting"
                            :action="$t('sorting_view')"
                        />
                        <PopoverItem name="desktop-sorting" side="left">
                            <FileSortingOptions />
                        </PopoverItem>
                    </PopoverWrapper>
                    <ToolbarButton
                        @click="$store.dispatch('fileInfoToggle')"
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
import MemberAvatar from "../../UI/Others/MemberAvatar.vue"
import OptionUpload from '../../Menus/Components/OptionUpload.vue'
import OptionGroup from '../../Menus/Components/OptionGroup.vue'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import Option from '../../Menus/Components/Option.vue'

export default {
    name: 'DesktopUploadRequestToolbar',
    components: {
        FileSortingOptions,
        UploadProgress,
        PopoverWrapper,
        NavigationBar,
        ToolbarButton,
		MemberAvatar,
        OptionUpload,
        OptionGroup,
        PopoverItem,
        Option,
    },
    computed: {
        ...mapGetters(['isVisibleNavigationBars', 'currentTeamFolder', 'currentFolder', 'sharedDetail', 'clipboard', 'uploadRequest']),
        canManipulate() {
            return this.clipboard[0]
        },
    },
    methods: {
        uploadingDone() {
			events.$emit('confirm:open', {
				title: this.$t('closing_request_for_upload', {name: this.uploadRequest.data.relationships.user.data.attributes.name}),
				message: this.$t('closing_request_for_upload_warn'),
				action: {
					id: this.$route.params.token,
					operation: 'close-upload-request',
				},
			})
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
