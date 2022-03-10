const defaultState = {
    isRunningConnection: false,
}

const actions = {
    runConnection: ({ commit, getters, dispatch }) => {

        commit('SET_RUNNING_COMMUNICATION')

		Echo.private(`App.Users.Models.User.${getters.user.data.id}`)
			.notification(() => {
				// TODO: call sound
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
