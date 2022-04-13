<template>
    <div class="progress-bar">
        <span class="bg-theme" :style="{ width: progress + '%' }"></span>
    </div>
</template>

<script>
export default {
    name: 'ProgressBar',
    props: ['progress'],
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.progress-bar {
    width: 100%;
    height: 5px;
    background: $light_background;
    margin-top: 6px;
    border-radius: 10px;

    span {
        display: block;
        height: 100%;
        border-radius: 10px;
        max-width: 100%;
    }
}

.dark {
    .progress-bar {
        background: $dark_mode_foreground;
    }
}

@media only screen and (min-width: 680px) {
    .dark .progress-bar {
        background: $dark_mode_foreground;
    }
}
</style>
