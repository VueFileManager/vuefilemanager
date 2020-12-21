<template>
    <ul class="menu-option-group">
        <slot></slot>
    </ul>
</template>

<script>
    export default {
        name: 'OptionGroup'
    }
</script>

<style scoped lang="scss" scoped>
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.menu-option-group {
        padding: 5px 0;
        border-bottom: 1px solid $light_mode_border;

        &:first-child {
            padding-top: 0;
        }

        &:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
    }

@media (prefers-color-scheme: dark) {
    .menu-option-group {
        border-color: $dark_mode_border_color;
    }
}
</style>
