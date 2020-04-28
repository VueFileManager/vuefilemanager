<template>
    <div id="shared">

        <!--Loading Spinenr-->
        <Spinner v-if="isPageLoading"/>

        <!--System alerts-->
        <Alert />

        <!--Background vignette-->
        <Vignette/>

        <!--Password verification-->
        <div v-if="currentPage === 'page-password'" id="password-view">

            <!--Verify share link by password-->
            <AuthContent class="center" name="password" :visible="true">
                <img class="logo" :src="config.app_logo" :alt="config.app_name">
                <h1>Your Share Link is Protected</h1>
                <h2>Please type the password to get shared content:</h2>

                <ValidationObserver @submit.prevent="authenticateProtected" ref="authenticateProtected" v-slot="{ invalid }" tag="form" class="form inline-form">

                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Password" rules="required" v-slot="{ errors }">
                        <input v-model="password" placeholder="Type password" type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <AuthButton icon="chevron-right" text="Submit" :loading="isLoading" :disabled="isLoading" />
                </ValidationObserver>
            </AuthContent>
        </div>

        <!--File browser-->
        <div v-if="currentPage === 'page-files'" id="files-view" :class="filesViewWidth">
            <div id="single-file" v-if="sharedDetail.type === 'file'">
                <div class="single-file-wrapper">
                    <FileItemGrid v-if="sharedFile" :data="sharedFile"/>

                    <ButtonBase @click.native="download" class="download-button" button-style="theme">
                        Download File
                    </ButtonBase>
                </div>
            </div>
            <div v-if="sharedDetail.type === 'folder'" @contextmenu.prevent.capture="contextMenu($event, undefined)" @click="fileViewClick">

                <!--Move item setup-->
                <MoveItem />

                <!--Mobile Menu-->
                <MobileMenu/>

                <!--Context menu-->
                <ContextMenu/>

                <!--Desktop Toolbar-->
                <DesktopToolbar/>

                <!--File browser-->
                <FileBrowser/>
            </div>
        </div>
    </div>
</template>

