<template>
    <div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('Details') }}
            </FormLabel>

            <!--Name-->
            <AppInputText :title="$t('admin_page_plans.form.name')">
                <input
                    @input="$updateInput('/subscriptions/admin/plans/' + $route.params.id, 'name', plan.attributes.name)"
                    v-model="plan.attributes.name"
                    :placeholder="$t('admin_page_plans.form.name_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <!--Description-->
            <AppInputText :title="$t('admin_page_plans.form.description')" :is-last="true">
                <textarea
                    @input="$updateInput('/subscriptions/admin/plans/' + $route.params.id, 'description', plan.attributes.description)"
                    v-model="plan.attributes.description"
                    :placeholder="$t('admin_page_plans.form.description_plac')"
                    class="focus-border-theme input-dark"
                ></textarea>
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('Charged Features') }}
            </FormLabel>

            <!--Bandwidth-->
            <AppInputText
                v-if="plan.attributes.features.bandwidth"
                :title="$t('Bandwidth Price per 1GB')"
                :description="$t('Charge your user by the amount of data he upload or download.')"
                class="w-full"
            >
                <input
                    :value="formatCurrency(plan.attributes.currency, plan.attributes.features.bandwidth.tiers[0].per_unit)"
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <!--Storage-->
            <AppInputText
                v-if="plan.attributes.features.storage"
                :title="$t('Storage Price per 1GB')"
                :description="$t('Charge your user by the amount of data he has stored on the disk per 1GB.')"
                class="w-full"
            >
                <input
                    :value="formatCurrency(plan.attributes.currency, plan.attributes.features.storage.tiers[0].per_unit)"
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <!--Member-->
            <AppInputText
                v-if="plan.attributes.features.member"
                :title="$t('Price per 1 Member')"
                :description="$t('Charge your user by the total members he use in his Team Folders.')"
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
            <AppInputText v-if="plan.attributes.features.flatFee" :title="$t('Flat Fee per Cycle')" :description="$t('Charge monthly flat fee.')" class="w-full">
                <input
                    :value="formatCurrency(plan.attributes.currency, plan.attributes.features.flatFee.tiers[0].per_unit)"
                    type="text"
                    class="focus-border-theme input-dark"
                    disabled
                />
            </AppInputText>

            <InfoBox style="margin-bottom: 0">
                <p>
                    {{ $t('Price change is not possible. If you would like to change your price or currency, please feel free to create a new plan.') }}
                </p>
            </InfoBox>
        </div>
    </div>
</template>

<script>
import SwitchInput from '../../../../components/Others/Forms/SwitchInput'
import SelectInput from '../../../../components/Others/Forms/SelectInput'
import AppInputSwitch from '../../../../components/Admin/AppInputSwitch'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import AppInputText from '../../../../components/Admin/AppInputText'
import InfoBox from '../../../../components/Others/Forms/InfoBox'

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
            })

            return formatter.format(amount)
        },
    },
    created() {
        this.visible = this.plan.attributes.visible
    },
}
</script>
