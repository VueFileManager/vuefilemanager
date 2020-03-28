<template>
    <!--Folder Icon-->
    <div class="folder-item-wrapper">

        <div class="folder-item" :class="{'is-selected': isSelected}" @click="showTree" :style="indent">
            <FontAwesomeIcon class="icon-chevron" :class="{'is-opened': isVisible, 'is-visible': nodes.folders.length !== 0}" icon="chevron-right"/>
            <FontAwesomeIcon class="icon" :icon="directoryIcon"/>
            <span class="label">{{ nodes.name }}</span>
        </div>

        <TreeMenu :depth="depth + 1" v-if="isVisible" :nodes="item" v-for="item in nodes.folders" :key="item.unique_id" />
    </div>
</template>

<script>
    import TreeMenu from '@/components/VueFileManagerComponents/Others/TreeMenu'
    import {events} from "@/bus"

    export default {
        name: 'TreeMenu',
        props: [
            'nodes', 'depth'
        ],
        components: {
            TreeMenu,
        },
        computed: {
            indent() {
                return { paddingLeft: this.depth * 25 + 'px' }
            },
            directoryIcon() {
                
                if (this.nodes.location === 'base') {
                    return 'hdd'
                } else {
                    return 'folder'
                }
            }
        },
        data() {
            return {
                isVisible: false,
                isSelected: false,
            }
        },
        methods: {
            showTree() {
                this.isVisible = ! this.isVisible

                events.$emit('pick-folder', this.nodes)
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
        }
    }
</script>

<style lang="scss" scoped>
    @import "@assets/app.scss";

    .folder-item {
        display: block;
        padding: 10px 20px;
        @include transition(150ms);
        cursor: pointer;
        position: relative;
        white-space: nowrap;

        .icon {
            @include font-size(18);
            margin-right: 9px;
            vertical-align: middle;

            path {
                fill: $text;
            }
        }

        .icon-chevron {
            @include transition(300ms);
            @include font-size(13);
            margin-right: 9px;
            vertical-align: middle;
            opacity: 0;

            &.is-visible {
                opacity: 1;
            }

            &.is-opened {
                @include transform(rotate(90deg));
            }

            path {
                fill: rgba($text, 0.25);
            }
        }

        .label {
            @include font-size(15);
            font-weight: 700;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        &:hover {
            background: $light_background;
        }

        &.is-selected {
            background: rgba($theme, .1);

            .icon {
                path {
                    fill: $theme;
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

            &:hover {
                background: $dark_mode_background;
            }

            .icon-chevron {

                path {
                    fill: $theme;
                }
            }
        }

        &.is-selected {
            background: rgba($theme, .1);
        }
    }

</style>
