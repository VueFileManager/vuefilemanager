<template>
    <transition name="folder">
        <div class="folder-item-wrapper">
            <div
				@click="goToFolder"
				class="folder-item text-theme dark-text-theme"
				:class="{'is-selected': isSelected, 'is-dragenter': area, 'is-inactive': disabledFolder || disabled && draggedItem.length > 0  }"
				:style="indent"
				@dragover.prevent="dragEnter"
				@dragleave="dragLeave"
				@drop="dragFinish()"
			>
                <chevron-right-icon
					@click.stop.prevent="showTree"
					size="17"
					class="icon-arrow"
					:class="{'is-opened': isVisible, 'is-visible': nodes.folders.length !== 0}"
				/>
                <folder-icon size="17" class="icon text-theme dark-text-theme" />
                <span class="label">{{ nodes.name }}</span>
            </div>
            <TreeMenuNavigator :disabled="disableChildren" :depth="depth + 1" v-if="isVisible" :nodes="item" v-for="item in nodes.folders" :key="item.id" />
        </div>
    </transition>
</template>

<script>
    import TreeMenuNavigator from '/resources/js/components/Others/TreeMenuNavigator'
	import {FolderIcon, ChevronRightIcon} from 'vue-feather-icons'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'

	export default {
		name: 'TreeMenuNavigator',
		props: [
			'disabled',
			'nodes',
			'depth',
		],
		components: {
			TreeMenuNavigator,
			ChevronRightIcon,
			FolderIcon,
		},
		computed: {
			...mapGetters([
				'clipboard'
			]),
			disabledFolder() {
				let disableFolder = false
				if (this.draggedItem.length > 0) {

					this.draggedItem.forEach(item => {
						//Disable the parent of the folder
						if (item.type === "folder" && this.nodes.id === item.parent_id) {
							disableFolder = true
						}
						//Disable the self folder with all children
						if (this.nodes.id === item.id && item.type === 'folder') {
							disableFolder = true
							this.disableChildren = true
						}
						if (this.disabled) {
							this.disableChildren = true
						}
					})
				} else {
					disableFolder = false
					this.disableChildren = false
				}
				return disableFolder
			},
			indent() {

				let offset = window.innerWidth <= 1024 ? 17 : 22;

				let value = this.depth === 0 ? offset : offset + (this.depth * 20);

				return {paddingLeft: value + 'px'}
			},
		},
		data() {
			return {
				disableChildren: false,
				isSelected: false,
				isVisible: false,
				draggedItem: [],
				area: false,
			}
		},
		methods: {
			goToFolder() {
				if (this.$router.currentRoute.name === 'Public') {
					this.$router.push({name: 'Public', params: {id: this.nodes.id}})
				} else {
					this.$router.push({name: 'Files', params: {id: this.nodes.id}})
				}
			},
			dragFinish() {
				// Move no selected item
				if (!this.clipboard.includes(this.draggedItem[0])) {
					this.$store.dispatch('moveItem', {to_item: this.nodes, noSelectedItem: this.draggedItem[0]})
				}

				// Move all selected items
				if (this.clipboard.includes(this.draggedItem[0])) {
					this.$store.dispatch('moveItem', {to_item: this.nodes, noSelectedItem: null})
				}

				this.draggedItem = []
				this.area = false

				events.$emit('drop')
			},
			dragEnter() {
				this.area = true
			},
			dragLeave() {
				this.area = false
			},
			showTree() {
				this.isVisible = !this.isVisible
			}
		},
		created() {

			events.$on('drop', () => {
				this.draggedItem = []
			})

			//Get dragged item
			events.$on('dragstart', (data) => {
				//If is dragged item not selected
				if (!this.clipboard.includes(data)) {
					this.draggedItem = [data]
				}
				//If are the dragged items selected
				if (this.clipboard.includes(data)) {
					this.draggedItem = this.clipboard
				}
			})
		}
	}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';

	.is-inactive {
		opacity: 0.5;
		pointer-events: none;
	}

	.is-dragenter {
		border-radius: 8px;
	}

	.folder-item {
		display: block;
		padding: 8px 0;
		@include transition(150ms);
		cursor: pointer;
		position: relative;
		white-space: nowrap;
		width: 100%;
		border: 2px dashed transparent;

		.icon {
			line-height: 0;
			width: 15px;
			margin-right: 9px;
			vertical-align: middle;
			margin-top: -1px;

			path, line, polyline, rect, circle {
				@include transition(150ms);
			}
		}

		.icon-arrow {
			@include transition(300ms);
			margin-right: 4px;
			vertical-align: middle;
			opacity: 0;

			&.is-visible {
				opacity: 1;
			}

			&.is-opened {
				@include transform(rotate(90deg));
			}
		}

		.label {
			@include transition(150ms);
			@include font-size(13);
			font-weight: 700;
			vertical-align: middle;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			display: inline-block;
			color: $text;
			max-width: 130px;
		}

		&:hover,
		&.is-selected {

			.icon {
				path, line, polyline, rect, circle {
					color: inherit !important;;
				}
			}

			.label {
				color: inherit !important;
			}
		}
	}

	@media only screen and (max-width: 1024px) {

		.folder-item {
			padding: 8px 0;
		}
	}

	// Dark mode
	.dark-mode {

		.folder-item {

			.label {
				color: $dark_mode_text_primary;
			}
		}
	}

</style>
