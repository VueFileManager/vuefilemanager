<template>
    <transition name="vignette">
        <div class="popup" v-if="isVisibleWrapper">
            <transition name="popup">
                <div v-if="isVisiblePopup" class="popup-wrapper">

                    <!--Title-->
                    <div class="popup-header">
                        <h1 class="title">Move Item</h1>
                        <!--<p v-if="message" class="message">{{ message }}</p>-->
                    </div>

                    <!--Content-->
                    <div class="popup-content" v-if="app && pickedItem">
                        <Spinner v-if="isLoadingTree" />
                        <div v-if="! isLoadingTree">
                            <ThumbnailItem class="item-thumbnail" :file="pickedItem" />
                            <TreeMenu :depth="1" :nodes="items" v-for="items in app.folders" :key="items.unique_id" />
                        </div>
                    </div>

                    <!--Actions-->
                    <div class="actions">
                        <ButtonBase
                                class="popup-button"
                                @click.native="closePopup"
                                button-style="secondary"
                        >Cancel
                        </ButtonBase>
                        <ButtonBase
                                class="popup-button"
                                @click.native="moveItem"
                                :button-style="selectedFolder ? 'theme' : 'secondary'"
                        >Move
                        </ButtonBase>
                    </div>
                </div>
            </transition>
            <div class="popup-vignette" @click="closePopup"></div>
        </div>
    </transition>
</template>

<script>
    import ThumbnailItem from '@/components/VueFileManagerComponents/Others/ThumbnailItem'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import Spinner from '@/components/VueFileManagerComponents/FilesView/Spinner'
    import TreeMenu from '@/components/VueFileManagerComponents/Others/TreeMenu'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'PopupMoveItem',
        components: {
            ThumbnailItem,
            ButtonBase,
            TreeMenu,
            Spinner,
        },
        computed: {
            ...mapGetters(['app']),
        },
        data() {
            return {
                isVisibleWrapper: false,
                isVisiblePopup: false,
                selectedFolder: undefined,
                pickedItem: undefined,
                isLoadingTree: true,
            }
        },
        methods: {
            moveItem() {

                if (! this.selectedFolder) return

                // Move item
                this.$store.dispatch('moveItem', [this.pickedItem, this.selectedFolder])

                // Close popup
                this.closePopup()
            },
            closePopup() {

                // Hide popup wrapper
                this.isVisibleWrapper = false
                this.isVisiblePopup = false

                // Clear selected folder
                this.selectedFolder = undefined
            }
        },
        mounted() {

            events.$on('pick-folder', unique_id => {
                this.selectedFolder = unique_id
            })

            // Show popup
            events.$on('popup:move-item', item => {

                this.isLoadingTree = true

                // Get folder tree
                this.$store.dispatch('getFolderTree').then(() => {
                    this.isLoadingTree = false
                }).catch(() => {
                    this.isLoadingTree = false
                })

                // Make popup visible
                this.isVisibleWrapper = true
                this.isVisiblePopup = true

                // Store picked item
                this.pickedItem = item
            })

            // Close popup
            events.$on('popup:close', () => this.closePopup())
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .popup {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
        overflow: auto;
        display: grid;
        padding: 40px;

        .popup-vignette {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background: rgba(17, 20, 29, 0.5);
        }
    }

    .popup-wrapper {
        box-shadow: 0 7px 250px rgba(25, 54, 60, 0.2);
        border-radius: 8px;
        background: white;
        margin: auto;
        width: 480px;
        z-index: 12;
    }

    .popup-header {
        padding: 20px;

        .title {
            @include font-size(20);
            font-weight: 900;
            color: $text;
        }

        .message {
            @include font-size(16);
            color: #8b8f9a;
            margin-top: 5px;
        }
    }

    .popup-content {
        height: 400px;
        overflow-y: auto;
    }

    .item-thumbnail {
        margin-bottom: 20px;
    }

    .actions {
        padding: 20px;
        margin: 0 -10px;
        display: flex;

        .popup-button {
            width: 100%;
            margin: 0 10px;
        }
    }

    // Desktop, tablet
    .medium, .large {

        // Animations
        .popup-enter-active {
            animation: popup-in 0.35s 0.15s ease both;
        }

        .popup-leave-active {
            animation: popup-in 0.15s ease reverse;
        }
    }

    // Mobile styles
    .small {

        .popup-wrapper {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            transform: translateY(0) scale(1);
            box-shadow: none;
            width: 100%;
            border-radius: 0px;

            .popup-content {
                top: 57px;
                bottom: 72px;
                position: absolute;
                left: 0;
                right: 0;
                height: initial;
            }

            .actions {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
            }
        }

        .popup-header {
            padding: 15px;
        }

        .actions {
            padding: 15px;
        }

        // Animations
        .popup-enter-active {
            animation: popup-slide-in 0.35s 0.15s ease both;
        }

        .popup-leave-active {
            animation: popup-slide-in 0.15s ease reverse;
        }
    }

    @keyframes popup-in {
        0% {
            opacity: 0;
            transform: scale(0.7);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes popup-slide-in {
        0% {
            transform: translateY(100%);
        }
        100% {
            transform: translateY(0);
        }
    }

    // Dark mode
    @media (prefers-color-scheme: dark) {

        .popup .popup-vignette {
            background: $dark_mode_vignette;
        }

        .popup-wrapper {
            background: $dark_mode_foreground;
        }

        .popup-header {
            .title {
                color: $dark_mode_text_primary;
            }

            .message {
                color: $dark_mode_text_secondary;
            }
        }
    }

    .vignette-enter-active {
        animation: vignette-in 0.15s ease;
    }

    .vignette-leave-active {
        animation: vignette-in 0.15s 0.15s ease reverse;
    }

    @keyframes vignette-in {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
