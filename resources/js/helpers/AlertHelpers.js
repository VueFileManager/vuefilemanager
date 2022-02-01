import store from '../store/index'
import { events } from '../bus'
import i18n from '../i18n'

const AlertHelpers = {
    install(Vue) {
        Vue.prototype.$temporarilyDisabledUpload = function () {
            let messages = {
                metered: {
                    title: i18n.t('Upload is temporarily disabled'),
                    message: i18n.t('Please review your billing settings.'),
                },
                fixed: {
                    title: i18n.t('Upload is temporarily disabled'),
                    message: i18n.t('Please review your billing settings.'),
                },
                none: {
                    title: i18n.t('You exceeded your upload limit'),
                    message: i18n.t('Unfortunately, you can not upload your file.'),
                },
            }

            events.$emit('alert:open', {
                title: messages[store.getters.config.subscriptionType]['title'],
                message: messages[store.getters.config.subscriptionType]['message'],
            })
        }

        Vue.prototype.$temporarilyDisabledFolderCreate = function () {
            events.$emit('alert:open', {
                title: i18n.t('Folder creation is temporarily disabled'),
                message: i18n.t('Please review your billing settings.'),
            })
        }

        Vue.prototype.$temporarilyDisabledDownload = function () {
            events.$emit('alert:open', {
                title: i18n.t('File download is temporarily disabled'),
                message: i18n.t('Please review your billing settings.'),
            })
        }

        Vue.prototype.$isSomethingWrong = function () {
            events.$emit('alert:open', {
                title: i18n.t('popup_error.title'),
                message: i18n.t('popup_error.message'),
            })
        }
    },
}

export default AlertHelpers
