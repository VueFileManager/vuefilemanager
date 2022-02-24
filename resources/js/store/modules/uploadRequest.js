import router from '../../router'
import { events } from '../../bus'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    uploadRequest: undefined,
}

const actions = {
    getUploadRequestFolder: ({ commit, getters }, id) => {
        commit('LOADING_STATE', { loading: true, data: [] })

        return new Promise((resolve, reject) => {
            axios
                .get(`/api/upload-request/${router.currentRoute.params.token}/browse/${id}${getters.sorting.URI}`)
                .then((response) => {
                    let folders = response.data.folders.data
                    let files = response.data.files.data

                    commit('LOADING_STATE', {
                        loading: false,
                        data: folders.concat(files),
                    })
                    commit('SET_CURRENT_FOLDER', response.data.root)

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
            axios.get(`/api/upload-request/${router.currentRoute.params.token}`)
                .then((response) => {
                    resolve(response)

                    // Stop loading spinner
                    if (['active', 'filled', 'expired'].includes(response.data.data.attributes.status) )
                        commit('LOADING_STATE', { loading: false, data: [] })

                    commit('SET_UPLOAD_REQUEST', response.data)

                    // Set current folder if exist
                    if (! router.currentRoute.params.id) {
                        commit('SET_CURRENT_FOLDER', response.data.data.relationships.folder)
                    }
                })
        })
    },
    closeUploadRequest: ({ commit }) => {
        axios
            .delete(`/api/upload-request/${router.currentRoute.params.token}`)
            .then((response) => {
                commit('LOADING_STATE', { loading: false, data: [] })
                commit('SET_UPLOAD_REQUEST', response.data)
            })
            .catch(() => this.$isSomethingWrong())
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
