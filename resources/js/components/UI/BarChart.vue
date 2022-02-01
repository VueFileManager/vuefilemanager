<template>
    <div class="grid h-20 grid-flow-col items-end gap-1 sm:h-28 sm:gap-2 lg:flex lg:items-end lg:justify-between lg:gap-0">
        <!--Data bar-->
        <Bar v-for="(item, i) in data" :key="i" :bar="item" />

        <!--Ghost bar-->
        <span
            v-if="ghostLength > 0"
            class="relative block cursor-pointer bg-gray-100 dark:bg-gray-800 lg:mr-2 lg:w-2 2xl:w-3"
            v-for="(ghost, i) in ghostLength"
            :style="{ height: '7%' }"
            :key="i"
        >
        </span>
    </div>
</template>
<script>
import Bar from './Bar'

export default {
    name: 'BarChart',
    props: ['color', 'data'],
    components: {
        Bar,
    },
    computed: {
        ghostLength() {
            return 45 - this.data.length
        },
    },
}
</script>
