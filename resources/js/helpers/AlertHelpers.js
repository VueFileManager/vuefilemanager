import {events} from "../bus";
import i18n from "../i18n";

const AlertHelpers = {
	install(Vue) {

		Vue.prototype.$temporarilyDisabledUpload = function () {
			events.$emit('alert:open', {
				title: i18n.t('Upload is temporarily disabled'),
				message: i18n.t('Please review your billing settings.')
			})
		}

		Vue.prototype.$temporarilyDisabledFolderCreate = function () {
			events.$emit('alert:open', {
				title: i18n.t('Folder creation is temporarily disabled'),
				message: i18n.t('Please review your billing settings.')
			})
		}

		Vue.prototype.$temporarilyDisabledDownload = function () {
			events.$emit('alert:open', {
				title: i18n.t('File download is temporarily disabled'),
				message: i18n.t('Please review your billing settings.')
			})
		}

		Vue.prototype.$isSomethingWrong = function () {
			events.$emit('alert:open', {
				title: i18n.t('popup_error.title'),
				message: i18n.t('popup_error.message')
			})
		}
	}
}

export default AlertHelpers