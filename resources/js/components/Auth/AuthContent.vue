<template>
    <div class="auth-form" v-if="isVisible">
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