<script>
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import FileItemGrid from '@/components/FilesView/FileItemGrid'
    import FileBrowser from '@/components/FilesView/FileBrowser'
    import ContextMenu from '@/components/FilesView/ContextMenu'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import MobileMenu from '@/components/FilesView/MobileMenu'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import Spinner from '@/components/FilesView/Spinner'
    import MoveItem from '@/components/Others/MoveItem'
    import Vignette from '@/components/Others/Vignette'
    import Alert from '@/components/FilesView/Alert'
    import {required} from 'vee-validate/dist/rules'
    import {ResizeSensor} from 'css-element-queries'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'SharedContent',
        components: {
            ValidationProvider,
            ValidationObserver,
            DesktopToolbar,
            FileItemGrid,
            AuthContent,
            FileBrowser,
            ContextMenu,
            AuthButton,
            MobileMenu,
            ButtonBase,
            MoveItem,
            required,
            Vignette,
            Spinner,
            Alert,
        },
        computed: {
            ...mapGetters(['config', 'filesViewWidth', 'sharedDetail', 'sharedFile']),
        },
        data() {
            return {
                checkedAccount: undefined,
                password: 'tvojpenis',
                isLoading: false,
                isPageLoading: true,
                currentPage: undefined
            }
        },
        methods: {
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
                    }).then(response => {

                        // End loading
                        this.isLoading = false

                        // Redirect to file browser page
                        this.currentPage = 'page-files'

                        // Get protected files
                        this.getFiles();

                    })

                    /*.catch((error) => {

                        /!*if (error.response.status == 401) {

                            this.$refs.authenticateProtected.setErrors({
                                'Password': [error.response.data.message]
                            });
                        }*!/

                        // End loading
                        this.isLoading = false
                    })*/
            },
            download() {
                this.$downloadFile(this.sharedFile.file_url, this.sharedFile.name + '.' + this.sharedFile.mimetype)
            },
            fileViewClick() {
                events.$emit('contextMenu:hide')
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            handleContentResize() {
                let filesView = document.getElementById('files-view').clientWidth

                if (filesView >= 0 && filesView <= 690)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'minimal-scale')

                else if (filesView >= 690 && filesView <= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'compact-scale')

                else if (filesView >= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'full-scale')
            },
            getFiles() {

                // Show folder
                if (this.sharedDetail.type === 'folder') {

                    let homeDirectory = {
                        unique_id: this.sharedDetail.item_id,
                        name: 'Home',
                    }

                    // Set start directory
                    this.$store.commit('SET_START_DIRECTORY', homeDirectory)

                    // Load folder
                    this.$store.dispatch('browseShared', [homeDirectory, false, true])
                        .then(() => {

                            var filesView = document.getElementById('files-view')
                            new ResizeSensor(filesView, this.handleContentResize)
                        })
                }

                // Get file
                if (this.sharedDetail.type === 'file') {
                    this.$store.dispatch('getSingleFile')
                }
            }
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
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';

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

        .single-file-wrapper {
            margin: auto;
            text-align: center;

            .download-button {
                margin-top: 15px;
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

    #files-view {
        font-family: 'Nunito', sans-serif;
        font-size: 16px;
        //overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;
        min-width: 320px;
        overflow-x: hidden;

        &.minimal-scale {
            padding: 0;

            .mobile-toolbar {
                padding: 10px 0 5px;
            }

            .popup-wrapper {
                left: 15px;
                right: 15px;
                padding: 25px 15px;
            }

            .toolbar {
                display: block;
                position: sticky;
                top: 0;
            }

            .toolbar-go-back {
                padding-top: 15px;
            }

            .toolbar-tools {
                text-align: left;
                display: flex;

                .toolbar-button-wrapper {
                    width: 100%;

                    &:last-child {
                        text-align: right;
                    }
                }
            }

            .files-container {
                padding-left: 15px;
                padding-right: 15px;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                position: absolute;
                overflow-y: auto;

                .file-list {
                    //height: 100%;

                    &.grid {
                        grid-template-columns: repeat(auto-fill, 120px);

                        .file-wrapper {

                            .file-item {
                                width: 120px;
                            }

                            .icon-item {
                                margin-bottom: 10px;
                                height: 90px;

                                .file-icon {
                                    @include font-size(75);
                                }

                                .file-icon-text {
                                    @include font-size(12);
                                }

                                .folder-icon {
                                    @include font-size(75);
                                    margin-top: 0;
                                    margin-bottom: 0;
                                }

                                .image {
                                    width: 90px;
                                    height: 90px;
                                }
                            }

                            .item-name .name {
                                @include font-size(13);
                                line-height: 1.2;
                                max-height: 30px;
                            }
                        }
                    }
                }
            }

            .file-wrapper {
                .item-name .name {
                    max-width: 220px;
                }
            }

            .search-bar {

                input {
                    min-width: initial;
                    width: 100%;
                }
            }

            .item-shared {
                .label {
                    display: none;
                }
            }
        }

        &.compact-scale {
            padding-left: 15px;
            padding-right: 15px;

            .file-content {
                position: absolute;
                top: 72px;
                left: 15px;
                right: 15px;
                bottom: 0;
                @include transition;

                &.is-offset {
                    margin-top: 50px;
                }
            }

            .toolbar-tools {

                .toolbar-button-wrapper {
                    margin-left: 35px;
                }
            }

            .search-bar input {
                min-width: 190px;
            }

            .toolbar-go-back span {
                max-width: 120px;
            }

            .grid .file-wrapper {

                .icon-item {
                    margin-bottom: 15px;
                }
            }
        }

        &.full-scale {
            padding-left: 15px;
            padding-right: 15px;

            .file-content {
                position: absolute;
                top: 72px;
                left: 15px;
                right: 15px;
                bottom: 0;
                @include transition;

                &.is-offset {
                    margin-top: 50px;
                }
            }
        }
    }

</style>
