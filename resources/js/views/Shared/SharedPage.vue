<template>
    <div id="shared">

        <!--Loading Spinenr-->
        <Spinner v-if="isPageLoading"/>

        <!--Move item setup-->
        <MoveItem />

        <!--Mobile Menu-->
        <MobileMenu/>

        <!--System alerts-->
        <Alert />

        <!--Background vignette-->
        <Vignette/>

        <!--Password verification-->
        <div v-if="currentPage === 'page-password'" id="password-view">

            <!--Verify share link by password-->
            <AuthContent class="center" name="password" :visible="true">
                <img class="logo" :src="config.app_logo" :alt="config.app_name">
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

        <!--File browser-->
        <div v-if="currentPage === 'page-files'" id="files-view">
            <div id="single-file" v-if="sharedDetail.type === 'file'">
                <div class="single-file-wrapper">
                    <FileItemGrid v-if="sharedFile" :data="sharedFile" :context-menu="false"/>

                    <ButtonBase @click.native="download" class="download-button" button-style="theme">
                        {{ $t('page_shared.download_file') }}
                    </ButtonBase>
                </div>
            </div>
            <div v-if="sharedDetail.type === 'folder'" @contextmenu.prevent.capture="contextMenu($event, undefined)" @click="fileViewClick">

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
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
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
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'SharedPage',
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
            ...mapGetters(['config', 'sharedDetail', 'sharedFile']),
        },
        data() {
            return {
                checkedAccount: undefined,
                password: '',
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

                    let homeDirectory = {
                        unique_id: this.sharedDetail.item_id,
                        name: this.$t('locations.home'),
                        location: 'public',
                    }

                    // Load folder
                    this.$store.dispatch('browseShared', [{folder: homeDirectory, back: false, init: true}])
                }

                // Get file
                if (this.sharedDetail.type === 'file') {
                    this.$store.dispatch('getSingleFile')
                }
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

</style>
