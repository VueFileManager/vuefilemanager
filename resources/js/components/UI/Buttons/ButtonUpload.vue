<template>
    <label :class="buttonStyle" label="file" class="button file-input button-base">
        <slot />
        <input accept="*" v-show="false" @change="emmitFiles" id="file" type="file" name="files[]" multiple />
    </label>
</template>

<script>
export default {
    name: 'ButtonBase',
    props: ['buttonStyle'],
    data() {
        return {
            files: undefined,
        }
    },
    methods: {
        emmitFiles(e) {
            this.$uploadFiles(e.target.files)
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.button-base {
    @include font-size(15);
    font-weight: 700;
    cursor: pointer;
    transition: 0.15s all ease;
    border-radius: 8px;
    border: 0;
    padding: 10px 28px;
    display: inline-block;

    &:active {
        transform: scale(0.95);
    }

    &.secondary {
        color: $text;
        background: $light_background;
    }
}
</style>
