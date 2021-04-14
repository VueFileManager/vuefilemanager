<template>
    <div id="mobile-actions-wrapper">

        <!--Actions for trash location--->
        <div v-if="trashLocationMenu && ! multiSelectMode" class="mobile-actions">
            <MobileActionButton @click.native="$store.dispatch('emptyTrash')" icon="trash">
                {{ $t('context_menu.empty_trash') }}
            </MobileActionButton>
             <MobileActionButton @click.native="enableMultiSelectMode" icon="check-square">
                {{ $t('context_menu.select') }}
            </MobileActionButton>
             <MobileActionButton @click.native="showViewOptions" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </div>

        <!--Actions for Base location-->
        <transition name="button">
            <div v-if="baseLocationMasterMenu && ! multiSelectMode" class="mobile-actions">
                <MobileActionButton @click.native="showLocations" icon="filter">
                    {{ filterLocationTitle }}
                </MobileActionButton>
                <MobileActionButton @click.native="createFolder" icon="folder-plus">
                    {{ $t('context_menu.add_folder') }}
                </MobileActionButton>
                <MobileActionButtonUpload>
                    {{ $t('context_menu.upload') }}
                </MobileActionButtonUpload>
                <MobileActionButton @click.native="enableMultiSelectMode" icon="check-square">
                    {{ $t('context_menu.select') }}
                </MobileActionButton>
                <MobileActionButton @click.native="showViewOptions" icon="preview-sorting">
                    {{ $t('preview_sorting.preview_sorting_button') }}
                </MobileActionButton>
            </div>
        </transition>

        <!-- Selecting buttons -->
        <transition name="button">
            <div v-if="multiSelectMode" class="mobile-actions">
                <MobileActionButton @click.native="selectAll" icon="check-square">
                    {{ $t('mobile_selecting.select_all') }}
                </MobileActionButton>
                <MobileActionButton @click.native="deselectAll" icon="x-square">
                    {{ $t('mobile_selecting.deselect_all') }}
                </MobileActionButton>
                <MobileActionButton @click.native="disableMultiSelectMode" icon="check">
                    {{ $t('mobile_selecting.done') }}
                </MobileActionButton>
            </div>
        </transition>

        <!--Actions for Base location in shared folder with visit permission-->
        <div v-if="baseLocationVisitorMenu && ! multiSelectMode" class="mobile-actions">
             <MobileActionButton @click.native="enableMultiSelectMode" icon="check-square">
               {{ $t('context_menu.select') }}
            </MobileActionButton>
             <MobileActionButton @click.native="showViewOptions" icon="preview-sorting">
                {{ $t('preview_sorting.preview_sorting_button') }}
            </MobileActionButton>
        </div>

        <!--Upload Progressbar-->
        <UploadProgress />
    </div>
</template>

<script>
    import MobileActionButtonUpload from '@/components/FilesView/MobileActionButtonUpload'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import UploadProgress from '@/components/FilesView/UploadProgress'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import store from "../../store";

    export default {
        name: 'FileActionsMobile',
        components: {
            MobileActionButtonUpload,
            MobileActionButton,
            UploadProgress,
        },
        computed: {
            ...mapGetters([
                'FilePreviewType'
            ]),
            previewIcon() {
                return this.FilePreviewType === 'list'
                    ? 'th'
                    : 'th-list'
            },
            trashLocationMenu() {
                return this.$isThisLocation(['trash', 'trash-root']) && this.$checkPermission('master')
            },
            baseLocationMasterMenu() {
                return this.$isThisLocation(['base', 'public']) && this.$checkPermission(['master', 'editor'])
            },
            baseLocationVisitorMenu() {
                return (this.$isThisLocation(['base', 'shared', 'public']) && this.$checkPermission('visitor')) || (this.$isThisLocation(['latest', 'shared']) && this.$checkPermission('master'))
            },
            filterLocationTitle() {
                return {
                    'base': 'Files',
                    'public': 'Files',
                    'shared': 'Shared',
                    'latest': 'Latest',
                    'trash': 'Trash',
                    'trash-root': 'Trash',
                    'participant_uploads': 'Participants',
                }[this.$store.getters.currentFolder.location]
            }
        },
        data() {
            return {
                multiSelectMode: false,
            }
        },
        methods: {
            showLocations() {

            },
            selectAll() {
                this.$store.commit('SELECT_ALL_FILES')
            },
            deselectAll() {
                this.$store.commit('CLEAR_FILEINFO_DETAIL')
            },
            enableMultiSelectMode() {
                this.multiSelectMode = true

                events.$emit('mobileSelecting:start')
            },
            disableMultiSelectMode() {
                this.multiSelectMode = false

                events.$emit('mobileSelecting:stop')
            },
            showViewOptions() {
                events.$emit('mobile-menu:show', 'file-sorting')
            },
            createFolder() {
                events.$emit('popup:open', {name: 'create-folder'})
            },
        },
        mounted() {
            events.$on('mobileSelecting:stop', () => this.multiSelectMode = false)
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .button-enter-active,
    .button-leave-active {
        transition: all 250ms;
    }

    .button-enter {
        opacity: 0;
        transform: translateY(-50%);
    }

    .button-leave-to {
        opacity: 0;
        transform: translateY(50%);
    }

    .button-leave-active {
        position: absolute;
    }

    #mobile-actions-wrapper {
        display: none;
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-actions {
        white-space: nowrap;
        overflow-x: auto;
        margin: 0 -15px;
        padding: 10px 0 10px 15px;
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
