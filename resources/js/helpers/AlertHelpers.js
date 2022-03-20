import store from '../store/index'
import { events } from '../bus'
import i18n from '../i18n'

const AlertHelpers = {
    install(Vue) {
        Vue.prototype.$temporarilyDisabledUpload = function () {
            let messages = {
                metered: {
                    title: i18n.t('upload_temporarily_disabled'),
                    message: i18n.t('upload_temporarily_disabled_note'),
                },
                fixed: {
                    title: i18n.t('upload_temporarily_disabled'),
                    message: i18n.t('upload_temporarily_disabled_note'),
                },
                none: {
                    title: i18n.t('exceed_upload_limit'),
                    message: i18n.t('exceed_upload_limit_description'),
                },
            }

            events.$emit('alert:open', {
                title: messages[store.getters.config.subscriptionType]['title'],
                message: messages[store.getters.config.subscriptionType]['message'],
            })
        }

        Vue.prototype.$temporarilyDisabledFolderCreate = function () {
            events.$emit('alert:open', {
                title: i18n.t('create_folder_temporarily_disabled'),
                message: i18n.t('create_folder_temporarily_disabled_desc'),
            })
        }

        Vue.prototype.$temporarilyDisabledDownload = function () {
            events.$emit('alert:open', {
                title: i18n.t('download_temporarily_disabled'),
                message: i18n.t('download_temporarily_disabled_desc'),
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
