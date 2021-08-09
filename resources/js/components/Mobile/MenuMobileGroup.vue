<template>
    <div class="menu-options">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'MenuMobileGroup',
    }
</script>

<style scoped lang="scss">
    @import "@assets/vuefilemanager/_variables";
    @import "@assets/vuefilemanager/_mixins";

    .menu-options {
        margin-top: 10px;
        list-style: none;
        width: 100%;
    }
</style>
