<template>
    <div class="options-wrapper">
        <transition name="context-menu">
            <div
                    v-show="isVisible"
                    ref="contextmenu"
                    class="options"
                    @click="closeAndResetContextMenu"
            >
                <div class="menu-wrapper">
                    <ul class="menu-options">
                        <li class="menu-option"
                            @click="addToFavourites"
                            v-if="! $isTrashLocation() && fileInfoDetail && fileInfoDetail.type === 'folder'"
                        >
                            {{ isInFavourites ? 'Remove Favourite' : 'Add To Favourites' }}
                        </li>

                        <li class="menu-option"
                            @click="$store.dispatch('restoreItem', fileInfoDetail)"
                            v-if="fileInfoDetail && $isTrashLocation()"
                        >
                            Restore
                        </li>
                        <li
                                class="menu-option"
                                @click="renameItem"
                                v-if="fileInfoDetail"
                        >
                            Rename
                        </li>
                        <li
                                class="menu-option"
                                @click="downloadItem"
                                v-if="isFile || isImage"
                        >
                            Download
                        </li>
                        <li
                                class="menu-option delete"
                                @click="removeItem"
                                v-if="fileInfoDetail"
                        >
                            Delete
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
                return this.fileInfoDetail && this.fileInfoDetail.type === 'file'
                    ? true
                    : false
            },
            isImage() {
                return this.fileInfoDetail && this.fileInfoDetail.type === 'image'
                    ? true
                    : false
            },
            isFolder() {
                return this.fileInfoDetail && this.fileInfoDetail.type === 'folder'
                    ? true
                    : false
            }
        },
        data() {
            return {
                isVisible: false
            }
        },
        methods: {
            addToFavourites() {
                if (this.app.favourites && ! this.app.favourites.find(el => el.unique_id == this.fileInfoDetail.unique_id)) {
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
                    'Change your item name',
                    this.fileInfoDetail.name
                )

                if (itemName && itemName !== '') {
                    this.$store.dispatch('changeItemName', {
                        unique_id: this.fileInfoDetail.unique_id,
                        type: this.fileInfoDetail.type,
                        name: itemName
                    })
                }
            },
            closeAndResetContextMenu() {
                events.$emit('fileItem:deselect')

                // Close context menu
                this.isVisible = false
            }
        },
        created() {
            events.$on('mobileMenu:show', () => {
                // Show context menu
                this.isVisible = !this.isVisible
            })

            events.$on('mobileMenu:hide', () => {
                this.isVisible = false
            })

            events.$on('mobileMenu:hide', () => {
                this.isVisible = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .vignette {
        background: rgba(0, 0, 0, 0.25);
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

        .menu-wrapper {
            margin: 15px;
        }

        .menu-options {
            margin-top: 10px;
            box-shadow: $shadow;
            background: white;
            border-radius: 8px;
            list-style: none;
            width: 100%;

            .menu-option {
                font-weight: 600;
                @include font-size(15);
                cursor: pointer;
                width: 100%;
                padding: 20px 10px;
                text-align: center;
                border-bottom: 1px solid $light_background;

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
                background: $dark_mode_foreground;

                .menu-option {
                    border-color: rgba($dark_mode_background, .5);
                    color: $dark_mode_text_primary;
                }
            }
        }
    }

    // Transition
    .context-menu-enter-active,
    .fade-enter-active {
        transition: all 300ms ease;
    }

    .context-menu-leave-active,
    .fade-leave-active {
        transition: all 300ms;
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
