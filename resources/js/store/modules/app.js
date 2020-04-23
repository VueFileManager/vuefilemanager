const defaultState = {
	fileInfoPanelVisible: localStorage.getItem('file_info_visibility') == 'true' || false,
	FilePreviewType: localStorage.getItem('FilePreviewType') || 'list',
	appSize: undefined,
	config: undefined,
}
const actions = {
	changePreviewType: ({commit, dispatch, state, getters}) => {
		// Get preview type
		let previewType = state.FilePreviewType == 'grid' ? 'list' : 'grid'

		// Store preview type to localStorage
		localStorage.setItem('preview_type', previewType)

		// Change preview
		commit('CHANGE_PREVIEW', previewType)

		if (getters.currentFolder.location === 'trash-root') {
			dispatch('getTrash')

		} else {

			if ( this.$isThisLocation('public') ) {
				dispatch('browseShared', [this.currentFolder(), false, true])
			} else {
				dispatch('getFolder', [getters.currentFolder, false, true])
			}
		}
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
	FILE_INFO_TOGGLE(state, isVisible) {
		state.fileInfoPanelVisible = isVisible

		localStorage.setItem('file_info_visibility', isVisible)
	},
	SET_APP_WIDTH(state, scale) {
		state.appSize = scale
	},
	CHANGE_PREVIEW(state, type) {
		state.FilePreviewType = type
	},
	SET_CONFIG(state, config) {
		state.config = config
	},

}
const getters = {
	fileInfoVisible: state => state.fileInfoPanelVisible,
	FilePreviewType: state => state.FilePreviewType,
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
