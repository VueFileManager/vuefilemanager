import Vuex from 'vuex'
import Vue from 'vue'

import fileFunctions from './modules/fileFunctions'
import fileBrowser from './modules/fileBrowser'
import emojisList from './modules/emojisList'
import userAuth from './modules/userAuth'
import sharing from './modules/sharing'
import app from './modules/app'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        fileFunctions,
        fileBrowser,
        emojisList,
        userAuth,
        sharing,
        app,
    }
})