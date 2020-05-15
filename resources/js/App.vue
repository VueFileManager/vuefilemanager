<template>
    <div id="vue-file-manager" :class="appSize" v-cloak>

        <!--System alerts-->
        <Alert/>

        <div id="application-wrapper" v-if="layout === 'authorized'">

            <MobileNavigation />

            <!--Share Item setup-->
            <ShareCreate/>
            <ShareEdit/>

            <!--Move item setup-->
            <MoveItem/>

            <!--Mobile Menu-->
            <MobileMenu/>

            <!--Navigation Sidebar-->
            <MenuBar/>

            <!--File page-->
            <router-view :class="{'is-scaled-down': isScaledDown}"/>
        </div>

        <router-view v-if="layout === 'unauthorized'"/>

        <!--Background vignette-->
        <Vignette/>
    </div>
</template>

<script>
    import MobileNavigation from '@/components/Others/MobileNavigation'
    import MobileMenu from '@/components/FilesView/MobileMenu'
    import ShareCreate from '@/components/Others/ShareCreate'
    import ShareEdit from '@/components/Others/ShareEdit'
    import MoveItem from '@/components/Others/MoveItem'
    import Vignette from '@/components/Others/Vignette'
    import MenuBar from '@/components/Sidebar/MenuBar'
    import Alert from '@/components/FilesView/Alert'
    import {ResizeSensor} from 'css-element-queries'
    import {includes} from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from "./bus"

    export default {
        name: 'app',
        components: {
            MobileNavigation,
            ShareCreate,
            MobileMenu,
            ShareEdit,
            MoveItem,
            Vignette,
            MenuBar,
            Alert,
        },
        computed: {
            ...mapGetters([
                'appSize', 'isLogged', 'isGuest'
            ]),
            layout() {
                if (includes(['VerifyByPassword', 'SharedPage', 'NotFoundShared', 'SignIn', 'SignUp', 'ForgottenPassword', 'CreateNewPassword'], this.$route.name)) {
                    return 'unauthorized'
                }

                return 'authorized'
            }
        },
        data() {
            return {
                isScaledDown: false,
            }
        },
        methods: {
            handleAppResize() {
                let appView = document.getElementById('vue-file-manager')
                    .offsetWidth

                if (appView <= 690)
                    this.$store.commit('SET_APP_WIDTH', 'small')
                if (appView > 690 && appView < 960)
                    this.$store.commit('SET_APP_WIDTH', 'medium')
                if (appView > 960)
                    this.$store.commit('SET_APP_WIDTH', 'large')
            },
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
        },
        mounted() {
            // Handle VueFileManager width
            var VueFileManager = document.getElementById('vue-file-manager');
            new ResizeSensor(VueFileManager, this.handleAppResize);

            // Handle mobile navigation scale animation
            events.$on('show:mobile-navigation', () => this.isScaledDown = true)
            events.$on('hide:mobile-navigation', () => this.isScaledDown = false)
            events.$on('mobileMenu:show', () => this.isScaledDown = true)
            events.$on('fileItem:deselect', () => this.isScaledDown = false)
        }
    }
</script>

<style lang="scss">
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;900&display=swap');
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
    }

    @media only screen and (max-width: 690px) {

        .is-scaled-down {
            @include transform(scale(0.95));
        }
    }

    // Dark mode support
    @media (prefers-color-scheme: dark) {

        body, html {
            background: $dark_mode_background;
            color: $dark_mode_text_primary;

            img {
                opacity: .8;
            }
        }
    }
</style>
