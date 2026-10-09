<template>
    <div v-if="isVisible" class="m-auto w-full max-w-xl py-6 text-center">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'AuthContent',
    props: ['visible', 'name'],
    inject: {
        authContentWrapper: { default: null },
    },
    data() {
        return {
            isVisible: false,
        }
    },
    created() {
        this.isVisible = this.visible

        if (this.authContentWrapper) {
            this.authContentWrapper.registerPage(this)
        }
    },
    beforeUnmount() {
        if (this.authContentWrapper) {
            this.authContentWrapper.unregisterPage(this)
        }
    },
}
</script>
