<template>
    <img :src="source" alt="oasis cloud">
</template>

<script>
    export default {
        name: 'Clouds',
        props: [
            'type'
        ],
        computed: {
            source() {
                return this.type === 'colored'
                    ? '/oasis/oasis-cloud-colored.svg'
                    : '/oasis/oasis-cloud-white.svg'
            }
        }
    }
</script>

<style lang="scss" scoped>
    img {
        position: absolute;
    }
</style>
