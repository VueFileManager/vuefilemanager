const defaultState = {
    isRunningConnection: false,
}

const actions = {
    runConnection: ({ commit, getters }) => {

        commit('SET_RUNNING_COMMUNICATION')

        Echo.private(`test.${getters.user.data.id}`)
            .listen('.Domain\\Notifications\\Events\\TestUpdate', (e) => {
                console.log(e);
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
