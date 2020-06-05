import i18n from '@/i18n/index'

const defaultState = {
	fileInfoPanelVisible: localStorage.getItem('file_info_visibility') == 'true' || false,
	FilePreviewType: localStorage.getItem('preview_type') || 'list',
	config: undefined,
	authorized: undefined,
	homeDirectory: undefined,
	requestedPlan: undefined,
	roles: [
		{
			label: i18n.t('roles.admin'),
			value: 'admin',
		},
		{
			label: i18n.t('roles.user'),
			value: 'user',
		},
	],
}
const actions = {
	changePreviewType: ({commit, dispatch, state, getters}) => {
		// Get preview type
		let previewType = state.FilePreviewType == 'grid' ? 'list' : 'grid'

		// Store preview type to localStorage
		localStorage.setItem('preview_type', previewType)

		// Change preview
		commit('CHANGE_PREVIEW', previewType)
	},
	fileInfoToggle: (context, visibility = undefined) => {
		if (!visibility) {
			if (context.state.fileInfoPanelVisible) {
				context.commit('FILE_INFO_TOGGLE', false)
			} else {
				context.commit('FILE_INFO_TOGGLE', true)
			}
		} else {
			context.commit('FILE_INFO_TOGGLE', visibility)
		}
	},
}
const mutations = {
	INIT(state, data) {
		state.config = data.config
		state.authorized = data.authCookie
		state.homeDirectory = data.rootDirectory
	},
	FILE_INFO_TOGGLE(state, isVisible) {
		state.fileInfoPanelVisible = isVisible

		localStorage.setItem('file_info_visibility', isVisible)
	},
	SET_AUTHORIZED(state, data) {
		state.authorized = data
	},
	CHANGE_PREVIEW(state, type) {
		state.FilePreviewType = type
	},
	STORE_REQUESTED_PLAN(state, plan) {
		state.requestedPlan = plan
	},
}
const getters = {
	fileInfoVisible: state => state.fileInfoPanelVisible,
	FilePreviewType: state => state.FilePreviewType,
	homeDirectory: state => state.homeDirectory,
	requestedPlan: state => state.requestedPlan,
	api: state => state.config.api,
	config: state => state.config,
	roles: state => state.roles,
}

export default {
	state: defaultState,
	getters,
	actions,
	mutations
}
