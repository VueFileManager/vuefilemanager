const defaultState = {
	appSize: undefined,
	config: undefined,
}
const actions = {

}
const mutations = {
	SET_CONFIG(state, config) {
		state.config = config
	},
	SET_APP_WIDTH(state, scale) {
		state.appSize = scale
	},
}
const getters = {
	appSize: state => state.appSize,
	api: state => state.config.api,
	config: state => state.config,
}

export default {
	state: defaultState,
	getters,
	actions,
	mutations
}
