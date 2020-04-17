<template>
    <div class="options-wrapper">
        <transition name="context-menu">
            <div
                    v-if="isVisible"
                    ref="contextmenu"
                    class="options"
                    @click="closeAndResetContextMenu"
            >
                <div class="menu-wrapper">

                    <!--Mobile for trash location-->
                    <ul v-if="$isTrashLocation()" class="menu-options">
                        <li class="menu-option" @click="$store.dispatch('restoreItem', fileInfoDetail)" v-if="fileInfoDetail">
                            {{ $t('context_menu.restore') }}
                        </li>
                        <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                            {{ $t('context_menu.download') }}
                        </li>
                        <li class="menu-option delete" @click="removeItem" v-if="fileInfoDetail">
                            {{ $t('context_menu.delete') }}
                        </li>
                    </ul>

                    <!--Mobile for Base location-->
                    <ul v-if="$isBaseLocation()" class="menu-options">
                        <li class="menu-option" @click="addToFavourites" v-if="fileInfoDetail && isFolder">
                            {{ isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites') }}
                        </li>
                        <li class="menu-option" @click="renameItem" v-if="fileInfoDetail">
                            {{ $t('context_menu.rename') }}
                        </li>
                        <li class="menu-option" @click="moveItem" v-if="fileInfoDetail">
                            {{ $t('context_menu.move') }}
                        </li>
                        <li class="menu-option" @click="shareItem" v-if="fileInfoDetail">
                            {{ $t('context_menu.share') }}
                        </li>
                        <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                            {{ $t('context_menu.download') }}
                        </li>
                        <li class="menu-option delete" @click="removeItem" v-if="fileInfoDetail">
                            {{ $t('context_menu.delete') }}
                        </li>
                    </ul>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div
                    v-show="isVisible"
                    class="vignette"
                    @click="closeAndResetContextMenu"
            ></div>
        </transition>
    </div>
</template>

<script>
    import {events} from '@/bus'
    import {mapGetters} from 'vuex'

    export default {
        name: 'MobileOptionList',
        computed: {
            ...mapGetters(['fileInfoDetail', 'app']),
            isInFavourites() {
                return this.app.favourites.find(el => el.unique_id == this.fileInfoDetail.unique_id)
            },
            isFile() {
                return (this.fileInfoDetail && this.fileInfoDetail.type !== 'folder') && (this.fileInfoDetail && this.fileInfoDetail.type !== 'image')
            },
            isImage() {
                return this.fileInfoDetail && this.fileInfoDetail.type === 'image'
            },
            isFolder() {
                return this.fileInfoDetail && this.fileInfoDetail.type === 'folder'
            }
        },
        data() {
            return {
                isVisible: false
            }
        },
        methods: {
            moveItem() {
                // Open move item popup
                events.$emit('popup:open', {name: 'move', item: this.fileInfoDetail})
            },
            shareItem() {
                // Open share item popup
                events.$emit('popup:open', {name: 'share-create', item: this.fileInfoDetail})

            },
            addToFavourites() {
                if (this.app.favourites && !this.app.favourites.find(el => el.unique_id == this.fileInfoDetail.unique_id)) {
                    this.$store.dispatch('addToFavourites', this.fileInfoDetail)
                } else {
                    this.$store.dispatch('removeFromFavourites', this.fileInfoDetail)
                }
            },
            downloadItem() {
                // Download file
                this.$downloadFile(
                    this.fileInfoDetail.file_url,
                    this.fileInfoDetail.name + '.' + this.fileInfoDetail.mimetype
                )
            },
            removeItem() {
                // Dispatch remove item
                this.$store.dispatch('removeItem', this.fileInfoDetail)
            },
            renameItem() {
                let itemName = prompt(
                    this.$t('popup_rename.title'),
                    this.fileInfoDetail.name
                )

                if (itemName && itemName !== '') {

                    let item = {
                        unique_id: this.fileInfoDetail.unique_id,
                        type: this.fileInfoDetail.type,
                        name: itemName
                    }

                    this.$store.dispatch('changeItemName', item)

                    // Change item name if is mobile device or prompted
                    if (this.$isMobile()) {
                        events.$emit('change:name', item)
                    }
                }
            },
            closeAndResetContextMenu() {
                events.$emit('fileItem:deselect')

                // Close context menu
                this.isVisible = false
            }
        },
        created() {

            // Show context menu
            events.$on('mobileMenu:show', () => {
                this.isVisible = !this.isVisible
            })

            // Hide mobile menu
            events.$on('mobileMenu:hide', () => {
                this.isVisible = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .vignette {
        background: rgba(0, 0, 0, 0.15);
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 9;
        cursor: pointer;
        opacity: 1;
    }

    .options {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99;
        overflow: hidden;

        &.showed {
            display: block;
        }

        .menu-options {
            margin-top: 10px;
            box-shadow: $shadow;
            background: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            list-style: none;
            width: 100%;

            .menu-option {
                font-weight: 700;
                letter-spacing: 0.15px;
                @include font-size(15);
                cursor: pointer;
                width: 100%;
                padding: 20px 10px;
                text-align: center;
                border-bottom: 1px solid $light_mode_border;

                &:last-child {
                    border: none;
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .vignette {
            background: $dark_mode_vignette;
        }

        .options {

            .menu-options {
                background: $dark_mode_background;

                .menu-option {
                    border-color: $dark_mode_border_color;
                    color: $dark_mode_text_primary;
                }
            }
        }
    }

    // Transition
    .context-menu-enter-active,
    .fade-enter-active {
        transition: all 200ms;
    }

    .context-menu-leave-active,
    .fade-leave-active {
        transition: all 200ms;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .context-menu-enter,
    .context-menu-leave-to {
        opacity: 0;
        transform: translateY(100%);
    }

    .context-menu-leave-active {
        position: absolute;
    }
</style>
