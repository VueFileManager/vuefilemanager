import './bootstrap'
import { createApp } from 'vue'
import i18n from './i18n'
import router from './router'
import { createGtag } from 'vue-gtag'
import App from './App.vue'
import store from './store'
import { events } from './bus'
import { globals, installGlobals } from './globals'

import SubscriptionHelpers from './helpers/SubscriptionHelpers'
import ValidatorHelpers from './helpers/ValidatorHelpers'
import functionHelpers from './helpers/functionHelpers'
import AlertHelpers from './helpers/AlertHelpers'
import itemHelpers from './helpers/itemHelpers'
import { VueReCaptcha } from 'vue-recaptcha-v3'

const app = createApp(App)

app.use(store)
app.use(router)
app.use(i18n)

app.use(SubscriptionHelpers)
app.use(ValidatorHelpers)
app.use(functionHelpers)
app.use(AlertHelpers)
app.use(itemHelpers)

// Make $xxx helpers available in components (this.$xxx) and expose app services to helpers
// that are called outside of components (globals.$xxx).
Object.defineProperties(globals, {
    $store: { get: () => store },
    $router: { get: () => router },
    $route: { get: () => router.currentRoute.value },
    $t: {
        get:
            () =>
            (...args) =>
                i18n.global.t(...args),
    },
})
installGlobals(app)

// Google Analytics implementation
if (config.googleAnalytics) {
    app.use(
        createGtag({
            tagId: config.googleAnalytics,
            pageTracker: {
                router,
            },
        })
    )
}

// ReCaptcha configuration
if (config.allowedRecaptcha) {
    app.use(VueReCaptcha, {
        siteKey: config.recaptcha_client_id,
        loaderOptions: {
            autoHideBadge: true,
        },
    })
}

// Handle position of Drag & Drop Ghost
document.addEventListener(
    'drag',
    (event) => {
        let multiSelect = document.getElementById('drag-ui')

        multiSelect.style.top = event.clientY + 20 + 'px'
        multiSelect.style.left = event.clientX + 'px'
    },
    false
)

// Handle for drop
document.addEventListener(
    'dragend',
    () => {
        events.$emit('drop')
    },
    false
)

app.mount('#app')
