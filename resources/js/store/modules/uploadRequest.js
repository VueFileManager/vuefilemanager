import router from '../../router'
import { events } from '../../bus'
import i18n from '../../i18n'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    uploadRequest: undefined,
}

const actions = {}

const mutations = {
    SET_UPLOAD_REQUEST(state, payload) {
        state.uploadRequest = payload
    },
}

const getters = {
    uploadRequest: (state) => state.uploadRequest,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
