import router from '../../router'
import { events } from '../../bus'
import i18n from '../../i18n'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    currentTeamFolder: undefined,
}

const actions = {
    getTeamFolder: ({ commit, getters }, id) => {
        commit('LOADING_STATE', { loading: true, data: [] })

        if (typeof id === 'undefined') {
            commit('SET_CURRENT_TEAM_FOLDER', null)
        }

        axios
            .get(`${getters.api}/teams/folders/${id}${getters.sorting.URI}`)
            .then((response) => {
                let folders = response.data.folders.data
                let files = response.data.files.data

                commit('LOADING_STATE', {
                    loading: false,
                    data: folders.concat(files),
                })
                commit('SET_CURRENT_FOLDER', response.data.root)

                if (
                    !getters.currentTeamFolder ||
                    getters.currentTeamFolder.data.id !== response.data.teamFolder.data.id
                ) {
                    commit('SET_CURRENT_TEAM_FOLDER', response.data.teamFolder)
                }

                events.$emit('scrollTop')
            })
            .catch((error) => {
                // Redirect if unauthenticated
                if ([401, 403].includes(error.response.status)) {
                    commit('SET_AUTHORIZED', false)
                    router.push({ name: 'SignIn' })
                } else {
                    // Show error message
                    events.$emit('alert:open', {
                        title: i18n.t('popup_error.title'),
                        message: i18n.t('popup_error.message'),
                    })
                }
            })
    },
    getSharedWithMeFolder: ({ commit, getters }, id) => {
        commit('LOADING_STATE', { loading: true, data: [] })

        if (typeof id === 'undefined') {
            commit('SET_CURRENT_TEAM_FOLDER', null)
        }

        axios
            .get(`${getters.api}/teams/shared-with-me/${id}${getters.sorting.URI}`)
            .then((response) => {
                let folders = response.data.folders.data
                let files = response.data.files.data

                commit('LOADING_STATE', {
                    loading: false,
                    data: folders.concat(files),
                })
                commit('SET_CURRENT_FOLDER', response.data.root)

                if (
                    !getters.currentTeamFolder ||
                    getters.currentTeamFolder.data.id !== response.data.teamFolder.data.id
                ) {
                    commit('SET_CURRENT_TEAM_FOLDER', response.data.teamFolder)
                }

                events.$emit('scrollTop')
            })
            .catch((error) => {
                // Redirect if unauthenticated
                if ([401, 403].includes(error.response.status)) {
                    commit('SET_AUTHORIZED', false)
                    router.push({ name: 'SignIn' })
                } else {
                    // Show error message
                    events.$emit('alert:open', {
                        title: i18n.t('popup_error.title'),
                        message: i18n.t('popup_error.message'),
                    })
                }
            })
    },
    getTeamFolderTree: ({ commit, getters }) => {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/teams/folders/${getters.currentTeamFolder.data.id}/tree${getters.sorting.URI}`)
                .then((response) => {
                    resolve(response)

                    commit('UPDATE_FOLDER_TREE', response.data)
                })
                .catch((error) => {
                    reject(error)

                    Vue.prototype.$isSomethingWrong()
                })
        })
    },
}

const mutations = {
    SET_CURRENT_TEAM_FOLDER(state, payload) {
        state.currentTeamFolder = payload
    },
}

const getters = {
    currentTeamFolder: (state) => state.currentTeamFolder,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
