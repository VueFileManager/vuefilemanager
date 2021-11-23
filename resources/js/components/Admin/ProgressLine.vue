<template>
    <div class="progress-wrapper">
        <div class="chart">
            <div v-for="(chart, i) in data" :key="i" :style="{width: chart.progress + '%'}" class="chart-wrapper">
				<!--<DotLabel class="label" :class="{'offset-top': chart.progress < 5}" :color="chart.color" :title="chart.value" />-->
                <span :class="['chart-progress', chart.color]"></span>
            </div>
        </div>
        <footer>
            <DotLabel v-for="(chart, i) in data" :key="i" :color="chart.color" :title="chart.title" />
        </footer>
    </div>
</template>

<script>
    import DotLabel from "./DotLabel";

    export default {
        name: 'ProgressLine',
        props: [
            'data',
        ],
        components: {
            DotLabel
        }
    }
</script>

<style lang="scss" scoped>


    .progress-wrapper {

        .chart {
            display: flex;
            align-items: center;
            margin-bottom: 14px;

            .chart-wrapper {

                .label {
                    justify-content: flex-end;
                    padding-right: 5px;
                    margin-bottom: 10px;

                    &.offset-top {
                        //@include transform(translateY(50px));
                    }
                }

                &:nth-child(1) {
                    .chart-progress {
                        border-bottom-left-radius: 8px;
                        border-top-left-radius: 8px;
                        border-right: 2px solid white;
                    }
                }

				.chart-progress {
					border-right: 2px solid white;
				}

                &:nth-child(6) {
                    .chart-progress {
                        border-bottom-right-radius: 8px;
                        border-top-right-radius: 8px;
                    }
                }
            }

            .chart-progress {
                width: 100%;
                height: 8px;
                display: block;

                &.success {
                    background: #0ABB87;
                    box-shadow: 0 3px 10px rgba(#0ABB87, 0.5);
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
        }

        footer {
            display: flex;
            align-items: center;

            .label {
                margin-right: 22px;
            }
        }
    }
</style>
