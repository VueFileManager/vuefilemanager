<template>
    <div id="vue-file-manager" :class="appSize">

        <!--System alerts-->
        <Alert />

        <div id="application-wrapper" v-if="layout === 'authorized'">

            <!--Share Item setup-->
            <ShareCreate />
            <ShareEdit />

            <!--Move item setup-->
            <MoveItem />

            <!--Mobile Menu-->
            <MobileMenu />

            <!--Navigation Sidebar-->
            <Sidebar/>

            <!--File page-->
            <router-view/>
        </div>

        <router-view v-if="layout === 'unauthorized'"/>

        <!--Background vignette-->
        <Vignette />
    </div>
</template>

<script>
    import MobileMenu from '@/components/FilesView/MobileMenu'
    import ShareCreate from '@/components/Others/ShareCreate'
    import ShareEdit from '@/components/Others/ShareEdit'
    import MoveItem from '@/components/Others/MoveItem'
    import Vignette from '@/components/Others/Vignette'
    import Sidebar from '@/components/Sidebar/Sidebar'
    import Alert from '@/components/FilesView/Alert'
    import {ResizeSensor} from 'css-element-queries'
    import { includes } from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from "./bus"

    export default {
        name: 'app',
        components: {
            ShareCreate,
            MobileMenu,
            ShareEdit,
            MoveItem,
            Vignette,
            Sidebar,
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
            this.$store.commit('SET_AUTHORIZED', this.$root.$data.config.hasAuthCookie)
            this.$store.commit('SET_CONFIG', this.$root.$data.config)
        },
        mounted() {
            // Handle VueFileManager width
            var VueFileManager = document.getElementById('vue-file-manager');
            new ResizeSensor(VueFileManager, this.handleAppResize);
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";

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
</style>
