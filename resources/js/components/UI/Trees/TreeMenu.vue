<template>
    <div
        :class="{
            'pointer-events-none opacity-50': (disabledById && disabledById.data.id === nodes.id) || !disableId || (isRootDepth && !nodes.folders.length && nodes.location !== 'files'),
            'mb-2.5': isRootDepth,
        }"
    >
        <div
            :style="indent"
            class="relative relative flex cursor-pointer select-none items-center whitespace-nowrap px-1.5 transition-all duration-150"
        >
            <!--Arrow icon-->
            <span @click.stop="showTree" class="-m-2 p-2">
                <chevron-right-icon
                    :class="{
                        'rotate-90 transform': isVisible,
                        'text-theme dark-text-theme': isSelectedItem,
                        'opacity-100': nodes.folders.length !== 0,
                    }"
                    class="vue-feather mr-2 opacity-0 transition-all duration-300"
                    size="17"
                />
            </span>

            <!--Item icon-->
            <hard-drive-icon
                v-if="['public', 'files', 'upload-request'].includes(nodes.location)"
                size="17"
                class="icon vue-feather shrink-0"
                :class="{ 'text-theme dark-text-theme': isSelectedItem }"
            />
            <users-icon
                v-if="nodes.location === 'team-folders'"
                size="17"
                class="icon vue-feather shrink-0"
                :class="{ 'text-theme dark-text-theme': isSelectedItem }"
            />
            <user-plus-icon
                v-if="nodes.location === 'shared-with-me'"
                size="17"
                class="icon vue-feather shrink-0"
                :class="{ 'text-theme dark-text-theme': isSelectedItem }"
            />
            <folder-icon
                v-if="!nodes.location"
                size="17"
                class="icon vue-feather shrink-0"
                :class="{ 'text-theme dark-text-theme': isSelectedItem }"
            />

            <!--Item label-->
            <b
                @click="getFolder"
                class="lg:py-2 py-3.5 ml-3 inline-block overflow-x-hidden text-ellipsis whitespace-nowrap text-xs font-bold transition-all duration-150"
                :class="{'text-theme': isSelectedItem }"
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
import { FolderIcon, ChevronRightIcon, HardDriveIcon, UsersIcon, UserPlusIcon } from 'vue-feather-icons'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'TreeMenu',
    props: ['disabledById', 'nodes', 'depth'],
    components: {
        ChevronRightIcon,
        HardDriveIcon,
        UserPlusIcon,
        FolderIcon,
        UsersIcon,
        'tree-node': () => import('./TreeMenu'),
    },
    computed: {
        ...mapGetters(['clipboard']),
        indent() {
            return { paddingLeft: this.depth * 20 + 'px' }
        },
        disableId() {
            let canBeShow = true

            if (this.clipboard.includes(this.disabledById)) {
                this.clipboard.map((item) => {
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
            return (this.isSelected && this.nodes.isMovable) || (this.isSelected && !this.isRootDepth)
        },
    },
    data() {
        return {
            isVisible: false,
            isSelected: false,
            isInactive: false,
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
        },
    },
    mounted() {
        // Show first location
        if (this.depth === 1 && this.nodes.isOpen) this.isVisible = true

        // Select clicked folder
        events.$on('pick-folder', (node) => {
            this.isSelected = false

            if (this.nodes.id === node.id) this.isSelected = true
        })

        // Select clicked folder
        events.$on('show-folder-item', (node) => {
            this.isSelected = false

            if (this.nodes.id === node.id) this.isSelected = true
        })
    },
}
</script>
