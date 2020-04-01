<template>
    <div id="mobile-actions-wrapper">
        <div class="mobile-actions">
            <MobileActionButton v-if="! $isTrashLocation()" @click.native="createFolder" icon="folder-plus" :text="$t('context_menu.add_folder')"></MobileActionButton>
            <MobileActionButtonUpload v-if="! $isTrashLocation()" @input.native="$uploadFiles" icon="upload" :text="$t('context_menu.upload')"></MobileActionButtonUpload>
            <MobileActionButton @click.native="switchPreview" :icon="previewIcon" :text="previewText"></MobileActionButton>
            <MobileActionButton  v-if="$isTrashLocation()" @click.native="$store.dispatch('emptyTrash')" icon="trash-alt" :text="$t('context_menu.empty_trash')"></MobileActionButton>
        </div>
        <UploadProgress />
    </div>
</template>

<script>
    import MobileActionButtonUpload from '@/components/VueFileManagerComponents/FilesView/MobileActionButtonUpload'
    import MobileActionButton from '@/components/VueFileManagerComponents/FilesView/MobileActionButton'
    import UploadProgress from '@/components/VueFileManagerComponents/FilesView/UploadProgress'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'

    export default {
        name: 'MobileActions',
        components: {
            MobileActionButtonUpload,
            MobileActionButton,
            UploadProgress,
        },
        computed: {
            ...mapGetters(['preview_type']),
            previewIcon() {
                return this.preview_type === 'list' ? 'th' : 'th-list'
            },
            previewText() {
                return this.preview_type === 'list' ? this.$t('preview_type.grid') : this.$t('preview_type.list')
            }
        },
        methods: {
            switchPreview() {
                this.$store.dispatch('changePreviewType')
            },
            createFolder() {
                if (this.$isMobile()) {
                    // Get folder name
                    let folderName = prompt(this.$t('popup_create_folder.title'))

                    // Create folder
                    if (folderName) this.$createFolder(folderName)
                } else {
                    // Create folder
                    this.$createFolder(this.$t('popup_create_folder.folder_default_name'))
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";


    #mobile-actions-wrapper {
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-actions {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: nowrap;
        overflow-x: auto;
    }

    @media (prefers-color-scheme: dark) {
        #mobile-actions-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
