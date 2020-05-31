<template>
    <div class="actions">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'PopupActions',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .actions {
        padding: 20px;
        margin: 0 -10px;
        display: flex;

        .popup-button {
            width: 100%;
            margin: 0 10px;
        }
    }

    @media only screen and (max-width: 690px) {
        .actions {
            padding: 15px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
    }
</style>
