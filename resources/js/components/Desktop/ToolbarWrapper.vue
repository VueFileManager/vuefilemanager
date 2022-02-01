<template>
    <div class="toolbar-tools">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'ToolbarWrapper',
}
</script>

<style scoped lang="scss">
@import 'resources/sass/vuefilemanager/_variables';
@import 'resources/sass/vuefilemanager/_mixins';

.toolbar-tools {
    text-align: right;
    display: flex;
}
</style>
