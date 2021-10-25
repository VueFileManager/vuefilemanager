<template>
    <div v-if="isVisible" class="auth-form">
        <slot></slot>
    </div>
</template>

<script>

    export default {
        name: 'AuthContent',
        props: ['visible', 'name'],
        data() {
            return {
                isVisible: false,
            }
        },
        created() {
            this.isVisible = this.visible
        }
    }
</script>
