<template>
    <div>
        <div class="mb-4 flex h-2.5 items-center rounded bg-light-300 dark:bg-2x-dark-foreground">
            <div
                v-for="(chart, i) in data"
                :key="i"
                :style="{
                    width: (chart.progress > 1 ? chart.progress : 0) + '%',
                }"
                class="chart-wrapper"
            >
                <!--Only singe line-->
                <span
                    v-if="data.length === 1"
                    :class="[
                        {
                            'rounded-tl-lg rounded-bl-lg border-r-2 border-white dark:border-gray-800':
                                chart.progress < 100,
                            'rounded-lg border-none': chart.progress >= 100,
                        },
                        chart.color,
                    ]"
                    class="chart-progress block h-2.5 w-full"
                >
                </span>

                <!--Multiple line-->
                <span
                    v-if="data.length > 1 && chart.progress > 0"
                    :class="[
                        {
                            'rounded-tl-lg rounded-bl-lg border-r-2 border-white dark:border-gray-800': i === 0,
                            'border-r-2 border-white dark:border-gray-800': i < data.length - 1,
                            'rounded-tr-lg rounded-br-lg': i === data.length - 1,
                        },
                        chart.color,
                    ]"
                    class="chart-progress block h-2.5 w-full"
                ></span>
            </div>
        </div>

        <footer class="flex w-full items-center overflow-x-auto">
            <DotLabel v-for="(chart, i) in data" :key="i" :color="chart.color" :title="chart.title" class="mr-5" />
        </footer>
    </div>
</template>

<script>
import DotLabel from './DotLabel'

export default {
    name: 'ProgressLine',
    props: ['data'],
    components: {
        DotLabel,
    },
}
</script>

<style lang="scss" scoped>
.chart-progress {
    &.success {
        background: #0abb87;
        box-shadow: 0 3px 10px rgba(#0abb87, 0.5);
    }

    &.danger {
        background: #fd397a;
        box-shadow: 0 3px 10px rgba(#fd397a, 0.5);
    }

    &.warning {
        background: #ffb822;
        box-shadow: 0 3px 10px rgba(#ffb822, 0.5);
    }

    &.info {
        background: #5578eb;
        box-shadow: 0 3px 10px rgba(#5578eb, 0.5);
    }

    &.purple {
        background: #9d66fe;
        box-shadow: 0 3px 10px rgba(#9d66fe, 0.5);
    }

    &.secondary {
        background: #e1e1ef;
        box-shadow: 0 3px 10px rgba(#e1e1ef, 0.5);
    }
}

.dark .chart-progress {
    &.secondary {
        background: #282a2f !important;
        box-shadow: 0 3px 10px rgba(#282a2f, 0.5) !important;
    }
}
</style>
