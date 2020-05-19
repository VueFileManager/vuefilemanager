<template>
    <transition name="folder">
        <div class="folder-item-wrapper">

            <div class="folder-item" :class="{'is-selected': isSelected}" :style="indent" @click="getFolder">
                <chevron-right-icon @click.stop="showTree" size="17" class="icon-arrow"
                                    :class="{'is-opened': isVisible, 'is-visible': nodes.folders.length !== 0}"></chevron-right-icon>
                <folder-icon size="17" class="icon"></folder-icon>
                <span class="label">{{ nodes.name }}</span>
            </div>

            <TreeMenuNavigator :depth="depth + 1" v-if="isVisible" :nodes="item" v-for="item in nodes.folders"
                               :key="item.unique_id"/>
        </div>
    </transition>
</template>

<script>
    import TreeMenuNavigator from '@/components/Others/TreeMenuNavigator'
    import {FolderIcon, ChevronRightIcon} from 'vue-feather-icons'
    import {events} from "@/bus"

    export default {
        name: 'TreeMenuNavigator',
        props: [
            'nodes', 'depth'
        ],
        components: {
            TreeMenuNavigator,
            ChevronRightIcon,
            FolderIcon,
        },
        computed: {
            indent() {

                let offset = window.innerWidth <= 1024 ? 17 : 22;

                let value = this.depth == 0 ? offset : offset + (this.depth * 20);

                return {paddingLeft: value + 'px'}
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

                events.$emit('show-folder', this.nodes)

                // Get folder content
                this.$store.dispatch('getFolder', [{folder: this.nodes, back: false, init: false}])
            },
            showTree() {
                this.isVisible = !this.isVisible
            }
        },
        created() {

            // Select clicked folder
            events.$on('show-folder', node => {
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
        display: block;
        padding: 8px 0;
        @include transition(150ms);
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        width: 100%;

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
                    stroke: $theme;
                }
            }

            .label {
                color: $theme;
            }
        }
    }

    @media only screen and (max-width: 1024px) {

        .folder-item {
            padding: 8px 0;
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
