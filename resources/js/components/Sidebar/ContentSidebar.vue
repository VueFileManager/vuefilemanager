<template>
    <section class="content-sidebar">
        <slot></slot>
    </section>
</template>

<script>
    export default {
        name: 'ContentSidebar',
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .content-sidebar {
        background: linear-gradient(0deg, rgba(246, 245, 241, 0.4) 0%, rgba(243, 244, 246, 0.4) 100%);
        user-select: none;
        padding-top: 25px;
        overflow-y: auto;
        flex: 0 0 225px;
    }

    @media only screen and (max-width: 1024px) {
        .content-sidebar {
            flex: 0 0 205px;
        }
    }

    @media only screen and (max-width: 690px) {
        .content-sidebar {
            display: none;
        }
    }

    @media (prefers-color-scheme: dark) {

        .content-sidebar {
            background: rgba($dark_mode_foreground, 0.2);
        }
    }
</style>
