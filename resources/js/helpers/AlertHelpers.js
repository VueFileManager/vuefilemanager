import { globals } from '../globals'
import store from '../store/index'
import { events } from '../bus'
import i18n from '../i18n'

const AlertHelpers = {
    install(app) {
        globals.$temporarilyDisabledUpload = function () {
            let messages = {
                metered: {
                    title: i18n.global.t('upload_temporarily_disabled'),
                    message: i18n.global.t('upload_temporarily_disabled_note'),
                },
                fixed: {
                    title: i18n.global.t('upload_temporarily_disabled'),
                    message: i18n.global.t('upload_temporarily_disabled_note'),
                },
                none: {
                    title: i18n.global.t('exceed_upload_limit'),
                    message: i18n.global.t('exceed_upload_limit_description'),
                },
            }

            events.$emit('alert:open', {
                title: messages[store.getters.config.subscriptionType]['title'],
                message: messages[store.getters.config.subscriptionType]['message'],
            })
        }

        globals.$temporarilyDisabledFolderCreate = function () {
            events.$emit('alert:open', {
                title: i18n.global.t('create_folder_temporarily_disabled'),
                message: i18n.global.t('create_folder_temporarily_disabled_desc'),
            })
        }

        globals.$temporarilyDisabledDownload = function () {
            events.$emit('alert:open', {
                title: i18n.global.t('download_temporarily_disabled'),
                message: i18n.global.t('download_temporarily_disabled_desc'),
            })
        }

        globals.$isSomethingWrong = function () {
            events.$emit('alert:open', {
                title: i18n.global.t('popup_error.title'),
                message: i18n.global.t('popup_error.message'),
            })
        }
    },
}

export default AlertHelpers
