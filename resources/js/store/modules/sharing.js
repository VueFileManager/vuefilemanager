import i18n from '@/i18n/index'
import router from '@/router'
import {events} from '@/bus'
import axios from 'axios'

const defaultState = {
    permissionOptions: [
        {
            label: 'Can edit and upload files',
            value: 'editor',
            icon: 'user-edit',
        },
        {
            label: 'Can only view and download',
            value: 'visitor',
            icon: 'user',
        },
    ],
    sharedDetail: undefined,
    sharedFile: undefined,
}
const actions = {
    browseShared: ({commit, state, getters}, [folder, back = false, init = false]) => {
        commit('LOADING_STATE', true)
        commit('FLUSH_DATA')

        // Clear search
        if (getters.isSearching) {
            commit('CHANGE_SEARCHING_STATE', false)
            events.$emit('clear-query')
        }

        // Create current folder for history
        let currentFolder = {
            name: folder.name,
            unique_id: folder.unique_id,
            location: 'public'
        }

        let route = getters.sharedDetail.protected ? '/api/browse-private/' : '/api/browse-public/'

        return new Promise((resolve, reject) => {
            axios
                .get(route + currentFolder.unique_id, {
                    params: {
                        token: router.currentRoute.params.token
                    }
                })
                .then(response => {

                    commit('LOADING_STATE', false)
                    commit('GET_DATA', response.data)
                    commit('STORE_CURRENT_FOLDER', currentFolder)
                    events.$emit('scrollTop')

                    if (back) {
                        commit('REMOVE_BROWSER_HISTORY')

                    } else {
                        if (!init)
                            commit('ADD_BROWSER_HISTORY', currentFolder)
                    }

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
    getSingleFile: ({commit, state}) => {

        let route = state.sharedDetail.protected ? '/api/file-private/' : '/api/file-public/'

        axios.get(route + router.currentRoute.params.token)
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
