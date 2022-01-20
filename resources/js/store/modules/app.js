import i18n from '/resources/js/i18n/index'
import axios from "axios"
import Vue from "vue"

const defaultState = {
    isVisibleNavigationBars: localStorage.getItem('is_navigation_bars') !== 'false',
    darkMode: localStorage.getItem('is_dark_mode') === 'true' || false,
    isVisibleSidebar: localStorage.getItem('file_info_visibility') === 'true' || false,
    itemViewType: localStorage.getItem('preview_type') || 'list',
    config: undefined,
    index: undefined,
    requestedPlan: undefined,
    emojis: undefined,
    sorting: {
        sort: localStorage.getItem('sorting') ? JSON.parse(localStorage.getItem('sorting')).sort : 'DESC',
        field: localStorage.getItem('sorting') ? JSON.parse(localStorage.getItem('sorting')).field : 'created_at',
    },
}
const actions = {
    toggleDarkMode: ({commit}, visibility) => {

        // Store dark mode into localStorage
        localStorage.setItem('is_dark_mode', visibility)

        // Change preview
        commit('TOGGLE_DARK_MODE', visibility)
    },
    toggleNavigationBars: ({commit, state}) => {

        // Store dark mode into localStorage
        localStorage.setItem('is_navigation_bars', ! state.isVisibleNavigationBars)

        // Change preview
        commit('TOGGLE_NAVIGATION_BARS')
    },
    togglePreviewType: ({commit, state}, preview) => {
        // Get preview type
        let previewType = preview ? preview : state.itemViewType === 'list' ? 'grid' : 'list';

        // Store preview type to localStorage
        localStorage.setItem('preview_type', previewType)

        // Change preview
        commit('CHANGE_PREVIEW', previewType)
    },
    toggleEmojiType: ({commit, getters}) => {
        let newType = getters.config.defaultEmoji === 'twemoji'
            ? 'applemoji'
            : 'twemoji'

        // Update config
        commit('REPLACE_CONFIG_VALUE', {
            key: 'defaultEmoji',
            value: newType,
        })

        // Update user settings
        Vue.prototype.$updateText('/user/settings', 'emoji_type', newType)
    },
    fileInfoToggle: (context, visibility = undefined) => {
        if (!visibility) {
            if (context.state.isVisibleSidebar) {
                context.commit('FILE_INFO_TOGGLE', false)
            } else {
                context.commit('FILE_INFO_TOGGLE', true)
            }
        } else {
            context.commit('FILE_INFO_TOGGLE', visibility)
        }
    },
    getLanguageTranslations: ({commit, state}, lang) => {
        return new Promise((resolve, reject) => {

            axios.get(`/translations/${lang}`)
                .then(response => {

                    i18n.setLocaleMessage(lang, response.data)
                    i18n.locale = lang

                    resolve(response)
                })
        })
    },
}

const mutations = {
    LOAD_EMOJIS_LIST(state, data) {
        state.emojis = data
    },
    UPDATE_SORTING(state) {
        state.sorting.field = JSON.parse(localStorage.getItem('sorting')).field
        state.sorting.sort = JSON.parse(localStorage.getItem('sorting')).sort
    },
    INIT(state, data) {
        state.config = data.config
    },
    SET_SAAS(state, data) {
        state.config.isSaaS = data
    },
    FILE_INFO_TOGGLE(state, isVisible) {
        state.isVisibleSidebar = isVisible

        localStorage.setItem('file_info_visibility', isVisible)
    },
    SET_AUTHORIZED(state, value) {
        state.config.isAuthenticated = value
    },
    SET_INDEX_CONTENT(state, data) {
        state.index = data
    },
    CHANGE_PREVIEW(state, type) {
        state.itemViewType = type
    },
    TOGGLE_DARK_MODE(state, visibility) {
        state.darkMode = visibility
    },
    TOGGLE_NAVIGATION_BARS(state) {
        state.isVisibleNavigationBars = ! state.isVisibleNavigationBars
    },
    STORE_REQUESTED_PLAN(state, plan) {
        state.requestedPlan = plan
    },
    REPLACE_CONFIG_VALUE(state, {key, value}) {
        state.config[key] = value
    },
    SET_SOCIAL_LOGIN_CONFIGURED(state, service) {
        if (service === 'facebook') {
            state.config.allowedFacebookLogin = true
            state.config.isFacebookLoginConfigured = true
        }

        if (service === 'google') {
            state.config.allowedGoogleLogin = true
            state.config.isGoogleLoginConfigured = true
        }

        if (service === 'github') {
            state.config.allowedGithubLogin = true
            state.config.isGithubLoginConfigured = true
        }
    },
    SET_STRIPE_CREDENTIALS(state, data) {
        state.config.stripe_public_key = data.key
        state.config.isStripe = true
    },
    SET_PAYSTACK_CREDENTIALS(state, data) {
        state.config.paystack_public_key = data.key
        state.config.isPaystack = true
    },
    SET_PAYPAL_CREDENTIALS(state, data) {
        state.config.paypal_client_id = data.key
        state.config.isPayPal = true
    },
}

const getters = {
    isVisibleNavigationBars: state => state.isVisibleNavigationBars,
    isVisibleSidebar: state => state.isVisibleSidebar,
    itemViewType: state => state.itemViewType,
    requestedPlan: state => state.requestedPlan,
    api: state => state.config.api,
    config: state => state.config,
    emojis: state => state.emojis,
    index: state => state.index,
    isDarkMode: state => state.darkMode,
    sorting: (state) => {
        return {sorting: state.sorting, URI: '?sort=' + state.sorting.field + '&direction=' + state.sorting.sort}
    },
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
