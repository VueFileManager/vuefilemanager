import router from '../../router'
import { events } from '../../bus'
import i18n from '../../i18n'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    currentTeamFolder: undefined,
}

const actions = {
    getTeamFolder: ({ commit, getters }, {page, id}) => {
        return new Promise ((resolve, reject) => {

            if(! page)
                commit('LOADING_STATE', { loading: true, data: [] })
    
            if (typeof id === 'undefined') {
                commit('SET_CURRENT_TEAM_FOLDER', null)
            }

            let currentPage = page || 1
    
            axios
                .get(`${getters.api}/teams/folders/${id}/${getters.sorting.URI}&page=${currentPage}`)
                .then((response) => {
                    commit('SET_PAGINATE', {
                        paginate: response.data.meta.paginate
                    })

                    commit('LOADING_STATE', {
                        loading: false,
                        data:response.data.data,
                    })
                    commit('SET_CURRENT_FOLDER', response.data.meta.root)
    
                    if (
                        !getters.currentTeamFolder ||
                        getters.currentTeamFolder.data.id !== response.data.teamFolder.data.id
                    ) {
                        commit('SET_CURRENT_TEAM_FOLDER', response.data.teamFolder)
                    }
    
                    events.$emit('scrollTop')

                    resolve(true)
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

                    reject(error)
                })
        })
    },
    getSharedWithMeFolder: ({ commit, getters }, {page, id}) => {
        return new Promise ((resolve, reject) => {
        
            if(! page)
                commit('LOADING_STATE', { loading: true, data: [] })
    
            if (typeof id === 'undefined') {
                commit('SET_CURRENT_TEAM_FOLDER', null)
            }

            let currentPage = page || 1
    
            axios
                .get(`${getters.api}/teams/shared-with-me/${id}/${getters.sorting.URI}&page=${currentPage}`)
                .then((response) => {
                    commit('SET_PAGINATE', {
                        paginate: response.data.meta.paginate
                    })
                   
                    commit('LOADING_STATE', {
                        loading: false,
                        data: response.data.data,
                    })
                    commit('SET_CURRENT_FOLDER', response.data.meta.root)
    
                    if (
                        !getters.currentTeamFolder ||
                        getters.currentTeamFolder.data.id !== response.data.teamFolder.data.id
                    ) {
                        commit('SET_CURRENT_TEAM_FOLDER', response.data.teamFolder)
                    }
    
                    events.$emit('scrollTop')

                    resolve(true)
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

                    reject(error)
                })
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
