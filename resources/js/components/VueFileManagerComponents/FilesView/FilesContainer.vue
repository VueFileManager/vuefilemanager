<template>
    <div class="file-content" :class="{ 'is-offset': uploadingFilesCount }">
        <div
                class="files-container"
                ref="fileContainer"
                :class="{
				'is-fileinfo-visible': fileInfoVisible && !$isMinimalScale()
			}"
                @click.self="filesContainerClick"
        >
            <!--MobileToolbar-->
            <MobileToolbar v-if="$isMinimalScale()"/>

            <!--Searchbar-->
            <SearchBar v-if="$isMinimalScale()" class="mobile-search"/>

            <!--Mobile Actions-->
            <MobileActions v-if="$isMinimalScale()" />

            <!--Item previews list-->
            <div v-if="isList" class="file-list-wrapper">
                <transition-group
                        name="file"
                        tag="section"
                        class="file-list"
                        :class="preview_type"
                >
                    <FileItemList
                            @dragstart="dragStart(item)"
                            @drop="dragFinish(item)"
                            @click.native="clickedFileItem(item.unique_id)"
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
                        :class="preview_type"
                >
                    <FileItemGrid
                            @dragstart="dragStart(item)"
                            @drop="dragFinish(item)"
                            @click.native="clickedFileItem(item.unique_id)"
                            @contextmenu.native.prevent="contextMenu($event, item)"
                            :data="item"
                            v-for="item in data"
                            :key="item.unique_id"
                            class="file-item"
                    />
                </transition-group>
            </div>

            <!--Show empty page if folder is empty-->
            <EmptyPage v-if="!isSearching"/>

            <!--Show empty page if no search results-->
            <EmptyMessage
                    v-if="isSearching && isEmpty"
                    message="Nothing was found."
                    icon="eye-slash"
            />
        </div>

        <div
                v-if="!$isMinimalScale()"
                class="file-info-container"
                :class="{ 'is-fileinfo-visible': fileInfoVisible }"
        >
            <!--File info panel-->
            <FileInfoPanel v-if="fileInfoDetail"/>

            <!--If file info panel empty show message-->
            <EmptyMessage
                    v-if="!fileInfoDetail"
                    message="There is nothing to preview."
                    icon="eye-slash"
            />
        </div>
    </div>
</template>

<script>
    import MobileToolbar from '@/components/VueFileManagerComponents/FilesView/MobileToolbar'
    import MobileActions from '@/components/VueFileManagerComponents/FilesView/MobileActions'
    import FileInfoPanel from '@/components/VueFileManagerComponents/FilesView/FileInfoPanel'
    import FileItemList from '@/components/VueFileManagerComponents/FilesView/FileItemList'
    import FileItemGrid from '@/components/VueFileManagerComponents/FilesView/FileItemGrid'
    import EmptyMessage from '@/components/VueFileManagerComponents/FilesView/EmptyMessage'
    import EmptyPage from '@/components/VueFileManagerComponents/FilesView/EmptyPage'
    import SearchBar from '@/components/VueFileManagerComponents/FilesView/SearchBar'
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
                'preview_type',
                'isSearching',
                'isLoading',
                'data'
            ]),
            isGrid() {
                return this.preview_type === 'grid'
            },
            isList() {
                return this.preview_type === 'list'
            },
            isEmpty() {
                return this.data.length == 0
            },
        },
        data() {
            return {
                draggingId: undefined
            }
        },
        methods: {
            dragStart(data) {

                events.$emit('dragstart', data)

                // Store dragged folder
                this.draggingId = data
            },
            dragFinish(data) {
                // Prevent to drop on file or image
                if (data.type !== 'folder' || this.draggingId === data) return

                // Move folder to new parent
                this.moveTo(this.draggingId, data)
            },
            moveTo(from_item, to_item) {
                this.$store.dispatch('moveItem', [from_item, to_item])
            },
            clickedFileItem(unique_id) {
                events.$emit('fileItem:clicked', unique_id)
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            filesContainerClick(e) {
                if (e.target.className === 'file-list grid') {
                    events.$emit('fileItem:deselect')
                }
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
                this.$store.dispatch('removeItem', this.fileInfoDetail)

                //let ids = []
                //let items = []

                // Get ids
                /*this.$children[0].$children.filter(item => {

                            if (item.isClicked) {

                                ids.push(item.data.unique_id)

                                items.push({
                                    unique_id: item.data.unique_id,
                                    type: item.data.type,
                                })
                            }
                        })*/

                // Dispatch action
                /*this.$store.dispatch('removeItems', [ids, items])*/
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .button-upload {
        display: block;
        text-align: center;
        margin: 20px 0;
    }

    .mobile-search {
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .file-content {
        position: relative;
        display: flex;
        flex-wrap: nowrap;
    }

    .files-container {
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

    .file-leave-active {
        position: absolute;
    }
</style>
