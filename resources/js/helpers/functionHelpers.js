import i18n from '/resources/js/i18n/index'
import store from '../store/index'
import {debounce, isArray} from "lodash";
import {events} from '../bus'
import axios from 'axios'

const FunctionHelpers = {
    install(Vue) {

        Vue.prototype.$updateText = debounce(function (route, name, value, allowEmpty = false) {

            if ((value === '' || value === ' ' || typeof value === 'object') && !allowEmpty) return

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

        Vue.prototype.$uploadFiles = async function (files) {

            if (files.length === 0) return

            if (!this.$checkFileMimetype(files) || !this.$checkUploadLimit(files)) return

            // Push items to file queue
            [...files].map(item => {
                this.$store.commit('ADD_FILES_TO_QUEUE', {
                    parent_id: store.getters.currentFolder ? store.getters.currentFolder.data.id : '',
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
                    parent_id: parent_id ? parent_id : '',
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

                striped_spaces = item.file.name.replace(/\s/g, '-'),
                striped_to_safe_characters = striped_spaces.match(/^[A-Za-z0-9._~()'!*:@,;+?-\W]*$/g),

                source_name = Array(16)
                    .fill(0)
                    .map(x => Math.random().toString(36).charAt(2))
                    .join('') + '-' + striped_to_safe_characters + '.part'

            do {
                let isLast = chunks.length === 1,
                    chunk = chunks.shift(),
                    attempts = 0

                // Set form data
                formData.set('filename', item.file.name);
                formData.set('file', chunk, source_name);
                formData.set('parent_id', item.parent_id)
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

        Vue.prototype.$getCurrentLocationName = function () {
            if (store.getters.currentFolder) {
                return store.getters.currentFolder.data.attributes.name
            } else {
                return {
                    'RecentUploads': this.$t('Recent'),
                    'MySharedItems': this.$t('Shared'),
                    'Trash': this.$t('Trash'),
                    'Public': this.$t('Files'),
                    'Files': this.$t('Files'),
                    'TeamFolders': this.$t('Team Folders'),
                    'SharedWithMe': this.$t('Shared With Me'),
                }[this.$route.name]
            }
        }

        Vue.prototype.$goToFileView = function (id) {

            let locations = {
                'Public': {name: 'Public', params: {token: this.$route.params.token, id: id}},
                'TeamFolders': {name: 'TeamFolders', params: {id: id}},
                'SharedWithMe': {name: 'SharedWithMe', params: {id: id}},
                'MySharedItems': {name: 'Files', params: {id: id}},
                'Trash': {name: 'Trash', params: {id: id}},
                'Files': {name: 'Files', params: {id: id}},
            }

            this.$router.push(locations[this.$router.currentRoute.name])
        }

        Vue.prototype.$isThisRoute = function (route, locations) {

            return locations.includes(route.name)
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

        // Detect windows
        Vue.prototype.$isWindows = function () {
            return navigator.userAgent.indexOf('Windows') != -1
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

        Vue.prototype.$closePopup = function () {
            events.$emit('popup:close')
        }

        Vue.prototype.$openInDetailPanel = function (entry) {
            // Dispatch load file info detail
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', entry)

            // Show panel if is not open
            this.$store.dispatch('fileInfoToggle', true)
        }

        Vue.prototype.$openSpotlight = function () {
            events.$emit('spotlight:show')
        }

        Vue.prototype.$showLocations = function () {
            events.$emit('mobile-menu:show', 'file-filter')
        }

        Vue.prototype.$createItems = function () {
            events.$emit('mobile-menu:show', 'create-list')
        }

        Vue.prototype.$enableMultiSelectMode = function () {
            events.$emit('mobile-select:start')
        }

        Vue.prototype.$showViewOptions = function () {
            events.$emit('mobile-menu:show', 'file-sorting')
        }
    }
}

export default FunctionHelpers
