<template>
    <div class="mb-14">
        <!--Custom content-->
        <slot />

        <!--Default application logo-->
        <div v-if="!$slots.default">
            <!--Image logo-->
            <img
                v-if="config.app_logo"
                class="mx-auto mb-6 w-28 md:w-32"
                :src="$getImage(config.app_logo)"
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
        ...mapGetters(['config']),
    },
}
</script>
