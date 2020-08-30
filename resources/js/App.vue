<template>
    <div id="vue-file-manager" v-cloak>

        <!--System alerts-->
        <Alert/>

        <div id="application-wrapper" v-if="! isGuestLayout">

            <!-- Full File Preview -->
            <FileFullPreview />

            <!--Mobile Navigation-->
            <MobileNavigation />

            <!--Confirm Popup-->
            <Confirm />

            <!--Share Item setup-->
            <ShareCreate/>
            <ShareEdit/>

            <!--Move item setup-->
            <MoveItem/>

            <!--Mobile Menu-->
            <MobileMenu/>

            <!--Navigation Sidebar-->
            <MenuBar/>

            <!--Toastr-->
            <ToastrWrapper/>

            <!--File page-->
            <keep-alive :include="['Admin', 'Users']">
                <router-view :class="{'is-scaled-down': isScaledDown}"/>
            </keep-alive>
        </div>

        <router-view v-if="isGuestLayout"/>

        <CookieDisclaimer />

        <!--Background vignette-->
        <Vignette/>
    </div>
</template>

<script>
    import ToastrWrapper from '@/components/Others/Notifications/ToastrWrapper'
    import FileFullPreview from '@/components/FilesView/FileFullPreview'
    import MobileNavigation from '@/components/Others/MobileNavigation'
    import CookieDisclaimer from '@/components/Others/CookieDisclaimer'
    import MobileMenu from '@/components/FilesView/MobileMenu'
    import ShareCreate from '@/components/Others/ShareCreate'
    import Confirm from '@/components/Others/Popup/Confirm'
    import ShareEdit from '@/components/Others/ShareEdit'
    import MoveItem from '@/components/Others/MoveItem'
    import Vignette from '@/components/Others/Vignette'
    import MenuBar from '@/components/Sidebar/MenuBar'
    import Alert from '@/components/FilesView/Alert'
    import {includes} from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from "./bus"

    export default {
        name: 'app',
        components: {
            MobileNavigation,
            CookieDisclaimer,
            FileFullPreview,
            ToastrWrapper,
            ShareCreate,
            MobileMenu,
            ShareEdit,
            MoveItem,
            Vignette,
            Confirm,
            MenuBar,
            Alert,
        },
        computed: {
            ...mapGetters([
                'isLogged', 'isGuest', 'config'
            ]),
            isGuestLayout() {
                return (includes([
                        'InstallationDisclaimer',
                        'SubscriptionService',
                        'StripeCredentials',
                        'SubscriptionPlans',
                        'ForgottenPassword',
                        'CreateNewPassword',
                        'EnvironmentSetup',
                        'VerifyByPassword',
                        'SaaSLandingPage',
                        'BillingsDetail',
                        'NotFoundShared',
                        'AdminAccount',
                        'PurchaseCode',
                        'DynamicPage',
                        'SharedPage',
                        'ContactUs',
                        'AppSetup',
                        'Database',
                        'Upgrade',
                        'SignIn',
                        'SignUp',
                    ], this.$route.name)
                )
            }
        },
        data() {
            return {
                isScaledDown: false,
            }
        },
        beforeMount() {

            // Store config to vuex
            this.$store.commit('INIT', {
                authCookie: this.$root.$data.config.hasAuthCookie,
                config: this.$root.$data.config,
                rootDirectory: {
                    name: this.$t('locations.home'),
                    location: 'base',
                    unique_id: 0,
                }
            })

            // Get installation state
            let installation = this.$root.$data.config.installation

            // Redirect to database verify code
            if ( installation === 'setup-database') {
                this.$router.push({name: 'PurchaseCode'})
            }

            // Redirect to starting installation process
            if ( installation === 'setup-disclaimer' ) {
                this.$router.push({name: 'InstallationDisclaimer'})
            }
        },
        mounted() {
            // Handle mobile navigation scale animation
            events.$on('show:mobile-navigation', () => this.isScaledDown = true)
            events.$on('hide:mobile-navigation', () => this.isScaledDown = false)
            events.$on('mobileMenu:show', () => this.isScaledDown = true)
            events.$on('fileItem:deselect', () => this.isScaledDown = false)
        }
    }
</script>

<style lang="scss">
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap');
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    [v-cloak],
    [v-cloak] > * {
        display: none
    }

    * {
        outline: 0;
        margin: 0;
        padding: 0;
        font-family: 'Nunito', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        box-sizing: border-box;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        font-size: 16px;
        text-decoration: none;
        color: $text;
    }

    #auth {
        width: 100%;
        height: 100%;
    }

    #vue-file-manager {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow-y: auto;
        scroll-behavior:smooth;
    }

    @media only screen and (max-width: 690px) {

        .is-scaled-down {
            @include transform(scale(0.95));
        }
    }

    // Dark mode support
    @media (prefers-color-scheme: dark) {

        * {
            color: $dark_mode_text_primary;
        }

        body, html {
            background: $dark_mode_background;
            color: $dark_mode_text_primary;

            img {
                opacity: .95;
            }
        }
    }
</style>
