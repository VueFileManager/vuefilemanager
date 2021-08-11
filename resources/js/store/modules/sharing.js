import i18n from '/resources/js/i18n/index'
import router from '/resources/js/router'
import {events} from '/resources/js/bus'
import axios from 'axios'
import Vue from "vue";

const defaultState = {
    permissionOptions: [
        {
            label: 'shared.editor',
            value: 'editor',
            icon: 'user-edit',
        },
        {
            label: 'shared.visitor',
            value: 'visitor',
            icon: 'user',
        },
    ],
    sharedDetail: undefined,
    sharedFile: undefined,
}
const actions = {
    browseShared: ({commit, getters}, [payload]) => {
        commit('LOADING_STATE', {loading: true, data: []})

        if (payload.init)
            commit('FLUSH_FOLDER_HISTORY')

        if (! payload.back && !payload.sorting)
            commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)

        payload.folder.location = 'public'

        return new Promise((resolve, reject) => {
            axios
                .get(`/api/browse/folders/${payload.folder.id}/${router.currentRoute.params.token}${getters.sorting.URI}`)
                .then(response => {
                    commit('LOADING_STATE', {loading: false, data: response.data})
                    commit('STORE_CURRENT_FOLDER', payload.folder)
                    events.$emit('scrollTop')

                    if (payload.back && !payload.sorting)
                        commit('REMOVE_BROWSER_HISTORY')

                    resolve(response)
                })
                .catch((error) => {
                    // Show error message
                    events.$emit('alert:open', {
                        title: i18n.t('popup_error.title'),
                        message: i18n.t('popup_error.message'),
                    })

                    reject(error)
                })
        })
    },
    getSingleFile: ({commit}) => {

        axios.get(`/api/browse/file/${router.currentRoute.params.token}`)
            .then(response => {
                commit('STORE_SHARED_FILE', response.data)
            })
    },
    getShareDetail: ({commit, state}, token) => {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/browse/share/${token}`)
                .then(response => {
                    resolve(response)

                    // Commit shared item options
                    commit('SET_SHARED_DETAIL', response.data.data.attributes)
                    commit('SET_PERMISSION', response.data.data.attributes.permission)
                })
                .catch(error => {
                    reject(error)

                    if (error.response.status == 404)
                        router.push({name: 'NotFound'})
                })
        })
    },
    shareCancel: ({commit, getters}, singleItem) => {
        return new Promise((resolve, reject) => {

            let tokens = []
            let items = [singleItem]

            if(!singleItem) {
                items = getters.clipboard
            }

            items.forEach(data => {
                tokens.push(data.shared.token)
            })

            axios
                .post('/api/share/revoke', {
                    _method: 'delete',
                    tokens: tokens
                })
                .then(() => {

                    items.forEach(item => {

                        // Remove item from file browser
                        if ( getters.currentFolder && getters.currentFolder.location === 'shared' ) {
                            commit('REMOVE_ITEM', item.id)
                        }

                        // Flush shared data
                        commit('FLUSH_SHARED', item.id)

                        commit('CLIPBOARD_CLEAR')
                    })
                    resolve(true)

                })
                .catch((error) => {
                    Vue.prototype.$isSomethingWrong()

                    reject(error)
                })
        })
    },
}
const mutations = {
    SET_SHARED_DETAIL(state, data) {
        state.sharedDetail = data
    },
    STORE_SHARED_FILE(state, data) {
        state.sharedFile = data
    }
}
const getters = {
    permissionOptions: state => state.permissionOptions,
    sharedDetail: state => state.sharedDetail,
    sharedFile: state => state.sharedFile,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
