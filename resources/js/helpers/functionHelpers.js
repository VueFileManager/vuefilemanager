import { debounce, isArray, orderBy } from 'lodash'
import i18n from '../i18n'
import router from '../router'
import store from '../store/index'
import { events } from '../bus'
import axios from 'axios'

const FunctionHelpers = {
    install(Vue) {
        Vue.prototype.$updateText = debounce(function (route, name, value, allowEmpty = false) {
            if ((value === '' || value === ' ' || typeof value === 'object') && !allowEmpty) return

            axios
                .post(store.getters.api + route, {
                    name,
                    value,
                    _method: 'patch',
                })
                .catch(() => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
        }, 150)

        Vue.prototype.$updateInput = debounce(function (route, name, value, allowEmpty = false) {
            if ((value === '' || value === ' ' || typeof value === 'object') && !allowEmpty) return

            axios
                .post(store.getters.api + route, {
                    [name]: value,
                    _method: 'patch',
                })
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

            axios
                .post(store.getters.api + route, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .catch((error) => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
        }

        Vue.prototype.$scrollTop = function () {
            const container = document.getElementsByTagName('html')[0]

            if (container) {
                container.scrollTop = 0
            }
        }

        Vue.prototype.$translateSelectOptions = function (options) {
            return options.map((role) => {
                let key, values

                if (isArray(role.label)) {
                    ;[key, values] = role.label
                }

                return {
                    label: isArray(role.label) ? i18n.t(key, values) : i18n.t(role.label),
                    value: role.value,
                    icon: role.icon ? role.icon : '',
                }
            })
        }

        Vue.prototype.$mapStorageUsage = function (storage) {
            let distribution = [
                {
                    progress: storage.data.meta.images.percentage,
                    color: 'success',
                    value: storage.data.meta.images.used,
                    title: i18n.t('images'),
                },
                {
                    progress: storage.data.meta.videos.percentage,
                    color: 'danger',
                    value: storage.data.meta.videos.used,
                    title: i18n.t('videos'),
                },
                {
                    progress: storage.data.meta.audios.percentage,
                    color: 'warning',
                    value: storage.data.meta.audios.used,
                    title: i18n.t('audios'),
                },
                {
                    progress: storage.data.meta.documents.percentage,
                    color: 'info',
                    value: storage.data.meta.documents.used,
                    title: i18n.t('documents'),
                },
                {
                    progress: storage.data.meta.others.percentage,
                    color: 'purple',
                    value: storage.data.meta.others.used,
                    title: i18n.t('others'),
                },
            ]

            // Order distribution by it's size
            distribution = orderBy(distribution, ['progress'], ['desc'])

            // Push at the end empty space data
            if (config.subscriptionType === 'fixed' || (config.subscriptionType === 'none' && config.storageLimit)) {
                distribution.push({
                    progress: 100 - storage.data.attributes.percentage,
                    color: 'secondary',
                    value: storage.data.meta.others.used,
                    title: i18n.t('empty'),
                })
            }

            return distribution
        }

        Vue.prototype.$getImage = function (source) {
            return source ? store.getters.config.host + '/' + source : ''
        }

        Vue.prototype.$getCreditCardBrand = function (brand) {
            return `/assets/icons/${brand}.svg`
        }

        Vue.prototype.$getInvoiceLink = function (id) {
            return '/invoices/' + id
        }

        Vue.prototype.$uploadFiles = async function (files) {
            // Show alert message when upload is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canUpload) {
                Vue.prototype.$temporarilyDisabledUpload()

                return
            }

            if (files.length === 0) return

            if (!this.$checkFileMimetype(files) || !this.$checkUploadLimit(files)) return // Push items to file queue
            ;[...files].map((item) => {
                store.commit('ADD_FILES_TO_QUEUE', {
                    parent_id: store.getters.currentFolder ? store.getters.currentFolder.data.id : '',
                    file: item,
                    path: '/' + item.webkitRelativePath,
                })
            })

            // Start uploading if uploading process isn't running
            if (store.getters.filesInQueueTotal == 0) this.$handleUploading(store.getters.fileQueue[0])

            // Increase total files in upload bar
            store.commit('INCREASE_FILES_IN_QUEUES_TOTAL', files.length)
        }

        Vue.prototype.$uploadDraggedFiles = async function (event, parent_id) {
            // Show alert message when upload is disabled
            if (!store.getters.user.data.meta.restrictions.canUpload) {
                Vue.prototype.$temporarilyDisabledUpload()

                return
            }

            // Prevent submit empty files
            if (event.dataTransfer.items.length === 0) return // Push items to file queue
            ;[...event.dataTransfer.items].map((item) => {
                store.commit('ADD_FILES_TO_QUEUE', {
                    parent_id: parent_id ? parent_id : '',
                    file: item.getAsFile(),
                })
            })

            // Start uploading if uploading process isn't running
            if (store.getters.filesInQueueTotal == 0) this.$handleUploading(store.getters.fileQueue[0])

            // Increase total files in upload bar
            store.commit('INCREASE_FILES_IN_QUEUES_TOTAL', [...event.dataTransfer.items].length)
        }

        Vue.prototype.$handleUploading = async function (item) {
            // Create ceil
            let size = store.getters.config.chunkSize,
                chunksCeil = Math.ceil(item.file.size / size),
                chunks = []

            // Create chunks
            for (let i = 0; i < chunksCeil; i++) {
                chunks.push(item.file.slice(i * size, Math.min(i * size + size, item.file.size), item.file.type))
            }

            // Set Data
            let formData = new FormData(),
                uploadedSize = 0,
                isNotGeneralError = true,
                striped_spaces = item.file.name.replace(/\s/g, '-'),
                striped_to_safe_characters = striped_spaces.match(/^[A-Za-z0-9._~()'!*:@,;+?-\W]*$/g),
                source_name =
                    Array(16)
                        .fill(0)
                        .map((x) => Math.random().toString(36).charAt(2))
                        .join('') +
                    '-' +
                    striped_to_safe_characters +
                    '.part'

            do {
                let isLast = chunks.length === 1,
                    chunk = chunks.shift(),
                    attempts = 0

                // Set form data
                formData.set('filename', item.file.name)
                formData.set('file', chunk, source_name)
                formData.set('path', item.path)
                formData.set('parent_id', item.parent_id)
                formData.set('extension', item.file.name.split('.').pop())
                formData.set('is_last', isLast)

                // Upload chunks
                do {
                    await store
                        .dispatch('uploadFiles', {
                            form: formData,
                            fileSize: item.file.size,
                            totalUploadedSize: uploadedSize,
                        })
                        .then(() => {
                            uploadedSize = uploadedSize + chunk.size
                        })
                        .catch((error) => {
                            // Count attempts
                            attempts++

                            // Show Error
                            //if (attempts === 3)

                            // Break uploading process
                            if ([500, 422].includes(error.response.status)) {
                                isNotGeneralError = false
                                this.$isSomethingWrong()
                            }
                        })
                } while (isNotGeneralError && attempts !== 0 && attempts !== 3)
            } while (isNotGeneralError && chunks.length !== 0)
        }

        Vue.prototype.$downloadFile = function (url, filename) {
            // Show alert message when download is disabled
            if (store.getters.user && !store.getters.user.data.meta.restrictions.canDownload) {
                Vue.prototype.$temporarilyDisabledDownload()

                return
            }

            let anchor = document.createElement('a')

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
                    RequestUpload: this.$t('home'),
                    RecentUploads: this.$t('menu.latest'),
                    MySharedItems: this.$t('publicly_shared'),
                    Trash: this.$t('trash'),
                    Public: this.$t('menu.files'),
                    Files: this.$t('sidebar.home'),
                    TeamFolders: this.$t('team_folders'),
                    SharedWithMe: this.$t('shared_with_me'),
                }[this.$route.name]
            }
        }

        Vue.prototype.$getCurrentSectionName = function () {
            return {
                RecentUploads: this.$t('menu.latest'),
                MySharedItems: this.$t('publicly_shared'),
                Trash: this.$t('trash'),
                Public: this.$t('menu.files'),
                Files: this.$t('sidebar.home'),
                TeamFolders: this.$t('team_folders'),
                SharedWithMe: this.$t('shared_with_me'),
            }[this.$route.name]
        }

        Vue.prototype.$getCurrentSectionIcon = function () {
            return {
                RecentUploads: 'upload-cloud',
                MySharedItems: 'share',
                Trash: 'trash2',
                Public: 'hard-drive',
                Files: 'hard-drive',
                TeamFolders: 'users',
                SharedWithMe: 'user-check',
            }[this.$router.currentRoute.name]
        }

        Vue.prototype.$getDataByLocation = function () {
            let routes = {
                RequestUpload: ['getUploadRequestFolder', router.currentRoute.params.id || undefined ],
                Public: ['getSharedFolder', router.currentRoute.params.id || undefined],
                Files: ['getFolder', router.currentRoute.params.id || undefined],
                RecentUploads: ['getRecentUploads'],
                MySharedItems: ['getMySharedItems'],
                Trash: ['getTrash', router.currentRoute.params.id || undefined],
                TeamFolders: ['getTeamFolder', router.currentRoute.params.id || undefined],
                SharedWithMe: ['getSharedWithMeFolder', router.currentRoute.params.id || undefined],
            }

            store.dispatch(...routes[router.currentRoute.name])
        }

        Vue.prototype.$getPaymentLogo = function (driver) {
            return (
                {
                    paypal: store.getters.isDarkMode
                        ? '/assets/payments/paypal-dark.svg'
                        : '/assets/payments/paypal.svg',
                    paystack: store.getters.isDarkMode
                        ? '/assets/payments/paystack-dark.svg'
                        : '/assets/payments/paystack.svg',
                    stripe: '/assets/payments/stripe.svg',
                    system: store.getters.isDarkMode
                        ? this.$getImage(store.getters.config.app_logo_horizontal_dark)
                        : this.$getImage(store.getters.config.app_logo_horizontal),
                }[driver] || this.$getImage(store.getters.config.app_logo_horizontal)
            )
        }

        Vue.prototype.$getSocialLogo = function (driver) {
            return {
                google: '/assets/socials/google.svg',
                facebook: '/assets/socials/facebook.svg',
                github: store.getters.isDarkMode ? '/assets/socials/github-dark.svg' : '/assets/socials/github.svg',
            }[driver]
        }

        Vue.prototype.$getSubscriptionStatusColor = function (status) {
            return {
                active: 'green',
                cancelled: 'yellow',
                completed: 'purple',
            }[status]
        }

        Vue.prototype.$getTransactionStatusColor = function (status) {
            return {
                completed: 'green',
                cancelled: 'yellow',
                error: 'red',
            }[status]
        }

        Vue.prototype.$getTransactionTypeColor = function (type) {
            return {
                credit: 'green',
                charge: 'purple',
                withdrawal: 'red',
            }[type]
        }

        Vue.prototype.$getTransactionStatusColor = function (type) {
            return {
                completed: 'green',
                error: 'red',
            }[type]
        }

        Vue.prototype.$getPlanStatusColor = function (type) {
            return {
                active: 'green',
                archived: 'red',
            }[type]
        }

        Vue.prototype.$getUserRoleColor = function (role) {
            return {
                admin: 'purple',
                user: 'green',
            }[role]
        }

        Vue.prototype.$getTransactionTypeTextColor = function (type) {
            return {
                withdrawal: 'text-red',
                credit: 'text-green',
                charge: '',
            }[type]
        }

        Vue.prototype.$getTransactionMark = function (type) {
            return {
                withdrawal: '-',
                credit: '+',
                charge: '',
            }[type]
        }

        Vue.prototype.$goToFileView = function (id) {
            let locations = {
                RequestUpload: {name: 'RequestUpload', params: { token: this.$route.params.token, id: id }},
                Public: {name: 'Public', params: { token: this.$route.params.token, id: id }},
                TeamFolders: { name: 'TeamFolders', params: { id: id } },
                SharedWithMe: { name: 'SharedWithMe', params: { id: id } },
                MySharedItems: { name: 'Files', params: { id: id } },
                Trash: { name: 'Trash', params: { id: id } },
                Files: { name: 'Files', params: { id: id } },
            }

            this.$router.push(locations[this.$router.currentRoute.name])
        }

        Vue.prototype.$isThisRoute = function (route, locations) {
            return locations.includes(route.name)
        }

        // TODO: not working correctly in share page
        Vue.prototype.$checkPermission = function (type) {
            let currentPermission = store.getters.permission

            // Check if type is object
            if (typeof type === 'Object' || type instanceof Object) {
                return type.includes(currentPermission)
            } else {
                return currentPermission === type
            }
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
                        message: i18n.t('popup_mimetypes_blacklist.message', {
                            mimetype: fileType[1],
                        }),
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
                        message: i18n.t('popup_upload_limit.message', {
                            uploadLimit: store.getters.config.uploadLimitFormatted,
                        }),
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
            const toMatch = [/iPhone/i, /iPad/i, /iPod/i, /iOS/i, /macOS/i, /Macintosh/i]

            return toMatch.some((toMatchItem) => {
                return navigator.userAgent.match(toMatchItem)
            })
        }

        Vue.prototype.$isMobile = function () {
            const toMatch = [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i]

            return toMatch.some((toMatchItem) => {
                return navigator.userAgent.match(toMatchItem)
            })
        }

        Vue.prototype.$mapIntoMemberResource = function (entry) {
            return {
                data: {
                    attributes: {
                        avatar: entry.avatar,
                        name: entry.name,
                        email: entry.email,
                        color: entry.color,
                    },
                },
            }
        }

        Vue.prototype.$closePopup = function () {
            events.$emit('popup:close')
        }

        Vue.prototype.$openInDetailPanel = function (entry) {
            // Dispatch load file info detail
            store.commit('CLIPBOARD_CLEAR')
            store.commit('ADD_ITEM_TO_CLIPBOARD', entry)

            // Show panel if is not open
            store.dispatch('fileInfoToggle', true)
        }

        Vue.prototype.$openSpotlight = function (filter = undefined) {
            events.$emit('spotlight:show', filter)
        }

        Vue.prototype.$enableMultiSelectMode = function () {
            store.commit('TOGGLE_MULTISELECT_MODE')
        }

        Vue.prototype.$showMobileMenu = function (name) {
            events.$emit('mobile-menu:show', name)
        }

        Vue.prototype.$openSubscribeOptions = function () {
            events.$emit('popup:open', { name: 'select-plan-subscription' })
        }

        Vue.prototype.$changeSubscriptionOptions = function () {
            events.$emit('popup:open', { name: 'change-plan-subscription' })
        }
    },
}

export default FunctionHelpers
