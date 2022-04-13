<template>
    <div>
        <div
            @click="goToFolder"
            class="flex cursor-pointer items-center rounded-lg border-2 border-dashed border-transparent py-2.5"
            :class="{
                'border-theme': area,
                'pointer-events-none opacity-50': disabledFolder || (disabled && draggedItem.length > 0),
            }"
            :style="indent"
            @dragover.prevent="dragEnter"
            @dragleave="dragLeave"
            @drop="dragFinish()"
        >
            <div @click.stop.prevent="showTree" class="-my-2 -ml-2 cursor-pointer p-2">
                <chevron-right-icon
                    size="17"
                    class="vue-feather"
                    :class="{
                        'rotate-90 transform': isVisible,
                        'opacity-0': nodes.folders.length === 0,
                    }"
                />
            </div>
            <folder-icon size="17" class="vue-feather mr-2.5 shrink-0" :class="{ 'text-theme': isSelected }" />
            <b
                class="max-w-1 overflow-hidden text-ellipsis whitespace-nowrap text-xs font-bold"
                :class="{ 'text-theme': isSelected }"
            >
                {{ nodes.name }}
            </b>
        </div>
        <tree-node
            :disabled="disableChildren"
            :depth="depth + 1"
            v-if="isVisible"
            :nodes="item"
            v-for="item in nodes.folders"
            :key="item.id"
        />
    </div>
</template>

<script>
import { FolderIcon, ChevronRightIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'

export default {
    name: 'TreeMenuNavigator',
    props: ['disabled', 'nodes', 'depth'],
    components: {
        'tree-node': () => import('./TreeMenuNavigator'),
        ChevronRightIcon,
        FolderIcon,
    },
    computed: {
        ...mapGetters(['clipboard']),
        isSelected() {
            return this.$route.params.id === this.nodes.id
        },
        disabledFolder() {
            let disableFolder = false

            if (this.draggedItem.length > 0) {
                this.draggedItem.forEach((item) => {
                    //Disable the parent of the folder
                    if (item.type === 'folder' && this.nodes.id === item.parent_id) {
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
            let offset = window.innerWidth <= 1024 ? 14 : 18

            return {
                paddingLeft: this.depth === 0 ? 0 : offset * this.depth + 'px',
            }
        },
    },
    data() {
        return {
            disableChildren: false,
            isVisible: false,
            draggedItem: [],
            area: false,
        }
    },
    methods: {
        goToFolder() {
            this.$goToFileView(this.nodes.id)
        },
        dragFinish() {
            // Move no selected item
            if (!this.clipboard.includes(this.draggedItem[0])) {
                this.$store.dispatch('moveItem', {
                    to_item: this.nodes,
					item: this.draggedItem[0],
                })
            }

            // Move all selected items
            if (this.clipboard.includes(this.draggedItem[0])) {
                this.$store.dispatch('moveItem', {
                    to_item: this.nodes,
					item: null,
                })
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
        },
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
    },
}
</script>
