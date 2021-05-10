import i18n from '@/i18n/index'
import store from './store/index'
import {debounce, isArray} from "lodash";
import {events} from './bus'
import axios from 'axios'

const Helpers = {
    install(Vue) {

        Vue.prototype.$searchInvoices = debounce(function (value) {

            if (value !== '' && typeof value !== 'undefined') {

                if (['regular-invoice', 'advance-invoice'].includes(this.$store.getters.currentFolder.location)) {
                    this.$store.dispatch('getSearchResultForInvoices', value)
                } else {
                    this.$store.dispatch('getSearchResultForClients', value)
                }
            } else if (typeof value !== 'undefined') {

                let locations = {
                    'regular-invoice': 'getRegularInvoices',
                    'advance-invoice': 'getAdvanceInvoices',
                    'clients': 'getClients',
                }

                this.$store.dispatch(locations[this.$store.getters.currentFolder.location])

                this.$store.commit('CHANGE_SEARCHING_STATE', false)
            }
        }, 300)

        Vue.prototype.$renameFileOrFolder = function (entry) {
            events.$emit('popup:open', {name: 'rename-item', item: entry})
        }

        Vue.prototype.$moveFileOrFolder = function (entry) {
            events.$emit('popup:open', {name: 'move', item: [entry]})
        }

        Vue.prototype.$deleteFileOrFolder = function (entry) {
            if (!this.$store.getters.clipboard.includes(entry)) {
                this.$store.dispatch('deleteItem', entry)
            }

            if (this.$store.getters.clipboard.includes(entry)) {
                this.$store.dispatch('deleteItem')
            }
        }

        Vue.prototype.$restoreFileOrFolder = function (entry) {
            if (!this.$store.getters.clipboard.includes(entry))
                this.$store.dispatch('restoreItem', entry)

            if (this.$store.getters.clipboard.includes(entry))
                this.$store.dispatch('restoreItem', null)
        }

        Vue.prototype.$shareFileOrFolder = function (entry) {
            let event = entry.shared
                ? 'share-edit'
                : 'share-create'

            events.$emit('popup:open', {
                name: event,
                item: entry
            })
        }

        Vue.prototype.$searchFiles = debounce(function (value) {

            if (value !== '' && typeof value !== 'undefined') {

                this.$store.dispatch('getSearchResult', value)

            } else if (typeof value !== 'undefined') {

                if (this.$store.getters.currentFolder) {

                    // Get back after delete query to previously folder
                    if (this.$isThisLocation('public')) {
                        this.$store.dispatch('browseShared', [{folder: this.$store.getters.currentFolder, back: true, init: false}])
                    } else {
                        this.$store.dispatch('getFolder', [{folder: this.$store.getters.currentFolder, back: true, init: false}])
                    }
                }

                this.$store.commit('CHANGE_SEARCHING_STATE', false)
            }
        }, 300)

        Vue.prototype.$updateText = debounce(function (route, name, value, allowEmpty = false) {

            if ((value === '' || value === ' ') && !allowEmpty) return

            axios.post(this.$store.getters.api + route, {name, value, _method: 'patch'})
                .catch(() => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
        }, 150)

        Vue.prototype.$updateImage = function (route, name, image) {

            // Create form
            let formData = new FormData()

            // Add image to form
            formData.append('name', name)
            formData.append(name, image)
            formData.append('_method', 'PATCH')

            axios.post(this.$store.getters.api + route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
                .catch(error => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
        }

        Vue.prototype.$scrollTop = function () {
            var container = document.getElementById('vuefilemanager')

            if (container) {
                container.scrollTop = 0
            }
        }

        Vue.prototype.$translateSelectOptions = function (options) {
            return options.map(role => {
                let key, values;

                if (isArray(role.label)) {
                    [key, values] = role.label
                }

                return {
                    label: isArray(role.label)
                        ? i18n.t(key, values)
                        : i18n.t(role.label),
                    value: role.value,
                    icon: role.icon ? role.icon : '',
                }
            })
        }

        Vue.prototype.$getImage = function (source) {
            return source ? this.$store.getters.config.host + '/' + source : ''
        }

        Vue.prototype.$getCreditCardBrand = function (brand) {
            return `/assets/icons/${brand}.svg`
        }

        Vue.prototype.$getInvoiceLink = function (customer, id) {
            return '/invoice/' + customer + '/' + id
        }

        Vue.prototype.$openImageOnNewTab = function (source) {
            let win = window.open(source, '_blank')

            win.focus()
        }

        Vue.prototype.$uploadFiles = async function (files) {

            if (files.length == 0) return

            if (!this.$checkFileMimetype(files) || !this.$checkUploadLimit(files)) return

            // Push items to file queue
            [...files].map(item => {
                this.$store.commit('ADD_FILES_TO_QUEUE', {
                    folder_id: store.getters.currentFolder.id ? store.getters.currentFolder.id : '',
                    file: item,
                })
            });

            // Start uploading if uploading process isn't running
            if (this.$store.getters.filesInQueueTotal == 0)
                this.$handleUploading(store.getters.fileQueue[0])

            // Increase total files in upload bar
            this.$store.commit('INCREASE_FILES_IN_QUEUES_TOTAL', files.length)
        }

        Vue.prototype.$uploadExternalFiles = async function (event, parent_id) {

            // Prevent submit empty files
            if (event.dataTransfer.items.length === 0) return

            // Push items to file queue
            [...event.dataTransfer.items].map(item => {
                this.$store.commit('ADD_FILES_TO_QUEUE', {
                    folder_id: parent_id ? parent_id : '',
                    file: item.getAsFile(),
                })
            });

            // Start uploading if uploading process isn't running
            if (this.$store.getters.filesInQueueTotal == 0)
                this.$handleUploading(this.$store.getters.fileQueue[0])

            // Increase total files in upload bar
            this.$store.commit('INCREASE_FILES_IN_QUEUES_TOTAL', [...event.dataTransfer.items].length)
        }

        Vue.prototype.$handleUploading = async function (item) {

            // Create ceil
            let size = store.getters.config.chunkSize,
                chunksCeil = Math.ceil(item.file.size / size),
                chunks = []

            // Create chunks
            for (let i = 0; i < chunksCeil; i++) {
                chunks.push(item.file.slice(
                    i * size, Math.min(i * size + size, item.file.size), item.file.type
                ));
            }

            // Set Data
            let formData = new FormData(),
                uploadedSize = 0,
                isNotGeneralError = true,
                striped_name = item.file.name.replace(/[^A-Za-z 0-9 \.,\?""!@#\$%\^&\*\(\)-_=\+;:<>\/\\\|\}\{\[\]`~]*/g, ''),
                filename = Array(16).fill(0).map(x => Math.random().toString(36).charAt(2)).join('') + '-' + striped_name + '.part'

            do {
                let isLast = chunks.length === 1,
                    chunk = chunks.shift(),
                    attempts = 0

                // Set form data
                formData.set('file', chunk, filename);
                formData.set('folder_id', item.folder_id)
                formData.set('is_last', isLast);

                // Upload chunks
                do {
                    await store.dispatch('uploadFiles', {
                        form: formData,
                        fileSize: item.file.size,
                        totalUploadedSize: uploadedSize
                    }).then(() => {
                        uploadedSize = uploadedSize + chunk.size
                    }).catch((error) => {

                        // Count attempts
                        attempts++

                        // Show Error
                        if (attempts === 3)
                            this.$isSomethingWrong()

                        // Break uploading process
                        if ([500, 422].includes(error.response.status))
                            isNotGeneralError = false
                    })
                } while (isNotGeneralError && attempts !== 0 && attempts !== 3)

            } while (isNotGeneralError && chunks.length !== 0)
        }

        Vue.prototype.$downloadFile = function (url, filename) {
            var anchor = document.createElement('a')

            anchor.href = url

            anchor.download = filename

            document.body.appendChild(anchor)

            anchor.click()
        }

        Vue.prototype.$closePopup = function () {
            events.$emit('popup:close')
        }

        Vue.prototype.$isThisRoute = function (route, locations) {

            return locations.includes(route.name)
        }

        Vue.prototype.$isThisLocation = function (location) {

            // Get current location
            let currentLocation = store.getters.currentFolder && store.getters.currentFolder.location ? store.getters.currentFolder.location : undefined

            // Check if type is object
            if (typeof location === 'Object' || location instanceof Object) {
                return location.includes(currentLocation)

            } else {
                return currentLocation === location
            }
        }

        Vue.prototype.$checkPermission = function (type) {

            let currentPermission = store.getters.permission

            // Check if type is object
            if (typeof type === 'Object' || type instanceof Object) {
                return type.includes(currentPermission)

            } else {
                return currentPermission === type
            }
        }

        Vue.prototype.$isMobile = function () {
            const toMatch = [
                /Android/i,
                /webOS/i,
                /iPhone/i,
                /iPad/i,
                /iPod/i,
                /BlackBerry/i,
                /Windows Phone/i
            ]

            return toMatch.some(toMatchItem => {
                return navigator.userAgent.match(toMatchItem)
            })
        }

        Vue.prototype.$isSomethingWrong = function () {
            events.$emit('alert:open', {
                title: i18n.t('popup_error.title'),
                message: i18n.t('popup_error.message')
            })
        }

        Vue.prototype.$checkFileMimetype = function (files) {
            let validated = true
            let mimetypesBlacklist = store.getters.config.mimetypesBlacklist

            for (let i = 0; i < files.length; i++) {
                let fileType = files[i].type.split('/')

                if (!fileType[0]) {
                    fileType[1] = _.last(files[i].name.split('.'))
                }

                if (mimetypesBlacklist.includes(fileType[1])) {
                    validated = false

                    events.$emit('alert:open', {
                        emoji: '😬😬😬',
                        title: i18n.t('popup_mimetypes_blacklist.title'),
                        message: i18n.t('popup_mimetypes_blacklist.message', {mimetype: fileType[1]})
                    })
                }
            }
            return validated
        }

        Vue.prototype.$checkUploadLimit = function (files) {
            let uploadLimit = store.getters.config.uploadLimit
            let validate = true

            for (let i = 0; i < files.length; i++) {
                if (uploadLimit != 0 && files[i].size > uploadLimit) {
                    validate = false
                    events.$emit('alert:open', {
                        emoji: '😟😟😟',
                        title: i18n.t('popup_upload_limit.title'),
                        message: i18n.t('popup_upload_limit.message', {uploadLimit: store.getters.config.uploadLimitFormatted}),
                    })
                    break
                }
            }
            return validate
        }

        Vue.prototype.$getDataByLocation = function () {

            let folder = store.getters.currentFolder

            let actions = {
                'base': ['getFolder', [{folder: folder, back: true, init: false, sorting: true}]],
                'public': ['browseShared', [{folder: folder, back: true, init: false, sorting: true}]],
                'trash': ['getFolder', [{folder: folder, back: true, init: false, sorting: true}]],
                'participant_uploads': ['getParticipantUploads'],
                'trash-root': ['getTrash'],
                'latest': ['getLatest'],
                'shared': ['getShared']
            }

            this.$store.dispatch(...actions[folder.location])

            // Get dara of user with favourites tree
            this.$store.dispatch('getAppData')

            // Get data of Navigator tree
            this.$store.dispatch('getFolderTree')
        }

        // Detect windows
        Vue.prototype.$checkOS = function () {
            if (navigator.userAgent.indexOf('Windows') != -1) {
                document.body.classList.add('windows')
            }
        }

        // Check if device is Apple
        Vue.prototype.$isApple = function () {

            const toMatch = [
                /iPhone/i,
                /iPad/i,
                /iPod/i,
                /iOS/i,
                /macOS/i,
                /Macintosh/i
            ]

            return toMatch.some(toMatchItem => {
                return navigator.userAgent.match(toMatchItem)
            })
        }
    }
}

export default Helpers
