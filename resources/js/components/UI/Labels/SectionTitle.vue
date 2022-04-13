<template>
    <b class="text-label">
        <slot />
    </b>
</template>

<script>
export default {
    name: 'SectionTitle',
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.text-label {
    @include font-size(12);
    color: #afafaf;
    font-weight: 700;
    display: block;
    margin-bottom: 20px;
}

@media only screen and (max-width: 1024px) {
}

.dark {
    .text-label {
        color: $theme;
    }
}
</style>
