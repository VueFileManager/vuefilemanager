<template>
    <div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('details') }}
            </FormLabel>

            <!--Name-->
            <AppInputText :title="$t('name')">
                <input
                    @input="
                        $updateInput('/subscriptions/admin/plans/' + $route.params.id, 'name', plan.attributes.name)
                    "
                    v-model="plan.attributes.name"
                    :placeholder="$t('plan_name')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <!--Description-->
            <AppInputText :title="$t('description_optional')" :is-last="true">
                <textarea
                    @input="
                        $updateInput(
                            '/subscriptions/admin/plans/' + $route.params.id,
                            'description',
                            plan.attributes.description
                        )
                    "
                    v-model="plan.attributes.description"
                    :placeholder="$t('plan_description')"
                    class="focus-border-theme input-dark"
                ></textarea>
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('charged_features') }}
            </FormLabel>

            <!--Bandwidth-->
            <AppInputText
                v-if="plan.attributes.features.bandwidth"
                :title="$t('bandwidth_per_gb')"
                :description="$t('bandwidth_per_gb_note')"
                class="w-full"
            >
                <input
                    :value="
                        formatCurrency(plan.attributes.currency, plan.attributes.features.bandwidth.tiers[0].per_unit)
                    "
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <!--Storage-->
            <AppInputText
                v-if="plan.attributes.features.storage"
                :title="$t('storage_per_gb')"
                :description="$t('storage_per_gb_note')"
                class="w-full"
            >
                <input
                    :value="
                        formatCurrency(plan.attributes.currency, plan.attributes.features.storage.tiers[0].per_unit)
                    "
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <!--Member-->
            <AppInputText
                v-if="plan.attributes.features.member"
                :title="$t('member_per_unit')"
                :description="$t('member_per_unit_note')"
                class="w-full"
            >
                <input
                    :value="formatCurrency(plan.attributes.currency, plan.attributes.features.member.tiers[0].per_unit)"
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <!--Flat Fee-->
            <AppInputText
                v-if="plan.attributes.features.flatFee"
                :title="$t('flat_fee_unit_gb')"
                :description="$t('flat_fee_unit_gb_note')"
                class="w-full"
            >
                <input
                    :value="
                        formatCurrency(plan.attributes.currency, plan.attributes.features.flatFee.tiers[0].per_unit)
                    "
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <InfoBox style="margin-bottom: 0">
                <p>
                    {{
                        $t(
                            'price_change_not_possible_create_new'
                        )
                    }}
                </p>
            </InfoBox>
        </div>
    </div>
</template>

<script>
import SwitchInput from '../../../../components/Inputs/SwitchInput'
import SelectInput from '../../../../components/Inputs/SelectInput'
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import InfoBox from '../../../../components/UI/Others/InfoBox'

export default {
    name: 'PlanMeteredSettings',
    props: ['plan'],
    components: {
        AppInputSwitch,
        AppInputText,
        SwitchInput,
        SelectInput,
        FormLabel,
        InfoBox,
    },
    data() {
        return {
            visible: undefined,
        }
    },
    methods: {
        formatCurrency(currency, amount) {
            // TODO: add user locale
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency,
				maximumFractionDigits: 4,
            })

            return formatter.format(amount)
        },
    },
    created() {
        this.visible = this.plan.attributes.visible
    },
}
</script>
