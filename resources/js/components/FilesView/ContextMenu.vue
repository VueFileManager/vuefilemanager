<template>
    <div
		v-show="isVisible"
		:style="{top: positionY + 'px', left: positionX + 'px'}"
		@click="closeAndResetContextMenu"
		class="contextmenu"
		ref="contextmenu"
	>
        <div id="menu-list" class="menu-options">

			<!--Show empty select contextmenu-->
			<slot name="empty-select" v-if="! item"></slot>

			<!--Show single select contextmenu-->
			<slot name="single-select" v-if="isMultiSelectContextMenu"></slot>

			<!--Show multiple select contextmenu-->
			<slot name="multiple-select" v-if="! isMultiSelectContextMenu"></slot>
        </div>
    </div>
</template>

<script>
import {events} from '/resources/js/bus'
import {mapGetters} from 'vuex'

export default {
	name: 'ContextMenu',
	computed: {
		...mapGetters([
			'clipboard',
			'user',
		]),
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

			let container = document.getElementById('file-view')

			let offset = container.getClientRects()[0]

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
		}
	},
	created() {
		events.$on('context-menu:show', (event, item) => {
			// Store item
			this.item = item

			// Show context menu
			setTimeout(() => this.showContextMenu(event, item), 10)
		})

		events.$on('context-menu:current-folder', folder => {
			this.item = folder

			this.isVisible = ! this.isVisible

			if (this.isVisible) {
				let container = document.getElementById('folder-actions')

				this.positionX = container.offsetLeft + 16
				this.positionY = container.offsetTop + 30
			}
		})

		events.$on('unClick', () => this.closeAndResetContextMenu())
	}
}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

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

.dark {
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
