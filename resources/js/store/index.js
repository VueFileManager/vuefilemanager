import Vuex from 'vuex'
import Vue from 'vue'

import filesView from './modules/filesView'
import userAuth from './modules/userAuth'
import app from './modules/app'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        filesView, userAuth, app,
    }
})