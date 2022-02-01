<template>
    <div class="toolbar-button-wrapper">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'ToolbarWrapper',
}
</script>

<style scoped lang="scss">
.toolbar-button-wrapper {
    margin-left: 28px;
    display: flex;
    align-items: center;
}

@media only screen and (max-width: 1024px) {
    .toolbar-button-wrapper {
        margin-left: 25px;
    }
}
</style>
