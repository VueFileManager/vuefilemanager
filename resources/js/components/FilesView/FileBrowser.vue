<template>
    <div class="file-content" id="file-content-id" :class="{ 'is-offset': filesInQueueTotal > 0, 'is-dragging': isDragging }"
         @dragover.prevent
         @drop.stop.prevent="dropUpload($event)"
         @dragover="dragEnter"
         @dragleave="dragLeave"
         @keydown.delete="deleteItems"
         tabindex="-1"
    >
        <div
                class="files-container"
                ref="fileContainer"
                :class="{'is-fileinfo-visible': fileInfoVisible && !$isMinimalScale() , 'mobile-multi-select' : mobileMultiSelect}"
                @click.self="filesContainerClick"
        >
            <!--MobileToolbar-->
            <MobileToolbar />

            <!--Searchbar-->
            <SearchBar class="mobile-search" />

            <!--Mobile Actions-->
            <MobileActions />

            <!--Item previews list-->
            <div v-if="isList" class="file-list-wrapper">
                <transition-group
                        name="file"
                        tag="section"
                        class="file-list"
                        :class="FilePreviewType"
                >
                    <FileItemList
                            @dragstart="dragStart(item)"
                            @drop.stop.native.prevent="dragFinish(item, $event)"
                            @contextmenu.native.prevent="contextMenu($event, item)"
                            :item="item"
                            v-for="item in data"
                            :key="item.unique_id"
                            class="file-item"
                            :class="draggedItems.includes(item) ? 'dragged' : '' "                      
                    />
                </transition-group>
            </div>

            <!--Item previews grid-->
            <div v-if="isGrid" class="file-grid-wrapper">
                <transition-group
                        name="file"
                        tag="section"
                        class="file-list"
                        :class="FilePreviewType"
                >
                    <FileItemGrid
                            @dragstart="dragStart(item)"
                            @drop.native.prevent="dragFinish(item, $event)"
                            @contextmenu.native.prevent="contextMenu($event, item)"
                            :item="item"
                            v-for="item in data"
                            :key="item.unique_id"
                            class="file-item"
                            :class="draggedItems.includes(item) ? 'dragged' : '' "
                    />
                </transition-group>
            </div>

            <!--Show empty page if folder is empty-->
            <EmptyPage v-if="! isSearching"/>

            <!--Show empty page if no search results-->
            <EmptyMessage
                    v-if="isSearching && isEmpty"
                    :message="$t('messages.nothing_was_found')"
                    icon="eye-slash"
            />
        </div>

        <!--File Info Panel-->
        <div v-if="! $isMinimalScale()" class="file-info-container" :class="{ 'is-fileinfo-visible': fileInfoVisible }">
            <!--File info panel-->
            <FileInfoPanel v-if="fileInfoDetail.length === 1"/>

            <MultiSelected  v-if="fileInfoDetail.length > 1"
                            :title="$t('file_detail.selected_multiple')" 
                            :subtitle="this.fileInfoDetail.length + ' ' + $tc('file_detail.items', this.fileInfoDetail.length)"            
            />

            <!--If file info panel empty show message-->
            <EmptyMessage v-if="fileInfoDetail.length === 0" :message="$t('messages.nothing_to_preview')" icon="eye-off"/>
        </div>
    </div>
</template>

