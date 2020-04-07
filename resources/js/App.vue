<template>
    <div id="vue-file-manager" :class="appSize">
        <div id="popups">

            <!--System alerts-->
            <Alert />

            <!--Popup-->
            <PopupMoveItem />

            <!--Mobile Menu-->
            <MobileOptionList />

            <!--Background vignette-->
            <Vignette />
        </div>

        <div id="application-wrapper" v-if="isLogged">

            <!--Navigation Sidebar-->
            <Sidebar />

            <!--File page-->
            <router-view />
        </div>

        <router-view v-if="! isLogged" />
    </div>
</template>

<script>
    import MobileOptionList from '@/components/VueFileManagerComponents/FilesView/MobileOptionList'
    import PopupMoveItem from '@/components/VueFileManagerComponents/Others/PopupMoveItem'
    import Vignette from '@/components/VueFileManagerComponents/Others/Vignette'
    import Alert from '@/components/VueFileManagerComponents/FilesView/Alert'
    import Sidebar from '@/components/VueFileManagerComponents/Sidebar/Sidebar'
    import {ResizeSensor} from 'css-element-queries'
    import {mapGetters} from 'vuex'

    export default {
        name: 'app',
        components: {
            MobileOptionList,
            PopupMoveItem,
            Vignette,
            Sidebar,
            Alert,
        },
        computed: {
            ...mapGetters([
                'appSize', 'isLogged', 'isGuest'
            ]),
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
