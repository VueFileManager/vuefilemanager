<template>
    <div class="content-group">
        <TextLabel>{{ title }}</TextLabel>
        <slot></slot>
    </div>
</template>

<script>
    import TextLabel from '@/components/Others/TextLabel'

    export default {
        name: 'ContentGroup',
        props: ['title'],
        components: {
            TextLabel,
        }
    }
</script>

<style scoped lang="scss">

    .content-group {
        margin-bottom: 30px;
    }
</style>
