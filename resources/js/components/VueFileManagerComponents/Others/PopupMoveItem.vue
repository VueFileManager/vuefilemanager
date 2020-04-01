<template>
    <transition name="popup">
        <div class="popup" @click.self="closePopup" v-if="isVisibleWrapper">
            <div class="popup-wrapper">

                <!--Title-->
                <div class="popup-header">
                    <h1 class="title">{{ $t('popup_move_item.title') }}</h1>
                    <!--<p v-if="message" class="message">{{ message }}</p>-->
                </div>

                <!--Content-->
                <div class="popup-content" v-if="app && pickedItem">
                    <Spinner v-if="isLoadingTree"/>
                    <div v-if="! isLoadingTree">
                        <ThumbnailItem class="item-thumbnail" :file="pickedItem"/>
                        <TreeMenu :depth="1" :nodes="items" v-for="items in app.folders" :key="items.unique_id"/>
                    </div>
                </div>

                <!--Actions-->
                <div class="actions">
                    <ButtonBase
                            class="popup-button"
                            @click.native="closePopup"
                            button-style="secondary"
                    >{{ $t('popup_move_item.cancel') }}
                    </ButtonBase>
                    <ButtonBase
                            class="popup-button"
                            @click.native="moveItem"
                            :button-style="selectedFolder ? 'theme' : 'secondary'"
                    >{{ $t('popup_move_item.submit') }}
                    </ButtonBase>
                </div>
            </div>
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
                selectedFolder: undefined,
                pickedItem: undefined,
                isLoadingTree: true,
            }
        },
        methods: {
            moveItem() {

                // Prevent empty submit
                if (! this.selectedFolder) return

                // Move item
                this.$store.dispatch('moveItem', [this.pickedItem, this.selectedFolder])

                // Close popup
                events.$emit('popup:close')
            },
            closePopup() {
                events.$emit('popup:close')
            }
        },
        mounted() {

            // Select folder in tree
            events.$on('pick-folder', folder => {

                if (folder.unique_id == this.pickedItem.unique_id) {
                    this.selectedFolder = undefined
                } else {
                    this.selectedFolder = folder
                }
            })

            // Show popup
            events.$on('popup:move-item', item => {

                // Show tree spinner
                this.isLoadingTree = true

                // Get folder tree and hide spinner
                this.$store.dispatch('getFolderTree').then(() => {
                    this.isLoadingTree = false
                }).catch(() => {
                    this.isLoadingTree = false
                })

                // Make popup visible
                this.isVisibleWrapper = true

                // Store picked item
                this.pickedItem = item
            })

            // Close popup
            events.$on('popup:close', () => {

                // Hide popup wrapper
                this.isVisibleWrapper = false

                // Clear selected folder
                this.selectedFolder = undefined
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .popup {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 20;
        overflow-y: auto;
        display: grid;
        padding: 40px;
    }

    .popup-wrapper {
        box-shadow: $light_mode_popup_shadow;
        border-radius: 8px;
        background: white;
        margin: auto;
        width: 480px;
        z-index: 12;
    }

    .popup-header {
        padding: 20px;

        .title {
            @include font-size(18);
            font-weight: 700;
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

        .popup {
            overflow: hidden;
        }

        .popup-wrapper {
            position: absolute;
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

        .popup-wrapper {
            background: $dark_mode_background;
            box-shadow: $dark_mode_popup_shadow;
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

    @media (prefers-color-scheme: dark) and (max-width: 690px) {
        .popup-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
