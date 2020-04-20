<template>
    <div class="mobile-toolbar" v-if="$isMinimalScale()">

        <!-- Go back-->
        <div @click="goBack" class="go-back-button">
            <FontAwesomeIcon
                    :class="{'is-visible': browseHistory.length > 0}"
                    class="icon-back"
                    icon="chevron-left"
            ></FontAwesomeIcon>
        </div>

        <!--Folder Title-->
        <div class="directory-name">{{ directoryName }}</div>

        <!--More Actions-->
        <div class="more-actions-button" @click="showSidebarMenu">
            <div class="tap-area">
                <FontAwesomeIcon icon="bars" v-if="isSmallAppSize"></FontAwesomeIcon>
            </div>
        </div>
    </div>
</template>

<script>
    import ToolbarButtonUpload from '@/components/VueFileManagerComponents/FilesView/ToolbarButtonUpload'
    import ToolbarButton from '@/components/VueFileManagerComponents/FilesView/ToolbarButton'
    import SearchBar from '@/components/VueFileManagerComponents/FilesView/SearchBar'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'MobileToolBar',
        components: {
            ToolbarButtonUpload,
            ToolbarButton,
            SearchBar
        },
        computed: {
            ...mapGetters([
                'fileInfoVisible',
                'fileInfoDetail',
                'currentFolder',
                'browseHistory',
                'homeDirectory',
                'preview_type',
                'appSize',
            ]),
            directoryName() {
                return this.currentFolder ? this.currentFolder.name : this.homeDirectory.name
            },
            previousFolder() {
                const length = this.browseHistory.length - 2

                return this.browseHistory[length] ? this.browseHistory[length] : this.homeDirectory
            },
            isSmallAppSize() {
                return this.appSize === 'small'
            }
        },
        data() {
            return {
                isSidebarMenu: false,
            }
        },
        methods: {
            showSidebarMenu() {
                this.isSidebarMenu = ! this.isSidebarMenu
                events.$emit('show:sidebar')
            },
            goBack() {

                if (this.previousFolder.location === 'trash-root') {
                    this.$store.dispatch('getTrash')
                    this.$store.commit('FLUSH_BROWSER_HISTORY')

                } else {
                    this.$store.dispatch('goToFolder', [this.previousFolder, true])
                }
            },
        },
        created() {
            // Listen for hide sidebar
            events.$on('show:content', () => {
                if (this.isSidebarMenu) this.isSidebarMenu = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";


    .mobile-toolbar {
        background: white;
        text-align: center;
        display: flex;
        padding: 10px 0;
        position: sticky;
        top: 0;
        z-index: 6;

        > div {
            width: 100%;
            flex-grow: 1;
            align-self: center;
            white-space: nowrap;
        }

        .go-back-button {
            text-align: left;
            flex: 1;

            .icon-back {
                vertical-align: middle;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;

                &.is-visible {
                    opacity: 1;
                    visibility: visible;
                }
            }
        }

        .directory-name {
            line-height: 1;
            text-align: center;
            width: 100%;
            vertical-align: middle;
            @include font-size(16);
            color: $text;
            font-weight: 700;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .more-actions-button {
            flex: 1;
            text-align: right;
            position: relative;

            .tap-area {
                display: inline-block;
                padding: 10px;
                position: absolute;
                right: -10px;
                top: -20px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .mobile-toolbar {
            background: $dark_mode_background;

            .directory-name {
                color: $dark_mode_text_primary;
            }

            .more-actions-button svg path {
                fill: $dark_mode_text_primary;
            }
        }
    }
</style>
