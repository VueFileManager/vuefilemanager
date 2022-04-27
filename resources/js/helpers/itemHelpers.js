import i18n from '../i18n'
import store from '../store/index'
import { events } from '../bus'
import {isArray} from "lodash";

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

        Vue.prototype.$toggleFavourites = function (entry) {
            let favourites = store.getters.user.data.relationships.favourites.data

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

        Vue.prototype.$renameFileOrFolder = function (entry) {
            events.$emit('popup:open', { name: 'rename-item', item: entry })
        }

        Vue.prototype.$moveFileOrFolder = function (entry) {

            let item = isArray(entry) ? [...entry] : [entry]

            events.$emit('popup:open', { name: 'move', item: item })
        }

        Vue.prototype.$createFolderByPopup = function () {
            // Show alert message when create folder is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canCreateFolder) {
                Vue.prototype.$temporarilyDisabledFolderCreate()

                return
            }

            events.$emit('popup:open', { name: 'create-folder' })
        }

        Vue.prototype.$createFolder = function () {
            // Show alert message when create folder is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canCreateFolder) {
                Vue.prototype.$temporarilyDisabledFolderCreate()

                return
            }

            store.dispatch('createFolder', {
                name: i18n.t('popup_create_folder.folder_default_name'),
            })
        }

        Vue.prototype.$downloadSelection = function (item = undefined) {
            // Show alert message when download is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canDownload) {
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
                Vue.prototype.$downloadFile(
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
