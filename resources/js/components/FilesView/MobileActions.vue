<template>
    <div id="mobile-actions-wrapper">

        <!--Actions for trash location with MASTER permission--->
        <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" class="mobile-actions">
            <MobileActionButton @click.native="$store.dispatch('emptyTrash')" icon="trash">
                {{ $t('context_menu.empty_trash') }}
            </MobileActionButton>
             <MobileMultiSelectButton @click.native="mobileMultiSelect = !mobileMultiSelect">
                {{ $t('context_menu.select') }}
            </MobileMultiSelectButton>
             <MobileActionButton class="preview-sorting" @click.native="mobileSortingAndPreview = ! mobileSortingAndPreview" icon="preview-sorting">
                {{$t('preview_sorting.preview_sorting_button')}}
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
            <MobileActionButton class="preview-sorting" @click.native="mobileSortingAndPreview = ! mobileSortingAndPreview" icon="preview-sorting">
                {{$t('preview_sorting.preview_sorting_button')}}
            </MobileActionButton>
        </div>

        <!--ContextMenu for Base location with VISITOR permission-->
        <div v-if="($isThisLocation(['base', 'shared', 'public']) && $checkPermission('visitor')) || ($isThisLocation(['latest', 'shared']) && $checkPermission('master'))" class="mobile-actions">
             <MobileMultiSelectButton @click.native="mobileMultiSelect = !mobileMultiSelect">
               {{ $t('context_menu.select') }}
            </MobileMultiSelectButton>
             <MobileActionButton class="preview-sorting" @click.native="mobileSortingAndPreview = ! mobileSortingAndPreview" icon="preview-sorting">
                {{$t('preview_sorting.preview_sorting_button')}}
            </MobileActionButton>
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
        },
        data () {
            return {
                mobileMultiSelect: false,
                turnOff:false,
                mobileSortingAndPreview: false
            }
        },
        watch: {
            mobileMultiSelect () {
                
                if(this.mobileMultiSelect ) {
                    events.$emit('mobileSelecting:start')
                }
                if(!this.mobileMultiSelect) {
                    events.$emit('mobileSelecting:stop')
                }
            },
            mobileSortingAndPreview () {
                // TODO: co to
                if(this.mobileSortingAndPreview) {
                    events.$emit('mobileSortingAndPreview' , true)
                    events.$emit('mobileSortingAndPreviewVignette' , true)
                    this.mobileMultiSelect = false
                }

                if(!this.mobileSortingAndPreview) {
                    events.$emit('mobileSortingAndPreview', false)
                    events.$emit('mobileSortingAndPreviewVignette' , false)
                }
            }
        },
        methods: {
            createFolder() {
                events.$emit('popup:open', {name: 'create-folder'})
            },
        },
        mounted () {
                events.$on('mobileSelecting:stop', () => {
                    this.mobileMultiSelect = false
                }) 

                events.$on('mobileSortingAndPreview', (state) => {
                    this.mobileSortingAndPreview = state
                })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .preview-sorting { 
        background: $light_background !important;
        /deep/ .label {
            color: $text !important;
        }
        /deep/ .preview-sorting {
              path, line, polyline, rect, circle {
                    stroke: $text !important;
                }
        }
    }

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
        .preview-sorting { 
            background: $dark_mode_foreground !important;
            /deep/ .label {
                color: $dark_mode_text_primary !important;
            }
            /deep/ .preview-sorting {
                path, line, polyline, rect, circle {
                        stroke: $theme !important;
                    }
            }
        }
    }
</style>
