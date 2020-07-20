<template>
    <div class="plans-wrapper" v-if="plans">
        <article class="plan" v-for="(plan, i) in plans" :key="i">
            <div class="plan-wrapper">
                <header class="plan-header">
                    <div class="icon">
                        <hard-drive-icon size="26"></hard-drive-icon>
                    </div>
                    <h1 class="title">{{ plan.data.attributes.name }}</h1>
                    <h2 class="description">{{ plan.data.attributes.description }}</h2>
                </header>
                <section class="plan-features">
                    <b class="storage-size">{{ plan.data.attributes.capacity_formatted }}</b>
                    <span class="storage-description">{{ $t('page_pricing_tables.storage_capacity') }}</span>
                </section>
                <footer class="plan-footer">
                    <b class="price">
                        {{ plan.data.attributes.price }}/{{ $t('global.monthly_ac') }}
                        <small v-if="plan.data.attributes.tax_rates.length > 0" class="vat-disclaimer">{{ $t('page_pricing_tables.vat_excluded') }}</small>
                    </b>
                </footer>
            </div>
        </article>
    </div>
</template>

<script>
    import {HardDriveIcon} from "vue-feather-icons"
    import axios from 'axios'

    export default {
        name: 'PricingTables',
        components: {
            HardDriveIcon,
        },
        data() {
            return {
                plans: undefined,
            }
        },
        created() {
            axios.get('/api/public/pricing')
                .then(response => {
                    this.plans = response.data
                    this.$emit('load', response.data)
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .plans-wrapper {
        box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.05);
        border-radius: 8px;
        background: white;
    }

    .plan {
        text-align: center;
        flex: 0 0 33%;
        padding: 55px 25px 75px;
        //border-right: 1px solid #F7F7F7;

        &:last-child {
            border-right: none;
        }

        .plan-header {

            .icon {

                path, line, polyline, rect, circle {
                    color: $theme;
                }
            }

            .title {
                @include font-size(22);
                font-weight: 800;
                padding-top: 10px;
            }

            .description {
                @include font-size(14);
                font-weight: 600;
            }
        }

        .plan-features {
            margin: 55px 0;

            .storage-size {
                @include font-size(48);
                font-weight: 900;
                line-height: 1.1;
            }

            .storage-description {
                display: block;
                @include font-size(15);
                font-weight: 800;
            }
        }

        .plan-footer {

            .sign-in-button {
                width: 100%;
                text-align: center;
            }

            .price {
                color: $theme;
                @include font-size(18);
                display: block;
                padding-top: 5px;

                .vat-disclaimer {
                    @include font-size(11);
                    color: $text;
                    display: block;
                    font-weight: 300;
                    opacity: 0.45;
                    margin-top: 5px;
                }
            }
        }
    }

    .plans-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    @media only screen and (max-width: 960px) {

        .plans-wrapper {
            display: block;
            margin: 0;

            .plan {
                padding: 30px 25px;
                border-bottom: 1px solid #F7F7F7;
                border-right: none;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .plans-wrapper {
            background: $dark_mode_foreground;
        }

        .plan {
            border-color: $dark_mode_border_color !important;

            .plan-wrapper {
                background: $dark_mode_foreground;
            }

            .plan-header {

                .title {
                    color: $dark_mode_text_primary;
                }

                .description {
                    color: $dark_mode_text_secondary;
                }
            }

            .plan-features {

                .storage-size {
                    color: $dark_mode_text_primary;
                }

                .storage-description {
                    color: $dark_mode_text_primary;
                }
            }

            .plan-footer {

                .sign-in-button {
                    background: rgba($theme, 0.1);

                    /deep/ .content {
                        color: $theme;
                    }
                }

                .price {

                    .vat-disclaimer {
                        color: $dark_mode_text_primary;
                    }
                }
            }
        }
    }
</style>
