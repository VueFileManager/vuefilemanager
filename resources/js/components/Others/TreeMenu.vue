<template>
    <div :class="{'opacity-50 pointer-events-none': disabledById && disabledById.data.id === nodes.id || !disableId, 'mb-2.5': isRootDepth}">

        <div
			:class="{'is-disabled-item': false}"
			:style="indent"
			class="relative flex items-center select-none py-2 px-1.5 cursor-pointer relative whitespace-nowrap transition-all duration-150"
		>
			<!--Arrow icon-->
			<span @click.stop="showTree" class="p-2 -m-2">
				<chevron-right-icon
					:class="{'transform rotate-90': isVisible, 'text-theme dark-text-theme': isSelectedItem, 'opacity-100': nodes.folders.length !== 0}"
					class="vue-feather transition-all duration-300 mr-2 opacity-0"
					size="17"
				/>
			</span>

			<!--Item icon-->
            <hard-drive-icon v-if="nodes.location === 'files'" size="17" class="icon vue-feather" :class="{'text-theme dark-text-theme': isSelectedItem}" />
            <users-icon v-if="nodes.location === 'team-folders'" size="17" class="icon vue-feather" :class="{'text-theme dark-text-theme': isSelectedItem}" />
            <user-plus-icon v-if="nodes.location === 'shared-with-me'" size="17" class="icon vue-feather" :class="{'text-theme dark-text-theme': isSelectedItem}" />
            <folder-icon v-if="! nodes.location" size="17" class="icon vue-feather" :class="{'text-theme dark-text-theme': isSelectedItem}" />

			<!--Item label-->
			<b
				@click="getFolder"
				class="text-sm font-bold whitespace-nowrap overflow-x-hidden text-ellipsis inline-block ml-3 transition-all duration-150"
				:class="{'text-theme': isSelectedItem}"
			>
				{{ nodes.name }}
			</b>
        </div>

		<!--Children-->
        <tree-node
			:disabled-by-id="disabledById"
			:depth="depth + 1"
			v-if="isVisible"
			:nodes="item"
			v-for="item in nodes.folders"
			:key="item.id"
		/>
    </div>
</template>

<script>
    //import TreeMenu from './TreeMenu'
	import {FolderIcon, ChevronRightIcon, HardDriveIcon, UsersIcon, UserPlusIcon} from 'vue-feather-icons'
	import {events} from '../../bus'
	import {mapGetters} from 'vuex'

	export default {
		name: 'TreeMenu',
		props: [
			'disabledById',
			'nodes',
			'depth',
		],
		components: {
			ChevronRightIcon,
			HardDriveIcon,
			UserPlusIcon,
			FolderIcon,
			UsersIcon,
			'tree-node': () => import('./TreeMenuNavigator'),
		},
		computed: {
			...mapGetters([
				'clipboard'
			]),
			indent() {
				return {paddingLeft: this.depth * 20 + 'px'}
			},
			disableId() {
				let canBeShow = true

				if (this.clipboard.includes(this.disabledById)) {
					this.clipboard.map(item => {
						if (item.data.id === this.nodes.id) {
							canBeShow = false
						}
					})
				}
				return canBeShow
			},
			isRootDepth() {
				return this.depth === 1
			},
			isSelectedItem() {
				return this.isSelected && this.nodes.isMovable || this.isSelected && !this.isRootDepth
			}
		},
		data() {
			return {
				isVisible: false,
				isSelected: false,
				isInactive: false
			}
		},
		methods: {
			getFolder() {
				if ((this.isRootDepth && this.nodes.isMovable) || !this.isRootDepth) {
					events.$emit('show-folder-item', this.nodes)
					events.$emit('pick-folder', this.nodes)
				}
			},
			showTree() {
				this.isVisible = !this.isVisible
			}
		},
		mounted() {

			// Show first location
			if (this.depth === 1 && this.nodes.isOpen)
				this.isVisible = true

			// Select clicked folder
			events.$on('pick-folder', node => {
				this.isSelected = false

				if (this.nodes.id === node.id)
					this.isSelected = true
			})

			// Select clicked folder
			events.$on('show-folder-item', node => {
				this.isSelected = false

				if (this.nodes.id === node.id)
					this.isSelected = true
			})
		}
	}
</script>
