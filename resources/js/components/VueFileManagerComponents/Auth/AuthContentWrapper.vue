<template>
    <div id="auth">
        <slot></slot>
    </div>
</template>

<script>

    export default {
        name: 'AuthContentWrapper',
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    #auth {
        height: 100%;
        width: 100%;
        display: table;
        //display: flex;
        //align-items: center;
        //justify-content: center;
    }
</style>
