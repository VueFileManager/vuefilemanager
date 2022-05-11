<template>
    <div @keydown.esc="closeOverlays" tabindex="-1" class="min-w-[320px]">
        <!--UI components-->
        <Alert />
        <ToasterWrapper />
        <CookieDisclaimer />
		<RemoteUploadProgress />

        <!--Show spinner before translations is loaded-->
        <Spinner v-if="!isLoaded" />

        <!--Show warning bar when user functionality is restricted-->
        <RestrictionWarningBar />

		<div :class="{'lg:flex': isSidebarNavigation}">
			<SidebarNavigation v-if="isSidebarNavigation" />
			<router-view v-if="isLoaded" />
		</div>

        <!--Background under popups-->
        <Vignette />
    </div>
</template>

<script>
import RestrictionWarningBar from './components/Subscription/RestrictionWarningBar'
import RemoteUploadProgress from "./components/RemoteUpload/RemoteUploadProgress"
import ToasterWrapper from './components/Toaster/ToasterNotifications'
import SidebarNavigation from "./components/Sidebar/SidebarNavigation"
import CookieDisclaimer from './components/UI/Others/CookieDisclaimer'
import Spinner from './components/UI/Others/Spinner'
import Vignette from './components/UI/Others/Vignette'
import Alert from './components/Popups/Alert'
import { mapGetters } from 'vuex'
import { events } from './bus'

export default {
    name: 'App',
    components: {
        RestrictionWarningBar,
		RemoteUploadProgress,
		SidebarNavigation,
        CookieDisclaimer,
        ToasterWrapper,
        Vignette,
        Spinner,
        Alert,
    },
    data() {
        return {
            isLoaded: false,
			isSidebarNavigation: undefined,
        }
    },
    computed: {
        ...mapGetters(['config', 'user']),
    },
    watch: {
        'config.defaultThemeMode': function () {
            this.handleDarkMode()
        },
		'$route' () {
			let section = this.$router.currentRoute.fullPath.split('/')[1]
			const app = document.getElementsByTagName('body')[0]

			// Set background color via theme setup
			if (['admin', 'user'].includes(section)) {
				app.classList.add('dark:bg-dark-background', 'bg-light-background')
			} else {
				app.classList.remove('dark:bg-dark-background', 'bg-light-background')
			}

			// Set sidebar navigation visibility
			this.isSidebarNavigation = ['admin', 'user', 'platform'].includes(section)
		}
    },
    methods: {
		closeOverlays() {
			events.$emit('popup:close')
			events.$emit('popover:close')

			this.$store.commit('CLOSE_NOTIFICATION_CENTER')
		},
        spotlightListener(e) {
			if (e.key === 'k' && e.metaKey || e.key === 'k' && e.ctrlKey) {
				e.preventDefault()
				events.$emit('spotlight:show');
			}
        },
        handleDarkMode() {
            const app = document.getElementsByTagName('html')[0]
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)')

            if (this.config.defaultThemeMode === 'dark') {
                app.classList.add('dark')
                this.$store.commit('UPDATE_DARK_MODE_STATUS', true)
            } else if (this.config.defaultThemeMode === 'light') {
                app.classList.remove('dark')
                this.$store.commit('UPDATE_DARK_MODE_STATUS', false)
            } else if (this.config.defaultThemeMode === 'system' && prefersDarkScheme.matches) {
                app.classList.add('dark')
                this.$store.commit('UPDATE_DARK_MODE_STATUS', true)
            } else if (this.config.defaultThemeMode === 'system' && !prefersDarkScheme.matches) {
                app.classList.remove('dark')
                this.$store.commit('UPDATE_DARK_MODE_STATUS', false)
            }
        },
    },
    beforeMount() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            this.handleDarkMode()
        })

        // Commit config
        this.$store.commit('INIT', {
            config: this.$root.$data.config,
        })

        // Redirect to setup wizard
        if (this.$root.$data.config.installation === 'installation-needed') {
            this.isLoaded = true

            if (window.location.pathname.split('/')[1] !== 'setup-wizard') {
                this.$router.push({ name: 'StatusCheck' })
            }
        } else {
            this.$store.dispatch('getLanguageTranslations', this.$root.$data.config.locale).then(() => {
                this.isLoaded = true
            })
        }

		// Go to sign in page if homepage is disabled
		if (!this.$root.$data.config.allowHomepage && window.location.pathname === '/') {
			this.$router.push({ name: 'SignIn' })
		}
    },
    created() {
        if (this.$isWindows()) {
            document.body.classList.add('windows')
        }

        window.addEventListener('keydown', this.spotlightListener)
    },
    destroyed() {
        window.removeEventListener('keydown', this.spotlightListener)
    },
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap');
@import '../sass/vuefilemanager/variables';
@import '../sass/vuefilemanager/mixins';

input:-webkit-autofill {
    transition-delay: 999999999999s;
}

[v-cloak],
[v-cloak] > * {
    display: none;
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

.vue-feather {
    path,
    circle,
    line,
    rect,
    polyline,
    ellipse,
    polygon {
        color: inherit;
    }
}

// Dark mode
.dark {
    * {
        color: $dark_mode_text_primary;
    }

    body,
    html {
        background: $dark_mode_background;
        color: $dark_mode_text_primary;

        img {
            opacity: 0.95;
        }
    }
}
</style>
