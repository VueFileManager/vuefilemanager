<template>
    <div class="mb-14">
        <!--Custom content-->
        <slot />

        <!--Default application logo-->
        <div v-if="!$slots.default">
            <!--Image logo-->
            <img
                v-if="config.app_logo"
                class="mx-auto mb-6 h-16 md:h-20 mb-10"
                :src="$getImage(logoSrc)"
                :alt="config.app_name"
            />

            <!--Text logo if image isn't available-->
            <b v-if="!config.app_logo" class="mb-10 block text-xl font-bold">
                {{ config.app_name }}
            </b>
        </div>

        <h1 class="mb-0.5 text-3xl font-extrabold md:text-4xl">
            {{ title }}
        </h1>

        <h2 class="text-xl font-normal md:text-2xl">
            {{ description }}
        </h2>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    name: 'Headline',
    props: ['description', 'title'],
    computed: {
        ...mapGetters(['config', 'isDarkMode']),
		logoSrc() {
			return this.isDarkMode && this.config.app_logo ? this.config.app_logo_dark : this.config.app_logo
		}
    },
}
</script>
