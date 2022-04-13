<template>
    <transition name="context-menu">
        <div v-if="isMultiSelectMode" class="multiselect-actions">
            <slot v-if="$slots.default" />
            <slot v-if="$slots.editor && $checkPermission('editor')" name="editor" />
            <slot v-if="$slots.visitor && $checkPermission('visitor')" name="visitor" />

            <ToolbarButton
                @click.native="closeSelecting"
                class="action-btn close-icon"
                source="close"
                :action="$t('close')"
            />
        </div>
    </transition>
</template>

<script>
import ToolbarButton from '../../UI/Buttons/ToolbarButton'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileMultiSelectToolbar',
    components: {
        ToolbarButton,
    },
    computed: {
        ...mapGetters(['isMultiSelectMode', 'clipboard']),
    },
    methods: {
        closeSelecting() {
            this.$store.commit('TOGGLE_MULTISELECT_MODE')
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.multiselect-actions {
    display: flex;
    padding: 10px 15px;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9;
    overflow: hidden;
    background: white;

    .action-btn {
        margin-right: 25px;

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

.dark {
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
