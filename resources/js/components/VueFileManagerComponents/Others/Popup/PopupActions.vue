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
    @import "@assets/app.scss";

    .actions {
        padding: 20px;
        margin: 0 -10px;
        display: flex;

        .popup-button {
            width: 100%;
            margin: 0 10px;
        }
    }

    .small {
        .actions {
            padding: 15px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
    }

    @media (prefers-color-scheme: dark) {

    }
</style>
