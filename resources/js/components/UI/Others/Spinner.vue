<template>
    <div id="loading-bar-spinner" class="spinner">
        <div class="spinner-icon border-top-theme border-left-theme"></div>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

#loading-bar-spinner.spinner {
    left: 50%;
    margin-left: -20px;
    top: 50%;
    margin-top: -20px;
    position: absolute;
    z-index: 19 !important;
    animation: loading-bar-spinner 400ms linear infinite;
}

#loading-bar-spinner.spinner .spinner-icon {
    width: 40px;
    height: 40px;
    border: solid 4px transparent;
    //border-top-color: $theme !important;
    //border-left-color: $theme !important;
    border-radius: 50%;
}

@keyframes loading-bar-spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
