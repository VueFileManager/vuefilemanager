<template>
    <div>
        <div class="select-box" :class="[isClicked ? 'select-box-active bg-theme' : 'select-box-deactive'] ">
            <CheckIcon v-if="isClicked" class="icon" size="17" />
        </div>
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
@import '@assets/vuefilemanager/_variables';

.select-box {
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;

    .icon {
        stroke: white;
    }
}

.select-box-deactive {
    background-color: darken($light_background, 5%);
}

@media (prefers-color-scheme: dark) {
    
    .select-box-deactive {
        background-color: lighten($dark_mode_foreground, 10%);
    }
}

</style>
