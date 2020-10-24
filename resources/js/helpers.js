import i18n from '@/i18n/index'
import store from './store/index'
import {debounce, includes} from "lodash";
import {events} from './bus'
import axios from 'axios'
import router from '@/router'

const Helpers = {
    install(Vue) {

        Vue.prototype.$updateText = debounce(function (route, name, value) {
    
            let enableEmptyInput = ['mimetypes_blacklist' , 'google_analytics']

            if (value === '' && !enableEmptyInput.includes(name)) return

            axios.post(this.$store.getters.api + route, {name, value, _method: 'patch'})
                .catch(error => {
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
            var container = document.getElementById('vue-file-manager')

            if (container) {
                container.scrollTop = 0
            }
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

        Vue.prototype.$createFolder = function (folderName) {
            this.$store.dispatch('createFolder', folderName)
        }

        Vue.prototype.$handleUploading = async function (files, parent_id) {

            let fileBuffer = []

            // Append the file list to fileBuffer array
            Array.prototype.push.apply(fileBuffer, files);

            let fileSucceed = 0

            // Update files count in progressbar
            store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                current: fileSucceed,
                total: files.length
            })

            // Reset upload progress to 0
            store.commit('UPLOADING_FILE_PROGRESS', 0)

            // Get parent id
            let parentFolder = this.$store.getters.currentFolder ? this.$store.getters.currentFolder.unique_id : 0
            let rootFolder = parent_id ? parent_id : parentFolder

            // Upload files
            do {
                let file = fileBuffer.shift(),
                    chunks = []

                // Calculate ceils
                let size = this.$store.getters.config.chunkSize,
                    chunksCeil = Math.ceil(file.size / size);

                // Create chunks
                for (let i = 0; i < chunksCeil; i++) {
                    chunks.push(file.slice(
                        i * size, Math.min(i * size + size, file.size), file.type
                    ));
                }

                // Set Data
                let formData = new FormData(),
                    uploadedSize = 0,
                    isNotGeneralError = true,
                    striped_name = file.name.replace(/[^A-Za-z 0-9 \.,\?""!@#\$%\^&\*\(\)-_=\+;:<>\/\\\|\}\{\[\]`~]*/g, ''),
                    filename = Array(16).fill(0).map(x => Math.random().toString(36).charAt(2)).join('') + '-' + striped_name + '.part'

                do {
                    let isLast = chunks.length === 1,
                        chunk = chunks.shift(),
                        attempts = 0

                    // Set form data
                    formData.set('file', chunk, filename);
                    formData.set('parent_id', rootFolder)
                    formData.set('is_last', isLast);

                    // Upload chunks
                    do {
                        await store.dispatch('uploadFiles', {
                            form: formData,
                            fileSize: file.size,
                            totalUploadedSize: uploadedSize
                        }).then(() => {
                            uploadedSize = uploadedSize + chunk.size
                        }).catch((error) => {

                            // Count attempts
                            attempts++

                            // Break uploading proccess
                            if (error.response.status === 500)
                                isNotGeneralError = false

                            //Break if mimetype of file is in blacklist
                            if(error.response.status === 415)
                                isNotGeneralError = false

                            // Show Error
                            if (attempts === 3)
                                this.$isSomethingWrong()
                        })
                    } while (isNotGeneralError && attempts !== 0 && attempts !== 3)

                } while (isNotGeneralError && chunks.length !== 0)

                fileSucceed++

                // Progress file log
                store.commit('UPDATE_FILE_COUNT_PROGRESS', {
                    current: fileSucceed,
                    total: files.length
                })

            } while (fileBuffer.length !== 0)

            store.commit('UPDATE_FILE_COUNT_PROGRESS', undefined)
        }

        Vue.prototype.$uploadFiles = async function (files) {

            if (files.length == 0) return

           if (!this.$checkFileMimetype(files)) return
           
            this.$handleUploading(files, undefined)
        }

        Vue.prototype.$uploadExternalFiles = async function (event, parent_id) {

            // Prevent submit empty files
            if (event.dataTransfer.items.length == 0) return

            // Get files
            let files = [...event.dataTransfer.items].map(item => item.getAsFile());

            this.$handleUploading(files, parent_id)
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
        Vue.prototype.$checkFileMimetype = function(files) {
            let validated = true
            let mimetypesBlacklist = store.getters.config.mimetypesBlacklist
            
              for (let i = 0 ; i<files.length; i++ ) {
                  let fileType = files[i].type.split('/')

                  if(!fileType[0]) {
                      fileType[1] = _.last(files[i].name.split('.'))
                  }

                  if(mimetypesBlacklist.includes(fileType[1])) {  
                      validated = false
                                          
                      events.$emit('alert:open', {
                        emoji: '😬😬😬',
                        title: i18n.t('popup_mimetypes_blacklist.title'),
                        message: i18n.t('popup_mimetypes_blacklist.message', {mimetype: fileType[1]}),
                    })
                  }else {
                      validated = true 
                  }
              } 
              return validated
        }
    }
}

export default Helpers