<script>
    import MobileToolbar from '@/components/FilesView/MobileToolbar'
    import MobileActions from '@/components/FilesView/MobileActions'
    import MultiSelected from '@/components/FilesView/MultiSelected'
    import FileInfoPanel from '@/components/FilesView/FileInfoPanel'
    import FileItemList from '@/components/FilesView/FileItemList'
    import FileItemGrid from '@/components/FilesView/FileItemGrid'
    import EmptyMessage from '@/components/FilesView/EmptyMessage'
    import EmptyPage from '@/components/FilesView/EmptyPage'
    import SearchBar from '@/components/FilesView/SearchBar'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilesContainer',
        components: {
            MobileToolbar,
            MobileActions,
            MultiSelected,
            FileInfoPanel,
            FileItemList,
            FileItemGrid,
            EmptyMessage,
            SearchBar,
            EmptyPage
        },
        computed: {
            ...mapGetters([
                'filesInQueueTotal',
                'fileInfoVisible',
                'fileInfoDetail',
                'currentFolder',
                'FilePreviewType',
                'isSearching',
                'isLoading',
                'data'
            ]),
            isGrid() {
                return this.FilePreviewType === 'grid'
            },
            isList() {
                return this.FilePreviewType === 'list'
            },
            isEmpty() {
                return this.data.length == 0
            },
            draggedItems() {
                //Set opacity for dragged items

                if(!this.fileInfoDetail.includes(this.draggingId)){
                    return [this.draggingId]
                }

                if(this.fileInfoDetail.includes(this.draggingId)) {
                    return this.fileInfoDetail
                }
            }
        },
        data() {
            return {
                draggingId: undefined,
                isDragging: false,
                mobileMultiSelect: false,
            }
        },
        methods: {
            deleteItems() {
                if(this.fileInfoDetail.length > 0 && this.$checkPermission('master') || this.$checkPermission('editor')) {
                    this.$store.dispatch('deleteItem')
                }
            },
            dropUpload(event) {
                // Upload external file
                this.$uploadExternalFiles(event, this.currentFolder.unique_id)

                this.isDragging = false
            },
            dragEnter() {
                this.isDragging = true
            },
            dragLeave() {
                this.isDragging = false
            },
            dragStart(data) {
                let img = document.createElement('img')
                img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                event.dataTransfer.setDragImage(img, 0, 0)

                events.$emit('dragstart', data)

                // Store dragged folder
                this.draggingId = data
            },
            dragFinish(data, event) {

                if (event.dataTransfer.items.length == 0) {
                    // Prevent to drop on file or image
                    if (data.type !== 'folder' || this.draggingId === data) return

                    //Prevent move selected folder to folder if in beteewn selected folders
                    if(this.fileInfoDetail.find(item => item === data && this.fileInfoDetail.length > 1)) return 

                    // Move folder to new parent

                     //Move item if is not included in selected items
                    if(!this.fileInfoDetail.includes(this.draggingId)){
                        this.$store.dispatch('moveItem', {to_item:data ,noSelectedItem:this.draggingId})
                    }

                    //Move selected items to folder
                    if(this.fileInfoDetail.length > 0 && this.fileInfoDetail.includes(this.draggingId)){
                        this.$store.dispatch('moveItem', {to_item:data ,noSelectedItem: null})
                    }
                   
                   

                } else {

                    // Get unique_id of current folder
                    const unique_id = data.type !== 'folder' ? this.currentFolder.unique_id : data.unique_id

                    // Upload external file
                    this.$uploadExternalFiles(event, unique_id)
                }

                this.isDragging = false
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            filesContainerClick() {
                
                // Deselect itms clicked by outside
                this.$store.commit('CLEAR_FILEINFO_DETAIL')
            }
        },
        created() {
            events.$on('mobileSelecting:start' , () => {
            this.mobileMultiSelect =true
            })

            events.$on('mobileSelecting:stop' , () => {
            this.mobileMultiSelect = false
            })

            events.$on('drop', () => {
                this.isDragging = false
                setTimeout(() => {
                    this.draggingId = undefined
                }, 10);
            })

            events.$on('fileItem:deselect', () =>
                this.$store.commit('CLEAR_FILEINFO_DETAIL')
            )

            events.$on('scrollTop', () => {
                // Scroll top
                var container = document.getElementsByClassName(
                    'files-container'
                )[0]

                if (container) container.scrollTop = 0
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .file-list {
        .dragged {
        /deep/.is-dragenter {
            border: 2px solid transparent;
        }
        }
    }

    .dragged {
        opacity: 0.5;
    }

    #multi-selected {
        position: fixed;
        pointer-events: none;
        z-index: 100;
        
    }

    .mobile-multi-select {
        bottom: 50px !important;
        top: 0px;
    }

    .button-upload {
        display: block;
        text-align: center;
        margin: 20px 0;
    }

    .mobile-search {
        display: none;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .file-content {
        display: flex;

        &.is-dragging {
            @include transform(scale(0.99));
        }
    }

    .files-container {
        overflow-x: hidden;
        overflow-y: auto;
        flex: 0 0 100%;
        @include transition(150ms);
        position: relative;
        scroll-behavior: smooth;

        &.is-fileinfo-visible {
            flex: 0 1 100%;
        }

        .file-list {

            &.grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, 180px);
                justify-content: space-evenly;
            }
        }
    }

    .file-info-container {
        flex: 0 0 300px;
        padding-left: 20px;
        overflow: auto;
    }

    // Transition
    .file-move {
        transition: transform 0.6s;
    }

    .file-enter-active {
        transition: all 300ms ease;
    }

    .file-leave-active {
        transition: all 0ms;
    }

    .file-enter, .file-leave-to /* .list-leave-active below version 2.1.8 */
    {
        opacity: 0;
        transform: translateX(-20px);
    }

    @media only screen and (min-width: 960px) {

        .file-content {
            position: absolute;
            top: 72px;
            left: 15px;
            right: 15px;
            bottom: 0;
            @include transition;
            overflow-y: auto;
            overflow-x: hidden;

            &.is-offset {
                margin-top: 50px;
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .file-info-container {
            display: none;
        }

        .mobile-search {
            display: block;
        }
        .file-content {
            position: absolute;
            top: 0px;
            left: 15px;
            right: 15px;
            bottom: 0;
            @include transition;
            overflow-y: auto;
            overflow-x: hidden;

            &.is-offset {
                margin-top: 50px;
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .files-container {
            padding-left: 15px;
            padding-right: 15px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
            overflow-y: auto;

            .file-list {

                &.grid {
                    grid-template-columns: repeat(auto-fill, 120px);
                }
            }
        }

        .file-content {
            position: absolute;
            top: 0;
            left: 0px;
            right: 0px;
            bottom: 0;
            @include transition;

            &.is-offset {
                margin-top: 50px;
            }
        }

        .mobile-search {
            margin-bottom: 0;
        }
        
        .file-info-container {
            display: none;
        }
    }
</style>
