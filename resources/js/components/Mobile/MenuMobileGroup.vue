<template>
    <div class="menu-options">
        <slot></slot>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'MenuMobileGroup',
        components: {

        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {

            }
        }
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
