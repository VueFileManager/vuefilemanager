<template>
    <section class="content-sidebar flex-none xl:w-56 w-52 sm:block hidden overflow-y-auto pt-6 select-none" id="content-sidebar">
        <slot></slot>
    </section>
</template>

<script>
    export default {
        name: 'ContentSidebar',
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .content-sidebar {
        background: rgba($light_background, 0.6);
    }

    .dark .content-sidebar {
		background: rgba($dark_mode_foreground, 0.2);
    }
</style>
