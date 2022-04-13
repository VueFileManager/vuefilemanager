<template>
	<div class="text-center">
		<PlanPeriodSwitcher v-if="plans && yearlyPlans.length > 0" v-model="isSelectedYearlyPlans" class="inline-block" />
		<div class="plans-wrapper" v-if="plans">
			<article class="plan" v-if="plan.data.attributes.interval === intervalPlanType" v-for="plan in plans.data" :key="plan.data.id">
				<div class="plan-wrapper">
					<header class="plan-header mb-8">
						<div class="icon">
							<hard-drive-icon class="text-theme mx-auto" size="26" />
						</div>
						<h1 class="title">{{ plan.data.attributes.name }}</h1>
						<h2 class="description">
							{{ plan.data.attributes.description }}
						</h2>
					</header>
					<div class="justify-center flex py-1.5" v-for="(value, key, i) in plan.data.attributes.features" :key="i">
						<div class="flex items-center">
							<CheckIcon size="18" class="svg-stroke-theme mr-2" />

							<span class="text-sm font-bold" v-if="value !== -1">
								{{ $t( key === 'max_team_members' ? 'max_team_members_total' : key, { value: value }) }}
							</span>

							<span class="text-sm font-bold" v-if="value === -1">
								{{ $t(`${key}.unlimited`) }}
							</span>
						</div>
					</div>
					<footer class="plan-footer mt-8">
						<b class="price text-theme">
							{{ plan.data.attributes.price }} / {{ $t(`interval.${plan.data.attributes.interval}`) }}
						</b>
					</footer>
				</div>
			</article>
		</div>
	</div>
</template>

<script>
import { CheckIcon, HardDriveIcon } from 'vue-feather-icons'
import axios from 'axios'
import PlanPeriodSwitcher from "../../Subscription/PlanPeriodSwitcher";

export default {
    name: 'PricingTables',
    components: {
		PlanPeriodSwitcher,
        HardDriveIcon,
		CheckIcon,
    },
	computed: {
		intervalPlanType() {
			return this.isSelectedYearlyPlans ? 'year' : 'month'
		},
		yearlyPlans() {
			return this.plans.data.filter((plan) => plan.data.attributes.interval === 'year')
		},
	},
    data() {
        return {
            plans: undefined,
			isSelectedYearlyPlans: false,
        }
    },
    created() {
        axios.get('api/subscriptions/plans').then((response) => {
            this.plans = response.data
            this.$emit('load', response.data)
        })
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

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
            path,
            line,
            polyline,
            rect,
            circle {
                color: inherit;
            }
        }

        .title {
            @include font-size(25);
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
            border-bottom: 1px solid #f7f7f7;
            border-right: none;
        }
    }
}

.dark {
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
