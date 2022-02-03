import i18n from '../i18n'
import store from '../store/index'
import { events } from '../bus'

const itemHelpers = {
    install(Vue) {
        Vue.prototype.$emptyTrash = function () {
            store.dispatch('emptyTrash')
        }
        Vue.prototype.$emptyTrashQuietly = function () {
            store.dispatch('emptyTrashQuietly')
        }

        Vue.prototype.$shareCancel = function () {
            store.dispatch('shareCancel')
        }

        Vue.prototype.$renameFileOrFolder = function (entry) {
            events.$emit('popup:open', { name: 'rename-item', item: entry })
        }

        Vue.prototype.$moveFileOrFolder = function (entry) {
            events.$emit('popup:open', { name: 'move', item: [entry] })
        }

        Vue.prototype.$createFolder = function () {
            // Show alert message when create folder is disabled
            if (!store.getters.user.data.meta.restrictions.canCreateFolder) {
                Vue.prototype.$temporarilyDisabledFolderCreate()

                return
            }

            store.dispatch('createFolder', {
                name: i18n.t('popup_create_folder.folder_default_name'),
            })
        }

        Vue.prototype.$downloadSelection = function (item = undefined) {
            // Show alert message when download is disabled
            if (!store.getters.user.data.meta.restrictions.canDownload) {
                Vue.prototype.$temporarilyDisabledDownload()

                return
            }

            // Download folder zip
            if (item && item.data.type === 'folder') {
                store.dispatch('downloadZip', item)

                return
            }

            // Download single item
            if (item && item.data.type !== 'folder') {
                Vue.prototype.$downloadFile(item.data.attributes.file_url, item.data.attributes.name + '.' + item.data.attributes.mimetype)

                return
            }

            // Download selection
            let clipboard = store.getters.clipboard

            if (clipboard.length > 1 || (clipboard.length === 1 && clipboard[0].data.type === 'folder')) {
                store.dispatch('downloadZip')
            }
        }

        Vue.prototype.$dissolveTeamFolder = function (folder) {
            events.$emit('confirm:open', {
                title: i18n.t('Are you sure you want to dissolve this team?'),
                message: i18n.t('All team members will lose access to your files and existing folder will be moved into your "Files" section.'),
                action: {
                    id: folder.data.id,
                    operation: 'dissolve-team-folder',
                },
            })
        }

        Vue.prototype.$detachMeFromTeamFolder = function (folder) {
            events.$emit('confirm:open', {
                title: i18n.t('Are you sure you want to leave this team folder?'),
                message: i18n.t("You will don't have access to the files and all your previously uploaded content will be part of this Team Folder you are leaving."),
                action: {
                    id: folder.data.id,
                    operation: 'leave-team-folder',
                },
            })
        }

        Vue.prototype.$createTeamFolder = function () {
            // Show alert message when create folder is disabled
            if (!store.getters.user.data.meta.restrictions.canCreateTeamFolder) {
                Vue.prototype.$temporarilyDisabledFolderCreate()

                return
            }

            events.$emit('popup:open', { name: 'create-team-folder' })
        }

        Vue.prototype.$convertAsTeamFolder = function (entry) {
            events.$emit('popup:open', {
                name: 'create-team-folder',
                item: entry,
            })
        }

        Vue.prototype.$updateTeamFolder = function (entry) {
            events.$emit('popup:open', {
                name: 'update-team-folder',
                item: entry,
            })
        }

        Vue.prototype.$removeFavourite = function (folder) {
            store.dispatch('removeFromFavourites', folder)
        }

        Vue.prototype.$deleteFileOrFolder = function (entry) {
            if (!store.getters.clipboard.includes(entry)) {
                store.dispatch('deleteItem', entry)
            }

            if (store.getters.clipboard.includes(entry)) {
                store.dispatch('deleteItem')
            }
        }

        Vue.prototype.$restoreFileOrFolder = function (entry) {
            if (!store.getters.clipboard.includes(entry)) store.dispatch('restoreItem', entry)

            if (store.getters.clipboard.includes(entry)) store.dispatch('restoreItem', null)
        }

        Vue.prototype.$shareFileOrFolder = function (entry) {
            let event = entry.data.relationships.shared ? 'share-edit' : 'share-create'

            events.$emit('popup:open', {
                name: event,
                item: entry,
            })
        }
    },
}

export default itemHelpers
