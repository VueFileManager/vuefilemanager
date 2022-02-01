<template>
    <div v-if="isActive">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'TabOption',
    props: ['title', 'icon', 'selected'],
    data() {
        return {
            isActive: false,
        }
    },
    mounted() {
        this.isActive = this.selected
    },
}
</script>
