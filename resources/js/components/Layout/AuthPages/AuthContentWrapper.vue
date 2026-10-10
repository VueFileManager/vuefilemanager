<template>
    <div class="flex px-2.5 md:px-6">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'AuthContentWrapper',
    provide() {
        return {
            authContentWrapper: this,
        }
    },
    created() {
        // Registered <AuthContent> pages (replacement for Vue 2 `$children`)
        this.pages = []
    },
    methods: {
        registerPage(page) {
            this.pages.push(page)
        },
        unregisterPage(page) {
            this.pages = this.pages.filter((item) => item !== page)
        },
    },
}
</script>
