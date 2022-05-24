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
    getSharedFolder: ({ commit, getters }, {page, id}) => {
        return new Promise((resolve, reject) => {

            if(page === 1)
                commit('START_LOADING_VIEW')

            axios
                .get(`/api/sharing/folders/${id}/${router.currentRoute.params.token}${getters.sorting.URI}&page=${page}`)
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
    getShareDetail: ({ commit, state }, token) => {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/sharing/${token}`)
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
            let items = singleItem ? [singleItem] : getters.clipboard
            let tokens = items.map((item) => item.data.relationships.shared.data.attributes.token)

            axios
                .post('/api/share/revoke', {
                    _method: 'delete',
                    tokens: tokens,
                })
                .then(() => {
                    items.forEach((item) => {
                        // Remove item from file browser
                        if (Vue.prototype.$isThisRoute(router.currentRoute, ['MySharedItems'])) {
                            commit('REMOVE_ITEM', item.data.id)
                            commit('CLIPBOARD_CLEAR')
                        }

                        // Flush shared data
                        commit('FLUSH_SHARED', item.data.id)
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
