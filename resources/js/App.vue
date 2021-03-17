<template>
    <div id="vuefilemanager" v-cloak @click="unClick">
        <Alert />

        <router-view />

        <CookieDisclaimer />
        <Vignette />
    </div>
</template>

<script>
import CookieDisclaimer from '@/components/Others/CookieDisclaimer'
import Vignette from '@/components/Others/Vignette'
import Alert from '@/components/FilesView/Alert'
import {events} from './bus'

export default {
    name: 'app',
    components: {
        CookieDisclaimer,
        Vignette,
        Alert
    },
    methods: {
        unClick() {
            events.$emit('unClick')
        }
    },
    beforeMount() {

        // Store config to vuex
        this.$store.commit('INIT', {
            config: this.$root.$data.config,
            rootDirectory: {
                name: this.$t('locations.home'),
                location: 'base',
                id: undefined
            }
        })

        // Get installation state
        let installation = this.$root.$data.config.installation

        // Redirect to database verify code
        if (installation === 'setup-database')
            this.$router.push({name: 'PurchaseCode'})

        // Redirect to starting installation process
        if (installation === 'setup-disclaimer')
            this.$router.push({name: 'InstallationDisclaimer'})
    },
    mounted() {
        this.$checkOS()
    }
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap');
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

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

#vuefilemanager {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    scroll-behavior: smooth;
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
