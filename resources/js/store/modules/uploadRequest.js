import router from '../../router'
import { events } from '../../bus'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    uploadRequest: undefined,
}

const actions = {
    getUploadRequestFolder: ({ commit, getters }, id) => {
        commit('START_LOADING_VIEW')

        return new Promise((resolve, reject) => {
            axios
                .get(`/api/file-request/${router.currentRoute.params.token}/browse/${id || 'all'}${getters.sorting.URI}`)
                .then((response) => {
                    commit('SET_CURRENT_FOLDER', response.data.meta.root)
                    commit('SET_PAGINATOR', response.data.meta.paginate)
                    commit('STOP_LOADING_VIEW')
                    commit('ADD_NEW_ITEMS', response.data.data)

                    events.$emit('scrollTop')

                    resolve(response)
                })
                .catch((error) => {
                    Vue.prototype.$isSomethingWrong()

                    reject(error)
                })
        })
    },
    getUploadRequestDetail: ({ commit }) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/file-request/${router.currentRoute.params.token}`)
                .then((response) => {
                    resolve(response)

                    // Stop loading spinner
                    if (['active', 'filled', 'expired'].includes(response.data.data.attributes.status) )
                        commit('STOP_LOADING_VIEW')

                    commit('SET_UPLOAD_REQUEST', response.data)

                    // Set current folder if exist
                    if (! router.currentRoute.params.id) {
                        commit('SET_CURRENT_FOLDER', response.data.data.relationships.folder)
                    }
                })
                .catch((error) => {
                    Vue.prototype.$isSomethingWrong()

                    reject(error)
                })
        })
    },
    closeUploadRequest: ({ commit }) => {
        axios
            .delete(`/api/file-request/${router.currentRoute.params.token}`)
            .then((response) => {
                commit('START_LOADING_VIEW')
                commit('STOP_LOADING_VIEW')
                commit('SET_UPLOAD_REQUEST_AS_FILLED')
            })
            .catch(() => this.$isSomethingWrong())
    },
}

const mutations = {
    SET_UPLOAD_REQUEST(state, payload) {
        state.uploadRequest = payload
    },
    SET_UPLOAD_REQUEST_AS_FILLED(state) {
        state.uploadRequest.data.attributes.status = 'filled'
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
