require('./bootstrap')
import Vue from 'vue'
import i18n from './i18n'
import VueRouter from 'vue-router'
import router from './router'
import App from './App.vue'
import store from './store'
import { events } from './bus'

import Adsense from 'vue-google-adsense/dist/Adsense.min.js'
import InArticleAdsense from 'vue-google-adsense/dist/InArticleAdsense.min.js'
import InFeedAdsense from 'vue-google-adsense/dist/InFeedAdsense.min.js'

Vue.use(require('vue-script2'))

Vue.use(Adsense)
Vue.use(InArticleAdsense)
Vue.use(InFeedAdsense)

import SubscriptionHelpers from './helpers/SubscriptionHelpers'
import ValidatorHelpers from './helpers/ValidatorHelpers'
import functionHelpers from './helpers/functionHelpers'
import AlertHelpers from './helpers/AlertHelpers'
import itemHelpers from './helpers/itemHelpers'
import { VueReCaptcha } from 'vue-recaptcha-v3'

Vue.use(VueRouter)
Vue.use(SubscriptionHelpers)
Vue.use(ValidatorHelpers)
Vue.use(functionHelpers)
Vue.use(AlertHelpers)
Vue.use(itemHelpers)

// ReCaptcha configuration
if (config.allowedRecaptcha) {
    Vue.use(VueReCaptcha, {
        siteKey: config.recaptcha_client_id,
        loaderOptions: {
            autoHideBadge: true,
        },
    })
}

Vue.config.productionTip = false

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

let vueFileManager = new Vue({
    i18n,
    store,
    router,
    data: {
        config,
    },
    render: (h) => h(App),
}).$mount('#app')
