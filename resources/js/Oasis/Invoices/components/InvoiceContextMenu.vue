<template>
    <div :style="{ top: positionY + 'px', left: positionX + 'px' }" @click="closeAndResetContextMenu" class="contextmenu" v-show="isVisible" ref="contextmenu">

        <!--Invoice-->
        <div v-show="isInvoice" class="menu-options" id="menu-list">
            <OptionGroup class="menu-option-group">
                <Option @click.native="$editInvoice(clipboard[0])" :title="$t('in.menu.edit_invoice')" icon="rename" />
                <Option @click.native="" :title="$t('in.menu.send_invoice')" icon="send" />
                <Option @click.native="$goToCompany(clipboard[0])" :title="$t('in.menu.show_company')" icon="user" />
                <Option @click.native="$deleteInvoice(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="showDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="$downloadInvoice(clipboard[0])" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!--Client-->
        <div v-show="isClient" class="menu-options" id="menu-list">
            <OptionGroup class="menu-option-group">
                <Option @click.native="$goToCompany(clipboard[0])" :title="$t('in.menu.edit')" icon="rename" />
                <Option @click.native="$deleteClient(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="$goToCompany(clipboard[0])" :title="$t('in.menu.show_company')" icon="user" />
                <Option @click.native="$showSidebarPreview(clipboard[0])" :title="$t('context_menu.detail')" icon="detail" />
            </OptionGroup>
        </div>
    </div>
</template>

<script>
import OptionGroup from '@/components/FilesView/OptionGroup'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'ContextMenu',
    components: {
        OptionGroup,
        Option
    },
    computed: {
        ...mapGetters([
			'clipboard',
		]),
		isInvoice() {
			return this.clipboard[0] && this.clipboard[0].type === 'invoice'
		},
		isClient() {
			return this.clipboard[0] && this.clipboard[0].type === 'client'
		},
    },
    data() {
        return {
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
        showContextMenu(event) {
            let parent = document.getElementById('menu-list')
            let nodesSameClass = parent.getElementsByClassName('menu-option')

            let VerticalOffsetArea = nodesSameClass.length * 50
            let HorizontalOffsetArea = 190

            let container = document.getElementById('files-view')

            var offset = container.getClientRects()[0]

            let x = event.clientX - offset.left
            let y = event.clientY - offset.top

            // Set position Y
            if (container.offsetHeight - y < VerticalOffsetArea) {
                this.positionY = y - VerticalOffsetArea
            } else {
                this.positionY = y
            }

            // Set position X
            if (container.offsetWidth - x < HorizontalOffsetArea) {
                this.positionX = x - HorizontalOffsetArea
            } else {
                this.positionX = x
            }

            // Show context menu
            this.isVisible = true
        },
    },
    created() {

        events.$on('contextMenu:show', (event, item) => {
            // Store item
            this.item = item

            // Show context menu
            setTimeout(() => this.showContextMenu(event, item), 10)
        })

        events.$on('unClick', () => this.closeAndResetContextMenu())
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

.no-options {
    /deep/ .text-label {
        color: $text-muted !important;
    }

    /deep/ &:hover {
        background: transparent;
    }

    /deep/ path,
    /deep/ line,
    /deep/ circle {
        stroke: $text-muted !important;
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
}

@media (prefers-color-scheme: dark) {
    .contextmenu {
        background: $dark_mode_foreground;
    }
    .no-options {
        /deep/ .text-label {
            color: $dark_mode_text_secondary !important;
        }

        /deep/ &:hover {
            background: transparent;
        }

        /deep/ path,
        /deep/ line,
        /deep/ circle {
            stroke: $dark_mode_text_secondary !important;
        }
    }
}
</style>
