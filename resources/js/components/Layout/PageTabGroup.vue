<template>
    <div class="page-tab-group">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'PageTabGroup',
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.page-tab-group {
    margin-bottom: 65px;
}
</style>
