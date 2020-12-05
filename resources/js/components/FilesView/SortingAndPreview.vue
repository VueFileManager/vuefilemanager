<template>
    <div :style="{ top: positionY + 'px', left: positionX + 'px' }" @click="closeAndResetContextMenu" class="contextmenu" ref="contextmenu" >
        <!-- ContextMenu for File Preview -->
        <div class="menu-options" id="menu-list">
            <ul class="menu-option-group">
                <li class="menu-option">
                    <div class="icon">
                        <corner-down-right-icon size="17"></corner-down-right-icon>
                    </div>
                    <div class="text-label">
                       Grid View
                    </div>
                </li>
                <li class="menu-option"> 
                    <div class="icon">
                        <link-icon size="17"></link-icon>
                    </div>
                    <div class="text-label">
                        List View
                    </div>
                </li>
                
            </ul>
            <ul class="menu-option-group">
                <li class="menu-option" >
                    <div class="icon">
                        <trash-2-icon size="17"></trash-2-icon>
                    </div>
                    <div class="text-label">
                        Sort By Date
                    </div>
                </li>
                <li class="menu-option" >
                    <div class="icon">
                       <img src="/assets/icons/alphabet.svg" size="17">
                    </div>
                    <div class="text-label">
                       Sort By Aplhabet
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import ToolbarButton from "@/components/FilesView/ToolbarButton";
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: 'ContextMenu',
    components: {
        ToolbarButton
    },
    computed: {
        ...mapGetters(['user', 'fileInfoDetail']),
    },
    data() {
        return {
            showFromPreview: false,
            item: undefined,
            isVisible: false,
            positionX: 0,
            positionY: 0
        }
    },

    methods: {
        closeAndResetContextMenu() {
            // Close context menu
            this.isVisible = false

            // Reset item container
            this.item = undefined
        },
        showFolderActionsMenu() {
            let container = document.getElementById('folder-actions')

            this.positionX = container.offsetLeft + 16
            this.positionY = container.offsetTop + 30

            // Show context menu
            this.isVisible = true
        },
    },

}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.filePreviewFixed {
    position: fixed !important;
    display: flex;
}

.menu-option {
    display: flex;
    align-items: center;

    .icon {
        margin-right: 20px;
        line-height: 0;
    }

    .text-label {
        @include font-size(16);
    }
}

.contextmenu {
    min-width: 250px;
    position: absolute;
    z-index: 99;
    box-shadow: $shadow;
    background: white;
    border-radius: 8px;
    overflow: hidden;

    &.showed {
        display: block;
    }
}

.menu-options {
    list-style: none;
    width: 100%;
    margin: 0;
    padding: 0;

    .menu-option-group {
        padding: 5px 0;
        border-bottom: 1px solid $light_mode_border;

        &:first-child {
            padding-top: 0;
        }

        &:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
    }

    .menu-option {
        white-space: nowrap;
        font-weight: 700;
        @include font-size(14);
        padding: 15px 20px;
        cursor: pointer;
        width: 100%;
        color: $text;

        &:hover {
            background: $light_background;

            .text-label {
                color: $theme;
            }

            path,
            line,
            polyline,
            rect,
            circle,
            polygon {
                stroke: $theme;
            }
        }
    }
}

@media (prefers-color-scheme: dark) {
    .contextmenu {
        background: $dark_mode_foreground;

        .menu-options {
            .menu-option-group {
                border-color: $dark_mode_border_color;
            }

            .menu-option {
                color: $dark_mode_text_primary;

                &:hover {
                    background: rgba($theme, 0.1);
                }
            }
        }
    }
}
</style>
