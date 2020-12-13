<template>
    <transition name="context-menu">
        <div class="multiselect-actions" v-if="mobileMultiSelect">
            <ToolbarButton class="action-btn" v-if="!$isThisLocation(['trash', 'trash-root']) && $checkPermission('master') || $checkPermission('editor')" source="move" :action="$t('actions.move')" :class="{'is-inactive' : fileInfoDetail.length < 1}" @click.native="moveItem"/>

            <ToolbarButton class="action-btn" v-if="$checkPermission('master') || $checkPermission('editor')" source="trash" :class="{'is-inactive' : fileInfoDetail.length < 1}" :action="$t('actions.delete')" @click.native="deleteItem"/>

            <ToolbarButton class="action-btn" source="download" :class="{'is-inactive' : fileInfoDetail.length < 1}" :action="$t('actions.delete')" @click.native="downloadItem"/>

            <ToolbarButton class="action-btn close-icon" source="close" :action="$t('actions.close')" @click.native="closeSelecting"/>
        </div>
    </transition>
</template>

<script>
import ToolbarButton from '@/components/FilesView/ToolbarButton'
import { events } from '@/bus'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileMultiSelectMenu',
    components: { ToolbarButton },
    computed: {
        ...mapGetters(['fileInfoDetail'])
    },
    data() {
        return {
            mobileMultiSelect: false
        }
    },
    methods: {
        closeSelecting() {
            events.$emit('mobileSelecting:stop')
        },
        downloadItem() {
            this.fileInfoDetail.forEach((item , i) => {
                setTimeout(() => {
                    this.$downloadFile(
                        item.file_url,
                        item.name + '.' + item.mimetype
                    )
                }, i * 100);   
            })
        },
        moveItem() {
            // Open move item popup
            events.$emit('popup:open', { name: 'move', item: [this.fileInfoDetail[0]] })
        },
        deleteItem() {
            //Delete items
            this.$store.dispatch('deleteItem')
        }
    },
    created() {
        events.$on('mobileSelecting:start', () => {
            this.mobileMultiSelect = true

        })

        events.$on('mobileSelecting:stop', () => {
            this.mobileMultiSelect = false

        })
    }

}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.multiselect-actions {
    display: flex;
    padding: 10px 15px;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99;
    overflow: hidden;
    background: white;

    .action-btn {
        margin-right: 15px;

        &:last-child {
            margin-right: 0;
        }
    }

    .close-icon {
        margin-left: auto !important;
    }
}

.is-inactive {
    opacity: 0.25 !important;
    pointer-events: none !important;

    .menu-option {
        display: flex;
        align-items: center;
    }

    .options {

        &.is-active {
            opacity: 1 !important;
            pointer-events: initial !important;
        }
    }
}

    @media (prefers-color-scheme: dark) {

        .multiselect-actions {
            background: $dark_mode_foreground;
        }
    }

    // Transition
    .context-menu-enter-active,
    .fade-enter-active {
        transition: all 200ms;
    }

    .context-menu-leave-active,
    .fade-leave-active {
        transition: all 200ms;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .context-menu-enter,
    .context-menu-leave-to {
        opacity: 0;
        transform: translateY(100%);
    }

    .context-menu-leave-active {
        position: absolute;
    }

</style>
