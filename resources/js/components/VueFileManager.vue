<template>
    <div id="vue-file-manager" :class="appSize">

        <router-view />
        <!--Authentication pages-->
        <!--<Auth v-if="isGuest" />-->

        <!--<div v-if="isLogged" id="auth">

            <div id="popups">

                &lt;!&ndash;System alerts&ndash;&gt;
                <Alert />

                &lt;!&ndash;Popup&ndash;&gt;
                <PopupMoveItem />

                &lt;!&ndash;Mobile Menu&ndash;&gt;
                <MobileOptionList />

                &lt;!&ndash;Background vignette&ndash;&gt;
                <Vignette />
            </div>

            <div id="application-wrapper">

                &lt;!&ndash;Navigation Sidebar&ndash;&gt;
                <Sidebar />

                &lt;!&ndash;User settings Page&ndash;&gt;
                <UserSettings v-if="isCurrentView('user-settings')"/>

                &lt;!&ndash;File page&ndash;&gt;
                <FilesView v-if="isCurrentView('files')"/>
            </div>
        </div>-->
    </div>
</template>

<script>
    import MobileOptionList from '@/components/VueFileManagerComponents/FilesView/MobileOptionList'
    import PopupMoveItem from '@/components/VueFileManagerComponents/Others/PopupMoveItem'
    import UserSettings from '@/components/VueFileManagerComponents/UserSettings'
    import Vignette from '@/components/VueFileManagerComponents/Others/Vignette'
    import Alert from '@/components/VueFileManagerComponents/FilesView/Alert'
    import FilesView from '@/components/VueFileManagerComponents/FilesView'
    import Sidebar from '@/components/VueFileManagerComponents/Sidebar'
    import Auth from '@/components/VueFileManagerComponents/Auth'
    import {ResizeSensor} from 'css-element-queries'
    import {mapGetters} from 'vuex'
    import {events} from "../bus";

    export default {
        name: 'VueFileManager',
        components: {
            MobileOptionList,
            PopupMoveItem,
            UserSettings,
            FilesView,
            Vignette,
            Sidebar,
            Alert,
            Auth,
        },
        computed: {
            ...mapGetters([
                'isLogged', 'isGuest', 'currentView', 'appSize'
            ]),
        },
        methods: {
            isCurrentView(view) {
                return this.currentView === view
            },
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
        position: relative;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    #application-wrapper {
        display: flex;
        height: 100%;

        #content {
            position: relative;
            width: 100%;
        }
    }

</style>
