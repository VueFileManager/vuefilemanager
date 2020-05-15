<template>
    <!--Folder Icon-->
    <div class="folder-item-wrapper">

        <div class="folder-item" :class="{'is-selected': isSelected}" @click="getFolder" :style="indent">
            <chevron-right-icon @click.stop="showTree" size="17" class="icon-arrow" :class="{'is-opened': isVisible, 'is-visible': nodes.folders.length !== 0}"></chevron-right-icon>
            <hard-drive-icon v-if="nodes.location === 'base'" size="17" class="icon"></hard-drive-icon>
            <folder-icon v-if="nodes.location !== 'base'" size="17" class="icon"></folder-icon>
            <span class="label">{{ nodes.name }}</span>
        </div>

        <TreeMenu :depth="depth + 1" v-if="isVisible" :nodes="item" v-for="item in nodes.folders" :key="item.unique_id" />
    </div>
</template>

<script>
    import TreeMenu from '@/components/Others/TreeMenu'
    import {FolderIcon, ChevronRightIcon, HardDriveIcon} from 'vue-feather-icons'
    import {events} from "@/bus"

    export default {
        name: 'TreeMenu',
        props: [
            'nodes', 'depth'
        ],
        components: {
            ChevronRightIcon,
            HardDriveIcon,
            FolderIcon,
            TreeMenu,
        },
        computed: {
            indent() {
                return { paddingLeft: this.depth * 20 + 'px' }
            },
        },
        data() {
            return {
                isVisible: false,
                isSelected: false,
            }
        },
        methods: {
            getFolder() {
                events.$emit('show-folder-item', this.nodes)
                events.$emit('pick-folder', this.nodes)
            },
            showTree() {
                this.isVisible = ! this.isVisible
            }
        },
        created() {

            // Show first location
            if (this.depth == 1) this.isVisible = true

            // Select clicked folder
            events.$on('pick-folder', node => {
                this.isSelected = false

                if (this.nodes.unique_id == node.unique_id) this.isSelected = true
            })

            // Select clicked folder
            events.$on('show-folder-item', node => {
                this.isSelected = false

                if (this.nodes.unique_id == node.unique_id)
                    this.isSelected = true
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .folder-item {
        user-select: none;
        display: block;
        padding: 8px 23px;
        @include transition(150ms);
        cursor: pointer;
        position: relative;
        white-space: nowrap;

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
        }

        &:hover,
        &.is-selected {

            .icon {
                path, line, polyline, rect, circle {
                    stroke: $theme;
                }
            }

            .label {
                color: $theme;
            }
        }
    }

    // Dark mode
    @media (prefers-color-scheme: dark) {

        .folder-item {

            .label {
                color: $dark_mode_text_primary;
            }

            &:hover {
                background: rgba($theme, .1);
            }

            &.is-selected {
                background: rgba($theme, .1);
            }
        }

        &.is-selected {
            background: rgba($theme, .1);
        }
    }

    @media (prefers-color-scheme: dark) and (max-width: 690px) {
        .folder-item {

            &:hover,
            &.is-selected {
                background: rgba($theme, .1);
            }
        }
    }

</style>
