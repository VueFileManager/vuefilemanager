import {events} from "../../bus";

const defaultState = {
    isRunningConnection: false,
}

const actions = {
    runConnection: ({ commit, getters, dispatch }) => {

        commit('SET_RUNNING_COMMUNICATION')

		Echo.private(`App.Users.Models.User.${getters.user.data.id}`)
			.notification((notification) => {

				// Play audio
				new Audio('/audio/blop.wav').play();

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
			});
    },
}

const mutations = {
    SET_RUNNING_COMMUNICATION(state) {
        state.isRunningConnection = true
    },
}

const getters = {
    isRunningConnection: (state) => state.isRunningConnection,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
