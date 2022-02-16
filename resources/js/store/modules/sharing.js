import { events } from '../../bus'
import router from '../../router'
import axios from 'axios'
import Vue from 'vue'

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
    getSharedFolder: ({ commit, getters }, id) => {
        commit('LOADING_STATE', { loading: true, data: [] })

        return new Promise((resolve, reject) => {
            axios
                .get(`/api/browse/folders/${id}/${router.currentRoute.params.token}${getters.sorting.URI}`)
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
    getShareDetail: ({ commit, state }, token) => {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/browse/share/${token}`)
                .then((response) => {
                    resolve(response)

                    // Commit shared item options
                    commit('SET_SHARED_DETAIL', response.data)
                    commit('SET_PERMISSION', response.data.data.attributes.permission)
                })
                .catch((error) => {
                    reject(error)

                    if (error.response.status === 404) {
                        router.push({ name: 'NotFound' })
                    }
                })
        })
    },
    shareCancel: ({ commit, getters }, singleItem) => {
        return new Promise((resolve, reject) => {
            let tokens = []
            let items = [singleItem]

            if (!singleItem) {
                items = getters.clipboard
            }

            items.forEach((item) => {
                tokens.push(item.data.relationships.shared.data.attributes.token)
            })

            axios
                .post('/api/share/revoke', {
                    _method: 'delete',
                    tokens: tokens,
                })
                .then(() => {
                    items.forEach((item) => {
                        // Remove item from file browser
                        if (
                            getters.currentFolder &&
                            Vue.prototype.$isThisRoute(router.currentRoute, ['MySharedItems'])
                        ) {
                            commit('REMOVE_ITEM', item.data.id)
                        }

                        // Flush shared data
                        commit('FLUSH_SHARED', item.data.id)
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
    },
}
const getters = {
    permissionOptions: (state) => state.permissionOptions,
    sharedDetail: (state) => state.sharedDetail,
    sharedFile: (state) => state.sharedFile,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
