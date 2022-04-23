import { events } from '../../bus'

const defaultState = {
    isBroadcasting: false,
}

const actions = {
    runConnection: ({ commit, getters, dispatch }) => {
        commit('SET_RUNNING_COMMUNICATION')

        Echo.private(`App.Users.Models.User.${getters.user.data.id}`)
            .listen('.file.created', (event) => {
                // If user is located in same directory as remote upload was called, then show the files
                if (
                    (!getters.currentFolder && !event.file.data.attributes.parent_id) ||
                    (getters.currentFolder && event.file.data.attributes.parent_id === getters.currentFolder.data.id)
                ) {
                    // Add received item into view
                    commit('ADD_NEW_ITEMS', event.file)
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
}

const getters = {
    isBroadcasting: (state) => state.isBroadcasting,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
