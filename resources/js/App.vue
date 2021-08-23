<template>
    <div id="vuefilemanager" @click="unClick" v-cloak>

        <!--UI components-->
        <Alert />
        <ToasterWrapper />
        <CookieDisclaimer />

        <!--Show spinner before translations is loaded-->
        <Spinner v-if="! isLoaded"/>

        <!--App view-->
        <router-view v-if="isLoaded" />

        <Vignette />
    </div>
</template>

<script>
import ToasterWrapper from '/resources/js/components/Others/Notifications/ToasterWrapper'
import CookieDisclaimer from '/resources/js/components/Others/CookieDisclaimer'
import Spinner from '/resources/js/components/FilesView/Spinner'
import Vignette from '/resources/js/components/Others/Vignette'
import Alert from '/resources/js/components/FilesView/Alert'
import {mapGetters} from 'vuex'
import {events} from './bus'

export default {
    name: 'app',
    components: {
        CookieDisclaimer,
        ToasterWrapper,
        Vignette,
        Spinner,
        Alert
    },
    data() {
        return {
            isLoaded: false
        }
    },
	computed: {
		...mapGetters([
			'isDarkMode'
		]),
	},
	watch: {
		isDarkMode() {
			this.toggleDarkMode()
		}
	},
    methods: {
        unClick() {
            events.$emit('unClick')
        },
		toggleDarkMode() {
			const webApp = document.getElementsByTagName("html")[0];

			webApp.classList.toggle("dark-mode");
		}
    },
    beforeMount() {

		// Set dark/light mode by user settings
		if (localStorage.hasOwnProperty('is_dark_mode')) {
			if (this.isDarkMode) this.toggleDarkMode()
		}

		// Proceed dark/light mode by system settings
		if (! localStorage.hasOwnProperty('is_dark_mode')) {
			const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

			// Set up initial dark/light mode on app loading
			if (prefersDarkScheme.matches) this.toggleDarkMode()

			// Watch for dark/light mode changes on os system layer
			prefersDarkScheme.addEventListener('change', () => this.toggleDarkMode());
		}

        // Get installation state
        let installation = this.$root.$data.config.installation

        if (['setup-disclaimer', 'setup-database'].includes(installation))
            this.isLoaded = true

        // Redirect to database verify code
        if (installation === 'setup-database')
            this.$router.push({name: 'StatusCheck'})

        // Redirect to starting installation process
        if (installation === 'setup-disclaimer')
            this.$router.push({name: 'InstallationDisclaimer'})

        if (installation === 'setup-done')
            this.$store.dispatch('getLanguageTranslations', this.$root.$data.config.locale)
                .then(() => {
                    this.isLoaded = true

                    // Store config to vuex
                    this.$store.commit('INIT', {
                        config: this.$root.$data.config,
                        rootDirectory: {
                            name: this.$t('locations.home'),
                            location: 'base',
                            id: undefined
                        }
                    })
                })
    },
    mounted() {
    	if (this.$isWIndows()) {
			document.body.classList.add('windows')
		}
    }
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap');
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

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
.dark-mode {

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
