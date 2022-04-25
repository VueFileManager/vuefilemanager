import { events } from '../../bus'
import i18n from "../../i18n"

const defaultState = {
    remoteUploadQueue: undefined,
    isBroadcasting: false,
}

const actions = {
    runConnection: ({ commit, getters, dispatch }) => {
        commit('SET_RUNNING_COMMUNICATION')

        Echo.private(`App.Users.Models.User.${getters.user.data.id}`)
            .listen('.file.created', (event) => {
                commit('UPDATE_REMOTE_UPLOAD_QUEUE', event.payload)

                // If user is located in same directory as remote upload was called, then show the files
                if (
                    event.payload.file &&
                    (!getters.currentFolder && !event.payload.file.data.attributes.parent_id) ||
                    (getters.currentFolder && event.payload.file.data.attributes.parent_id === getters.currentFolder.data.id)
                ) {
                    // Add received item into view
                    commit('ADD_NEW_ITEMS', event.payload.file)
                }

                if (event.payload.progress.total === event.payload.progress.processed) {
                    events.$emit('toaster', {
                        type: 'success',
                        message: i18n.t('remote_download_finished'),
                    })
                }
            })
            .notification((notification) => {
                // Play audio
                new Audio('/audio/blop.wav').play()

                // Call toaster notification
                events.$emit('notification', {
                    data: {
                        type: notification.category,
                        id: notification.id,
                        attributes: {
                            action: notification.action,
                            description: notification.description,
                            title: notification.title,
                            category: notification.category,
                        },
                    },
                })

                // Reload user data to update notifications
                dispatch('getAppData')
            })
    },
}

const mutations = {
    SET_RUNNING_COMMUNICATION(state) {
        state.isBroadcasting = true
    },
    UPDATE_REMOTE_UPLOAD_QUEUE(state, payload) {
        if (payload.progress.total !== payload.progress.processed) {
            state.remoteUploadQueue = {
                total: payload.progress.total,
                processed: payload.progress.processed,
            }
        } else {
            state.remoteUploadQueue = undefined
        }
    },
}

const getters = {
    remoteUploadQueue: (state) => state.remoteUploadQueue,
    isBroadcasting: (state) => state.isBroadcasting,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
