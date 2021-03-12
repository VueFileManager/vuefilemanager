<template>
    <div id="shared">
        <!-- File Preview -->
        <FileFullPreview />

        <!--Loading Spinenr-->
        <Spinner v-if="isPageLoading"/>

        <!--Move item setup-->
        <MoveItem />

    	<!-- Processing popup for zip -->
        <ProcessingPopup/>

        <!-- Mobile Menu for Multi selected items -->
        <MobileMultiSelectMenu/>

        <!--Rename folder or file item-->
        <RenameItem/>

        <!--Create folder in mobile version-->
        <CreateFolder/>

         <!-- Drag & Drop UI -->
        <DragUI/>

        <!--Mobile Menu-->
        <MobileMenu/>

        <!-- Mobile menu for selecting view and sorting -->
        <MobileSortingAndPreview/>

        <!--System alerts-->
        <Alert />

        <!--Background vignette-->
        <Vignette/>

        <!--Password verification-->
        <div v-if="isPagePasswordVerification" id="password-view">

            <!--Verify share link by password-->
            <AuthContent class="center" name="password" :visible="true">
                <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
                <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>

                <h1>{{ $t('page_shared.title') }}</h1>
                <h2>{{ $t('page_shared.subtitle') }}</h2>

                <ValidationObserver @submit.prevent="authenticateProtected" ref="authenticateProtected" v-slot="{ invalid }" tag="form" class="form inline-form">

                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Password" rules="required" v-slot="{ errors }">
                        <input v-model="password" :placeholder="$t('page_shared.placeholder_pass')" type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <AuthButton icon="chevron-right" :text="$t('page_shared.submit')" :loading="isLoading" :disabled="isLoading" />
                </ValidationObserver>
            </AuthContent>
        </div>

        <!--Single file page-->
        <div v-if="sharedDetail && sharedDetail.type === 'file' && isPageFiles" id="single-file">
            <div class="single-file-wrapper">
                <FileItemGrid v-if="sharedFile" :item="sharedFile" :context-menu="false"/>

                <ButtonBase @click.native="download" class="download-button" button-style="theme">
                    {{ $t('page_shared.download_file') }}
                </ButtonBase>
            </div>
        </div>

        <!--Multiple items view page-->
        <div v-if="sharedDetail && sharedDetail.type === 'folder' && isPageFiles"
             @contextmenu.prevent.capture="contextMenu($event, undefined)"
             id="viewport">

                <ContentSidebar v-if="navigationTree">

                    <!--Locations-->
                    <ContentGroup :title="$t('sidebar.locations_title')">
                        <div class="menu-list-wrapper vertical">
                            <a class="menu-list-item link" @click="goHome">
                                <div class="icon">
                                    <home-icon size="17"></home-icon>
                                </div>
                                <div class="label">
                                    {{ $t('sidebar.home') }}
                                </div>
                            </a>
                        </div>
                    </ContentGroup>

                    <!--Navigator-->
                    <ContentGroup :title="$t('sidebar.navigator_title')" class="navigator">
                        <span class="empty-note navigator" v-if="navigationTree.length == 0">
                            {{ $t('sidebar.folders_empty') }}
                        </span>
                        <TreeMenuNavigator class="folder-tree" :depth="0" :nodes="items" v-for="items in navigationTree" :key="items.unique_id"/>
                    </ContentGroup>
                </ContentSidebar>

                <div id="files-view">
                    <!--Context menu-->
                    <ContextMenu/>

                    <!--Desktop Toolbar-->
                    <DesktopToolbar/>

                    <!--File browser-->
                    <FileBrowser/>
                    
                    <!-- Selecting preview list and sorting -->
                    <DesktopSortingAndPreview/>
                </div>
            </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import MobileSortingAndPreview from '@/components/FilesView/MobileSortingAndPreview'
    import MobileMultiSelectMenu from '@/components/FilesView/MobileMultiSelectMenu'
    import DesktopSortingAndPreview from '@/components/FilesView/DesktopSortingAndPreview'
    import ProcessingPopup from '@/components/FilesView/ProcessingPopup'
    import TreeMenuNavigator from '@/components/Others/TreeMenuNavigator'
    import FileFullPreview from '@/components/FilesView/FileFullPreview'
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import FileItemGrid from '@/components/FilesView/FileItemGrid'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import FileBrowser from '@/components/FilesView/FileBrowser'
    import ContextMenu from '@/components/FilesView/ContextMenu'
    import CreateFolder from '@/components/Others/CreateFolder'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import MobileMenu from '@/components/FilesView/MobileMenu'
    import AuthContent from '@/components/Auth/AuthContent'
    import RenameItem from '@/components/Others/RenameItem'
    import AuthButton from '@/components/Auth/AuthButton'
    import Spinner from '@/components/FilesView/Spinner'
    import MoveItem from '@/components/Others/MoveItem'
    import Vignette from '@/components/Others/Vignette'
    import DragUI from '@/components/FilesView/DragUI'
    import Alert from '@/components/FilesView/Alert'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'
    import {
        HomeIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'SharedPage',
        components: {
            MobileSortingAndPreview,
            MobileMultiSelectMenu,
            ValidationProvider,
            DesktopSortingAndPreview,
            ValidationObserver,
            TreeMenuNavigator,
            FileFullPreview,
            ProcessingPopup,
            DesktopToolbar,
            ContentSidebar,
            CreateFolder,
            FileItemGrid,
            ContentGroup,
            AuthContent,
            FileBrowser,
            ContextMenu,
            AuthButton,
            MobileMenu,
            ButtonBase,
            RenameItem,
            HomeIcon,
            MoveItem,
            required,
            Vignette,
            Spinner,
            DragUI,
            Alert,
        },
        computed: {
            ...mapGetters([
                'config',
                'sharedDetail',
                'sharedFile',
                'navigation'
            ]),
            navigationTree() {
                return this.navigation ? this.navigation[0].folders : undefined
            },
            isPageFiles() {
                return this.currentPage === 'page-files'
            },
            isPagePasswordVerification() {
                return this.currentPage === 'page-password'
            }
        },
        data() {
            return {
                checkedAccount: undefined,
                password: '',
                isLoading: false,
                isPageLoading: true,
                currentPage: undefined,
                homeDirectory: undefined,
            }
        },
        methods: {
            goHome() {
                this.$store.dispatch('browseShared', [{folder: this.homeDirectory, back: false, init: true}])
            },
            async authenticateProtected() {

                // Validate fields
                const isValid = await this.$refs.authenticateProtected.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/shared/authenticate/' + this.$route.params.token, {
                        password: this.password
                    }).then(() => {

                        // End loading
                        this.isLoading = false

                        // Redirect to file browser page
                        this.currentPage = 'page-files'

                        // Get protected files
                        this.getFiles();

                    }).catch(error => {

                        if (error.response.status == 401) {

                            this.$refs.authenticateProtected.setErrors({
                                'Password': [error.response.data.message]
                            });
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
            getFiles() {

                // Show folder
                if (this.sharedDetail.type === 'folder') {

                    this.homeDirectory = {
                        unique_id: this.sharedDetail.item_id,
                        name: this.$t('locations.home'),
                        location: 'public',
                    }

                    // Get folder tree
                    this.$store.dispatch('getFolderTree')

                    // Load folder
                    this.goHome()
                }

                // Get file
                if (this.sharedDetail.type === 'file') {
                    this.$store.dispatch('getSingleFile')
                }
            },
            download() {
                this.$downloadFile(this.sharedFile.file_url, this.sharedFile.name + '.' + this.sharedFile.mimetype)
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
        },
        created() {

            axios
                .get('/api/shared/' + this.$route.params.token, )
                .then(response => {

                    // Commit shared item options
                    this.$store.commit('SET_SHARED_DETAIL', response.data.data.attributes)
                    this.$store.commit('SET_PERMISSION', response.data.data.attributes.permission)

                    // Hide page spinner
                    this.isPageLoading = false

                    // Show password page
                    if (response.data.data.attributes.protected) {
                        this.currentPage = 'page-password'
                    } else {
                        this.currentPage = 'page-files'
                        this.getFiles()
                    }
                })
                .catch(error => {

                    if (error.response.status == 404) {

                        this.$router.push({name: 'NotFoundShared'})
                    }
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';

     #files-view {
        font-family: 'Nunito', sans-serif;
        font-size: 16px;
        width: 100%;
        height: 100%;
        position: relative;
        min-width: 320px;
        overflow-x: hidden;
        padding-left: 15px;
        padding-right: 15px;
        overflow-y: hidden;
    }

    #shared {
        height: 100%;
    }

    #password-view {
        display: grid;
        height: inherit;

        .center {
            margin: auto;
        }
    }

    #single-file {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        top: 0;
        display: grid;
        height: 100%;

        .single-file-wrapper {
            margin: auto;
            text-align: center;

            .download-button {
                margin-top: 15px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /deep/ .file-wrapper {

            .file-item {
                width: 290px;

                &:hover, &.is-clicked {
                    background: transparent;
                }

                .item-shared {
                    display: none;
                }
            }
        }
    }

    .empty-note {

        &.navigator {
            padding: 5px 25px 10px;
        }
    }

</style>
