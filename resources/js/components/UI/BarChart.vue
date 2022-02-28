<template>
    <div
        class="grid h-20 grid-flow-col items-end gap-1 sm:h-28 sm:gap-2 lg:flex lg:items-end lg:justify-between lg:gap-0"
    >
        <!--Data bar-->
        <Bar v-for="(item, i) in data" :key="i" :bar="item" />
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
}
</script>
