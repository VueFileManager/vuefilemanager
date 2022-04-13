<template>
    <div class="action-button">
        <x-icon size="12" class="icon text-theme dark-text-theme" v-if="icon === 'x'" />
        <edit-2-icon size="12" class="icon text-theme dark-text-theme" v-if="icon === 'pencil-alt'" />
        <span class="label">
            <slot />
        </span>
    </div>
</template>

<script>
import { Edit2Icon, XIcon } from 'vue-feather-icons'

export default {
    name: 'ActionButton',
    props: ['icon'],
    components: {
        Edit2Icon,
        XIcon,
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.action-button {
    cursor: pointer;

    .label {
        @include font-size(12);
        font-weight: 600;
    }

    .icon {
        @include font-size(10);
        vertical-align: middle;
        display: inline-block;
        margin-right: 2px;

        path,
        circle,
        line {
            color: inherit;
        }
    }
}

.dark {
}
</style>
