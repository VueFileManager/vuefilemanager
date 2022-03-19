<template>
    <div class="plans-wrapper" v-if="plans">
        <article class="plan" v-for="(plan, i) in plans" :key="i">
            <div class="plan-wrapper">
                <header class="plan-header">
                    <div class="icon">
                        <hard-drive-icon class="text-theme" size="26" />
                    </div>
                    <h1 class="title">{{ plan.data.attributes.name }}</h1>
                    <h2 class="description">
                        {{ plan.data.attributes.description }}
                    </h2>
                </header>
                <section class="plan-features">
                    <b class="storage-size">{{ plan.data.attributes.capacity_formatted }}</b>
                    <span class="storage-description">{{ $t('page_pricing_tables.storage_capacity') }}</span>
                </section>
                <footer class="plan-footer">
                    <b class="price text-theme">
                        {{ plan.data.attributes.price }}/{{ $t('mo.') }}
                        <small v-if="plan.data.attributes.tax_rates.length > 0" class="vat-disclaimer">{{
                            $t('page_pricing_tables.vat_excluded')
                        }}</small>
                    </b>
                    <ButtonBase
                        @click.native="selectPlan(plan)"
                        type="submit"
                        button-style="secondary"
                        class="sign-in-button"
                    >
                        {{ $t('get_it') }}
                    </ButtonBase>
                </footer>
            </div>
        </article>
    </div>
</template>

<script>
import ButtonBase from '../FilesView/ButtonBase'
import { HardDriveIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'PlanPricingTables',
    components: {
        HardDriveIcon,
        ButtonBase,
    },
    props: ['customRoute'],
    data() {
        return {
            plans: undefined,
        }
    },
    computed: {
        ...mapGetters(['user']),
    },
    methods: {
        selectPlan(plan) {
            this.$emit('selected-plan', plan)

            let route = this.customRoute ? this.customRoute : 'UpgradeBilling'

            this.$router.push({ name: route })
        },
    },
    created() {
        axios.get('/api/pricing').then((response) => {
            this.plans = response.data.filter((plan) => {
                return plan.data.attributes.capacity > this.user.data.attributes.max_storage_amount
            })
            this.$emit('load', false)
        })
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.plan {
    text-align: center;
    flex: 0 0 33%;
    padding: 0 25px;
    margin-bottom: 45px;

    .plan-wrapper {
        box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.03);
        padding: 25px;
        border-radius: 8px;
        @include transition;

        &:hover {
            @include transform(translateY(-20px) scale(1.05));
            box-shadow: 0 15px 25px 5px hsla(220, 36%, 16%, 0.08);
        }
    }

    .plan-header {
        .icon {
            path,
            line,
            polyline,
            rect,
            circle {
                color: inherit;
            }
        }

        .title {
            @include font-size(22);
            font-weight: 800;
        }

        .description {
            @include font-size(14);
            font-weight: 600;
        }
    }

    .plan-features {
        margin: 65px 0;

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
            @include font-size(18);
            display: block;
            margin-bottom: 20px;

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
    margin: 0 -25px;
    justify-content: center;
}

@media only screen and (max-width: 960px) {
    .plans-wrapper {
        display: block;
        margin: 0;
    }
}

.dark {
    .plan {
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
