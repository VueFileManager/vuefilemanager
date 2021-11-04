<template>
    <div class="select-box" :class="[isClicked ? 'bg-theme' : 'is-deactive'] ">
        <CheckIcon v-if="isClicked" class="icon" size="17" />
    </div>
</template>

<script>
import { CheckIcon } from 'vue-feather-icons'

export default {
    name: 'CheckBox',
    props: [ 'isClicked' ],
    components: { CheckIcon }
}
</script>

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';

.select-box {
    min-width: 20px;
    min-height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;

    .icon {
        stroke: white;
    }
}

.is-deactive {
    background-color: darken($light_background, 5%);
}

.dark {
    
    .is-deactive {
        background-color: lighten($dark_mode_foreground, 10%);
    }
}

</style>
