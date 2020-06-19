import store from './store/index'
import {debounce, includes} from "lodash";
import {events} from './bus'
import axios from 'axios'
import router from '@/router'

const Helpers = {
    install(Vue) {

        Vue.prototype.$updateText = debounce(function (route, name, value) {

            if (value === '') return

            axios.patch(this.$store.getters.api + route, {name, value})
                .catch(error => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                })
        }, 300)

        Vue.prototype.$getCreditCardBrand = function (brand) {
            return `/assets/icons/${brand}.svg`
        }

        Vue.prototype.$updateImage = function (route, name, image) {

            // Create form
            let formData = new FormData()

            // Add image to form
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

        Vue.prototype.$openImageOnNewTab = function (source) {
            let win = window.open(source, '_blank')

            win.focus()
        }

        Vue.prototype.$createFolder = function (folderName) {
            this.$store.dispatch('createFolder', folderName)
        }

        Vue.prototype.$uploadFiles = async function (files) {
            // Prevent submit empty files
            if (files && files.length == 0) return

            let fileCount = files ? files.length : 0
            let fileCountSucceed = 1

            store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                current: fileCountSucceed,
                total: fileCount
            })

            // Get parent id
            const rootFolder = this.$store.getters.currentFolder
                ? this.$store.getters.currentFolder.unique_id
                : 0

            for (var i = files.length - 1; i >= 0; i--) {
                let formData = new FormData()

                // Append data
                formData.append('file', files[i])

                // Append form data
                formData.append('parent_id', rootFolder)

                // Upload data
                await store.dispatch('uploadFiles', formData)
                    .then(() => {
                        // Progress file log
                        store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                            current: fileCountSucceed,
                            total: fileCount
                        })

                        // Uploading finished
                        if (fileCount === fileCountSucceed) {
                            store.commit('UPDATE_FILE_COUNT_PROGRESS', undefined)
                        } else {
                            // Add uploaded file
                            fileCountSucceed++
                        }
                    }).catch(error => {
                        switch (error.response.status) {
                            case 423:
                                events.$emit('alert:open', {
                                    emoji: '😬',
                                    title: this.$t('popup_exceed_limit.title'),
                                    message: this.$t('popup_exceed_limit.message')
                                })
                                break;
                            case 413:
                                events.$emit('alert:open', {
                                    emoji: '😟',
                                    title: this.$t('popup_paylod_error.title'),
                                    message: this.$t('popup_paylod_error.message')
                                })
                                break;
                            default:
                                events.$emit('alert:open', {
                                    title: this.$t('popup_error.title'),
                                    message: this.$t('popup_error.message'),
                                })
                                break;
                        }
                    })
            }
        }

        Vue.prototype.$uploadExternalFiles = async function (event, parent_id) {

            // Prevent submit empty files
            if (event.dataTransfer.items.length == 0) return

            // Get files
            const files = [...event.dataTransfer.items].map(item => item.getAsFile());

            let fileCountSucceed = 1

            store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                current: fileCountSucceed,
                total: files.length
            })

            for (var i = files.length - 1; i >= 0; i--) {

                let formData = new FormData()

                // Append data
                formData.append('file', files[i])

                // Append form data
                formData.append('parent_id', parent_id)

                // Upload data
                await store.dispatch('uploadFiles', formData).then(() => {
                    // Progress file log
                    store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                        current: fileCountSucceed,
                        total: files.length
                    })
                    // Progress file log
                    store.commit('INCREASE_FOLDER_ITEM', parent_id)

                    // Uploading finished
                    if (files.length === fileCountSucceed) {
                        store.commit('UPDATE_FILE_COUNT_PROGRESS', undefined)
                    } else {
                        // Add uploaded file
                        fileCountSucceed++
                    }
                }).catch(error => {
                    switch (error.response.status) {
                        case 423:
                            events.$emit('alert:open', {
                                emoji: '😬',
                                title: this.$t('popup_exceed_limit.title'),
                                message: this.$t('popup_exceed_limit.message')
                            })
                            break;
                        case 413:
                            events.$emit('alert:open', {
                                emoji: '😟',
                                title: this.$t('popup_paylod_error.title'),
                                message: this.$t('popup_paylod_error.message')
                            })
                            break;
                        default:
                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                            break;
                    }
                })
            }
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

            return includes(locations, route.name)
        }

        Vue.prototype.$isThisLocation = function (location) {

            // Get current location
            let currentLocation = store.getters.currentFolder && store.getters.currentFolder.location ? store.getters.currentFolder.location : undefined

            // Check if type is object
            if (typeof location === 'Object' || location instanceof Object) {
                return includes(location, currentLocation)

            } else {
                return currentLocation === location
            }
        }

        Vue.prototype.$checkPermission = function (type) {

            let currentPermission = store.getters.permission

            // Check if type is object
            if (typeof type === 'Object' || type instanceof Object) {
                return includes(type, currentPermission)

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

        Vue.prototype.$isMinimalScale = function () {
            let sizeType = store.getters.filesViewWidth

            return sizeType === 'minimal-scale'
        }

        Vue.prototype.$isCompactScale = function () {
            let sizeType = store.getters.filesViewWidth

            return sizeType === 'compact-scale'
        }

        Vue.prototype.$isFullScale = function () {
            let sizeType = store.getters.filesViewWidth

            return sizeType === 'full-scale'
        }

        Vue.prototype.$isSomethingWrong = function () {

            events.$emit('alert:open', {
                title: this.$t('popup_error.title'),
                message: this.$t('popup_error.message'),
            })
        }
    }
}

export default Helpers
