import i18n from '@/i18n/index'
import router from '@/router'
import {events} from '@/bus'
import axios from 'axios'
import Vue from "vue";

const defaultState = {
    permissionOptions: [
        {
            label: i18n.t('shared.editor'),
            value: 'editor',
            icon: 'user-edit',
        },
        {
            label: i18n.t('shared.visitor'),
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

        // Clear search
        if (getters.isSearching) {
            commit('CHANGE_SEARCHING_STATE', false)
            events.$emit('clear-query')
        }

        if (! payload.back && !payload.sorting)
            commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)

        payload.folder.location = 'public'

        let route = getters.sharedDetail.protected
            ? '/api/browse/folders/' + payload.folder.id + '/private'
            : '/api/browse/folders/' + payload.folder.id + '/public/' + router.currentRoute.params.token

        return new Promise((resolve, reject) => {
            axios
                .get(route + getters.sorting.URI)
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
    shareCancel: ({commit, getters} , singleItem) => {
        return new Promise((resolve, reject) => {

            let tokens = []
            let items = [singleItem]

            if(!singleItem) {
                items = getters.fileInfoDetail
            }

            items.forEach(data => {
                tokens.push(data.shared.token)
            })

            axios
                .post('/api/share/cancel', {
                    _method: 'post',
                    tokens: tokens
                })
                .then(() => {

                    items.forEach(item => {

                        // Remove item from file browser
                        if ( getters.currentFolder , getters.currentFolder.location === 'shared' ) {
                            commit('REMOVE_ITEM', item.id)
                        }

                        // Flush shared data
                        commit('FLUSH_SHARED', item.id)

                        commit('CLEAR_FILEINFO_DETAIL')
                    })
                    resolve(true)

                })
                .catch((error) => {
                    Vue.prototype.$isSomethingWrong()

                    reject(error)
                })
        })
    },
    getSingleFile: ({commit, state}) => {

        let route = state.sharedDetail.protected
            ? '/api/files/private'
            : '/api/files/' + router.currentRoute.params.token + '/public'

        axios.get(route)
            .then(response => {
                commit('STORE_SHARED_FILE', response.data)
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
