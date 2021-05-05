<template>
    <div :style="{ top: positionY + 'px', left: positionX + 'px' }" @click="closeAndResetContextMenu" class="contextmenu" v-show="isVisible || showFromPreview" ref="contextmenu" :class="{'filePreviewFixed': showFromPreview}">

        <!--Invoice-->
        <div v-show="isInvoice" class="menu-options" id="menu-list">
            <OptionGroup class="menu-option-group">
                <Option @click.native="editItem" title="Edit Invoice" icon="rename" />
                <Option @click.native="" title="Send Invoice" icon="send" />
                <Option @click.native="goToCompany" title="Go to Company" icon="user" />
                <Option @click.native="deleteInvoice" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="showDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!--Client-->
        <div v-show="isClient" class="menu-options" id="menu-list">
            <OptionGroup class="menu-option-group">
                <Option @click.native="goToCompany" title="Edit" icon="rename" />
                <Option @click.native="deleteClient" title="Delete" icon="trash" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="goToCompany" title="Go to Profile" icon="user" />
                <Option @click.native="showDetail" :title="$t('context_menu.detail')" icon="detail" />
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
			'user',
			'clipboard'
		]),
		isInvoice() {
			return this.clipboard[0] && this.clipboard[0].type === 'invoice'
		},
		isClient() {
			return this.clipboard[0] && this.clipboard[0].type === 'client'
		},
        isMultiSelectContextMenu() {

            // If is context Menu open on multi selected items open just options for the multi selected items
            if (this.clipboard.length > 1 && this.clipboard.includes(this.item))
                return false

            // If is context Menu open for the non selected item open options for the single item
            if (this.clipboard.length < 2 || !this.clipboard.includes(this.item))
                return true
        },
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
		goToCompany() {
			this.$router.push({name: 'ClientDetail', params: {id: this.item.client_id ?? this.item.id}})

			events.$emit('file-preview:hide')

			this.isVisible = false
		},
        downloadItem() {
            if (this.clipboard.length > 1)
                this.$store.dispatch('downloadFiles')
            else {
                this.$downloadFile(this.item.file_url, this.item.name + '.' + this.item.mimetype)
            }
        },
		showDetail() {
            // Dispatch load file info detail
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

            // Show panel if is not open
            this.$store.dispatch('fileInfoToggle', true)
        },
		editItem() {
			this.$router.push({name: 'EditInvoice', params: {id: this.item.id}})
		},
        deleteInvoice() {
			events.$emit('confirm:open', {
				title: `Are you sure you want to delete invoice number ${this.item.invoice_number}?`,
				message: 'Your invoice will be permanently deleted.',
				buttonColor: 'danger-solid',
				action: {
					id: this.item.id,
					operation: 'delete-invoice'
				}
			})
        },
		deleteClient() {
			events.$emit('confirm:open', {
				title: `Are you sure you want to delete client ${this.item.name}?`,
				message: 'Your client will be permanently deleted.',
				buttonColor: 'danger-solid',
				action: {
					id: this.item.id,
					operation: 'delete-client'
				}
			})
        },
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
        showFilePreviewMenu() {
            let container = document.getElementById('fast-preview-menu')
            if (container) {
                this.positionX = container.offsetLeft + 16
                this.positionY = container.offsetTop + 51
            }
        }
    },
    created() {
        events.$on('showContextMenuPreview:show', (item) => {
            if (!this.showFromPreview) {
                this.item = item
                this.showFromPreview = true
                this.showFilePreviewMenu()
            } else if (this.showFromPreview) {
                this.showFromPreview = false
                this.item = undefined
            }
        })

        events.$on('showContextMenuPreview:hide', () => {
            this.isVisible = false
            this.showFromPreview = false
            this.item = undefined
        })

        events.$on('contextMenu:show', (event, item) => {
            // Store item
            this.item = item

            // Show context menu
            setTimeout(() => this.showContextMenu(event, item), 10)
        })

        events.$on('unClick', () => this.closeAndResetContextMenu())

        events.$on('folder:actions', (folder) => {
            // Store item
            this.item = folder

            if (this.isVisible) this.isVisible = false
            else this.showFolderActionsMenu()
        })
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

.filePreviewFixed {
    position: fixed !important;
    display: flex;
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
