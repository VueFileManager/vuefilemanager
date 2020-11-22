<template>
    <div id="mobile-actions-wrapper">

        <!--Actions for trash location with MASTER permission--->
        <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" class="mobile-actions">
            <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                {{ previewText }}
            </MobileActionButton>
            <MobileMultiSelectButton @click.native="mobileMultiSelect = !mobileMultiSelect">
                {{ $t('context_menu.select') }}
            </MobileMultiSelectButton>
            <MobileActionButton @click.native="$store.dispatch('emptyTrash')" icon="trash">
                {{ $t('context_menu.empty_trash') }}
            </MobileActionButton>
        </div>

        <!--ContextMenu for Base location with MASTER permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission(['master', 'editor'])" class="mobile-actions">
            <MobileActionButton @click.native="createFolder" icon="folder-plus" :class="{'is-inactive' : mobileMultiSelect}">
                {{ $t('context_menu.add_folder') }}
            </MobileActionButton>
            <MobileActionButtonUpload :class="{'is-inactive' : mobileMultiSelect}">
                {{ $t('context_menu.upload') }}
            </MobileActionButtonUpload>
            <MobileMultiSelectButton @click.native="mobileMultiSelect = !mobileMultiSelect">
               {{ $t('context_menu.select') }}
            </MobileMultiSelectButton>
            <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                {{ previewText }}
            </MobileActionButton>
        </div>

        <!--ContextMenu for Base location with VISITOR permission-->
        <div v-if="($isThisLocation(['base', 'shared', 'public']) && $checkPermission('visitor')) || ($isThisLocation(['latest', 'shared']) && $checkPermission('master'))" class="mobile-actions">
            <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                {{ previewText }}
            </MobileActionButton>
             <MobileMultiSelectButton @click.native="mobileMultiSelect = !mobileMultiSelect">
               {{ $t('context_menu.select') }}
            </MobileMultiSelectButton>
        </div>

        <!--Upload Progressbar-->
        <UploadProgress />
    </div>
</template>

<script>
    import MobileActionButtonUpload from '@/components/FilesView/MobileActionButtonUpload'
    import MobileMultiSelectButton from '@/components/FilesView/MobileMultiSelectButton'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import UploadProgress from '@/components/FilesView/UploadProgress'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'

    export default {
        name: 'MobileActions',
        components: {
            MobileActionButtonUpload,
            MobileMultiSelectButton,
            MobileActionButton,
            UploadProgress,
        },
        computed: {
            ...mapGetters(['FilePreviewType']),
            previewIcon() {
                return this.FilePreviewType === 'list' ? 'th' : 'th-list'
            },
            previewText() {
                return this.FilePreviewType === 'list' ? this.$t('preview_type.grid') : this.$t('preview_type.list')
            }
        },
        data () {
            return {
                mobileMultiSelect: false,
                turnOff:false
            }
        },
        watch: {
            mobileMultiSelect () {
                
                if(this.mobileMultiSelect ) {
                    events.$emit('mobileSelecting-start')
                }
                if(!this.mobileMultiSelect) {
                    events.$emit('mobileSelecting-stop')
                }
            }
        },
        methods: {
            switchPreview() {
                this.$store.dispatch('changePreviewType')
                events.$emit('mobileSelecting-stop')
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
        },
        mounted () {
                events.$on('mobileSelecting-stop', () => {
                    this.mobileMultiSelect = false
                }) 

        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    #mobile-actions-wrapper {
        display: none;
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-action-button {
        &.is-inactive {
            opacity: 0.25;
            pointer-events: none;
        }
    }

    .mobile-actions {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: nowrap;
        overflow-x: auto;
    }

    @media only screen and (max-width: 960px) {

        #mobile-actions-wrapper {
            display: block;
        }
    }

    @media (prefers-color-scheme: dark) {
        #mobile-actions-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
