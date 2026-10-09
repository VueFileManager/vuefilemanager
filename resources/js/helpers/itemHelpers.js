import { globals } from '../globals'
import i18n from '../i18n'
import store from '../store/index'
import { events } from '../bus'
import {isArray} from "lodash";

const itemHelpers = {
    install(app) {
        globals.$emptyTrash = function () {
            store.dispatch('emptyTrash')
        }
        globals.$emptyTrashQuietly = function () {
            store.dispatch('emptyTrashQuietly')
        }

        globals.$shareCancel = function () {
            store.dispatch('shareCancel')
        }

        globals.$toggleFavourites = function (entry) {
            let favourites = store.getters.user.data.relationships.favourites

            // Check if folder is in favourites and then add/remove from favourites
            if (favourites && !favourites.find((el) => el.data.id === entry.data.id)) {
                // Add to favourite folder that is not selected
                if (!store.getters.clipboard.includes(entry)) {
                    this.$store.dispatch('addToFavourites', entry)
                }

                // Add to favourites all selected folders
                if (store.getters.clipboard.includes(entry)) {
                    this.$store.dispatch('addToFavourites', null)
                }
            } else {
                this.$store.dispatch('removeFromFavourites', entry)
            }
        }

        globals.$renameFileOrFolder = function (entry) {
            events.$emit('popup:open', { name: 'rename-item', item: entry })
        }

        globals.$moveFileOrFolder = function (entry) {

            let item = isArray(entry) ? [...entry] : [entry]

            events.$emit('popup:open', { name: 'move', item: item })
        }

        globals.$createFolderByPopup = function () {
            // Show alert message when create folder is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canCreateFolder) {
                globals.$temporarilyDisabledFolderCreate()

                return
            }

            events.$emit('popup:open', { name: 'create-folder' })
        }

        globals.$createFolder = function () {
            // Show alert message when create folder is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canCreateFolder) {
                globals.$temporarilyDisabledFolderCreate()

                return
            }

            store.dispatch('createFolder', {
                name: i18n.global.t('popup_create_folder.folder_default_name'),
            })
        }

        globals.$downloadSelection = function (item = undefined) {
            // Show alert message when download is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canDownload) {
                globals.$temporarilyDisabledDownload()

                return
            }

            // Download folder zip
            if (item && item.data.type === 'folder') {
                store.dispatch('downloadZip', item)

                return
            }

            // Download single item
            if (item && item.data.type !== 'folder') {
                globals.$downloadFile(
                    item.data.attributes.file_url,
                    item.data.attributes.name + '.' + item.data.attributes.mimetype
                )

                return
            }

            // Download selection
            let clipboard = store.getters.clipboard

            if (clipboard.length > 1 || (clipboard.length === 1 && clipboard[0].data.type === 'folder')) {
                store.dispatch('downloadZip')
            }
        }

        globals.$dissolveTeamFolder = function (folder) {
            events.$emit('confirm:open', {
                title: i18n.global.t('really_dissolve_team'),
                message: i18n.global.t(
                    'really_dissolve_team_desc'
                ),
                action: {
                    id: folder.data.id,
                    operation: 'dissolve-team-folder',
                },
            })
        }

        globals.$detachMeFromTeamFolder = function (folder) {
            events.$emit('confirm:open', {
                title: i18n.global.t('really_leave_team'),
                message: i18n.global.t(
                    "really_leave_team_desc"
                ),
                action: {
                    id: folder.data.id,
                    operation: 'leave-team-folder',
                },
            })
        }

        globals.$createTeamFolder = function () {
            // Show alert message when create folder is disabled
            if (!store.getters.user.data.meta.restrictions.canCreateTeamFolder) {
                globals.$temporarilyDisabledFolderCreate()

                return
            }

            events.$emit('popup:open', { name: 'create-team-folder' })
        }

        globals.$convertAsTeamFolder = function (entry) {
            events.$emit('popup:open', {
                name: 'create-team-folder',
                item: entry,
            })
        }

        globals.$createFileRequest = function (entry = undefined) {
            events.$emit('popup:open', {
                name: 'create-file-request',
                item: entry,
            })
        }

        globals.$updateTeamFolder = function (entry) {
            events.$emit('popup:open', {
                name: 'update-team-folder',
                item: entry,
            })
        }

        globals.$removeFavourite = function (folder) {
            store.dispatch('removeFromFavourites', folder)
        }

        globals.$deleteFileOrFolder = function (entry) {
            if (!store.getters.clipboard.includes(entry)) {
                store.dispatch('deleteItem', entry)
            }

            if (store.getters.clipboard.includes(entry)) {
                store.dispatch('deleteItem')
            }
        }

        globals.$restoreFileOrFolder = function (entry) {
            if (!store.getters.clipboard.includes(entry)) store.dispatch('restoreItem', entry)

            if (store.getters.clipboard.includes(entry)) store.dispatch('restoreItem', null)
        }

        globals.$shareFileOrFolder = function (entry) {
            let event = entry.data.relationships.shared ? 'share-edit' : 'share-create'

            events.$emit('popup:open', {
                name: event,
                item: entry,
            })
        }
    },
}

export default itemHelpers
