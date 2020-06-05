<template>
    <div class="file-content" :class="{ 'is-offset': uploadingFilesCount, 'is-dragging': isDragging }"
         @dragover.prevent
         @drop.stop.prevent="dropUpload($event)"
         @dragover="dragEnter"
         @dragleave="dragLeave"
    >
        <div
                class="files-container"
                ref="fileContainer"
                :class="{'is-fileinfo-visible': fileInfoVisible && !$isMinimalScale() }"
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
                            :data="item"
                            v-for="item in data"
                            :key="item.unique_id"
                            class="file-item"
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
                            :data="item"
                            v-for="item in data"
                            :key="item.unique_id"
                            class="file-item"
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
            <FileInfoPanel v-if="fileInfoDetail"/>

            <!--If file info panel empty show message-->
            <EmptyMessage v-if="!fileInfoDetail" :message="$t('messages.nothing_to_preview')" icon="eye-off"/>
        </div>
    </div>
</template>

<script>
    import MobileToolbar from '@/components/FilesView/MobileToolbar'
    import MobileActions from '@/components/FilesView/MobileActions'
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
            FileInfoPanel,
            FileItemList,
            FileItemGrid,
            EmptyMessage,
            SearchBar,
            EmptyPage
        },
        computed: {
            ...mapGetters([
                'uploadingFilesCount',
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
        },
        data() {
            return {
                draggingId: undefined,
                isDragging: false,
            }
        },
        methods: {
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

                events.$emit('dragstart', data)

                // Store dragged folder
                this.draggingId = data
            },
            dragFinish(data, event) {

                if (event.dataTransfer.items.length == 0) {

                    // Prevent to drop on file or image
                    if (data.type !== 'folder' || this.draggingId === data) return

                    // Move folder to new parent
                    this.$store.dispatch('moveItem', [this.draggingId, data])

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

                // Deselect clicked item
                events.$emit('fileItem:deselect')

                // Hide context menu if is opened
                events.$emit('contextMenu:hide')
            }
        },
        created() {
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

            // On items delete
            events.$on('items:delete', () => {
                this.$store.dispatch('deleteItem', this.fileInfoDetail)
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
