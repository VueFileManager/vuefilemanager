<template>
    <div id="card-navigation" style="height: 62px" class="mb-7">
        <div
            :class="{
                'fixed top-0 left-0 right-0 z-10 rounded-none bg-white bg-opacity-50 px-6 backdrop-blur-lg backdrop-filter dark:bg-dark-foreground':
                    fixedNav,
            }"
        >
            <div class="overflow-x-auto whitespace-nowrap">
                <router-link
                    class="border-bottom-theme inline-block border-b-2 border-transparent px-4 py-5 text-sm font-bold"
                    :class="{
                        'text-theme': routeName === page.route,
                        'text-gray-600 dark:text-gray-100': routeName !== page.route,
                    }"
                    v-for="(page, i) in pages"
                    :to="{ name: page.route }"
                    :key="i"
                    replace
                >
                    {{ page.title }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CardNavigation',
    props: ['pages'],
    computed: {
        routeName() {
            return this.$route.name
        },
    },
    data() {
        return {
            fixedNav: false,
        }
    },
    created() {
        // Handle fixed mobile navigation
        window.addEventListener('scroll', () => {
            let card = document.getElementById('card-navigation')

            this.fixedNav = card.getBoundingClientRect().top < 0
        })
    },
}
</script>
