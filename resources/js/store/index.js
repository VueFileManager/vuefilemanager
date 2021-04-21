import Vuex from 'vuex'
import Vue from 'vue'

import oasisInvoices from './modules/oasisInvoices'
import fileFunctions from './modules/fileFunctions'
import fileBrowser from './modules/fileBrowser'
import userAuth from './modules/userAuth'
import sharing from './modules/sharing'
import app from './modules/app'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        oasisInvoices,
        fileFunctions,
        fileBrowser,
        userAuth,
        sharing,
        app,
    }
})