<template>
    <ul class="list-info">
        <slot />
    </ul>
</template>

<script>
export default {
    name: 'ListInfo',
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';
</style>
