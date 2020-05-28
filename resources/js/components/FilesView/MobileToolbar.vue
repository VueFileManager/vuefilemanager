<template>
    <div class="mobile-toolbar">

        <!-- Go back-->
        <div @click="goBack" class="go-back-button">
            <chevron-left-icon size="17" :class="{'is-visible': browseHistory.length > 1}" class="icon-back"></chevron-left-icon>
        </div>

        <!--Folder Title-->
        <div class="directory-name">{{ directoryName }}</div>

        <!--More Actions-->
        <div class="more-actions-button">
            <div class="tap-area" @click="showMobileNavigation" v-if="$checkPermission('master')">
                <menu-icon size="17"></menu-icon>
            </div>
        </div>
    </div>
</template>

<script>
    import ToolbarButtonUpload from '@/components/FilesView/ToolbarButtonUpload'
    import ToolbarButton from '@/components/FilesView/ToolbarButton'
    import SearchBar from '@/components/FilesView/SearchBar'
    import { MenuIcon, ChevronLeftIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import {last} from 'lodash'

    export default {
        name: 'MobileToolBar',
        components: {
            ToolbarButtonUpload,
            ChevronLeftIcon,
            ToolbarButton,
            SearchBar,
            MenuIcon,
        },
        computed: {
            ...mapGetters([
                'fileInfoVisible',
                'FilePreviewType',
                'fileInfoDetail',
                'currentFolder',
                'browseHistory',
                'homeDirectory',
            ]),
            directoryName() {
                return this.currentFolder ? this.currentFolder.name : this.homeDirectory.name
            }
        },
        methods: {
            showMobileNavigation() {
                events.$emit('show:mobile-navigation')
            },
            goBack() {

                let previousFolder = last(this.browseHistory)

                if (previousFolder.location === 'trash-root') {
                    this.$store.dispatch('getTrash')

                } else if (previousFolder.location === 'shared') {
                    this.$store.dispatch('getShared')

                } else {

                    if ( this.$isThisLocation('public') ) {
                        this.$store.dispatch('browseShared', [{folder: previousFolder, back: true, init: false}])
                    } else {
                        this.$store.dispatch('getFolder', [{folder: previousFolder, back: true, init: false}])
                    }
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
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-toolbar {
        background: white;
        text-align: center;
        display: none;
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
                margin-top: -2px;

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

                path, line, polyline, rect, circle {
                    stroke: $text;
                }
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .mobile-toolbar {
            display: flex;
        }
    }

    @media (prefers-color-scheme: dark) {

        .mobile-toolbar {
            background: $dark_mode_background;

            .directory-name {
                color: $dark_mode_text_primary;
            }

            .more-actions-button .tap-area {

                path, line, polyline, rect, circle {
                    stroke: $dark_mode_text_primary;
                }
            }
        }
    }
</style>
