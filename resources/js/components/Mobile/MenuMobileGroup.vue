<template>
    <div class="menu-options">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'MenuMobileGroup',
}
</script>

<style scoped lang="scss">
@import 'resources/sass/vuefilemanager/_variables';
@import 'resources/sass/vuefilemanager/_mixins';

.menu-options {
    margin-top: 10px;
    list-style: none;
    width: 100%;
}
</style>
