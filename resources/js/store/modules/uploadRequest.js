import router from '../../router'
import { events } from '../../bus'
import i18n from '../../i18n'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    uploadRequest: undefined,
}

const actions = {
    getUploadRequestDetail: ({ commit, getters }) => {

        axios.get(`/api/upload-request/${router.currentRoute.params.token}`)
            .then((response) => {

                commit('LOADING_STATE', { loading: false, data: [] })

                commit('SET_UPLOAD_REQUEST', response.data)

                // Set current folder if exist
                if (response.data.data.relationships.folder) {
                    commit('SET_CURRENT_FOLDER', response.data.data.relationships.folder)
                }
            })
    },
}

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
