<template>
    <div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('details') }}
            </FormLabel>

            <!--Visible-->
            <AppInputSwitch
                :title="$t('status')"
                :description="$t('admin_page_plans.form.status_help')"
            >
                <SwitchInput
                    @input="
                        $updateInput(
                            '/subscriptions/admin/plans/' + $route.params.id,
                            'visible',
                            plan.attributes.visible
                        )
                    "
                    v-model="plan.attributes.visible"
                    class="switch"
                    :state="plan.attributes.visible"
                />
            </AppInputSwitch>

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
            <AppInputText :title="$t('description_optional')">
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
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('features') }}
            </FormLabel>

            <!--Storage Capacity-->
            <AppInputText
                :title="$t('admin_page_plans.form.storage')"
                :description="$t('admin_page_plans.form.storage_helper')"
            >
                <input
                    @input="
                        $updateInput(
                            `/subscriptions/admin/plans/${$route.params.id}/features`,
                            'max_storage_amount',
                            plan.attributes.features.max_storage_amount
                        )
                    "
                    v-model="plan.attributes.features.max_storage_amount"
                    :placeholder="$t('admin_page_plans.form.storage_plac')"
                    type="number"
                    min="1"
                    max="999999999"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <!--Team Members-->
            <AppInputText :title="$t('max_team_members')" :description="$t('zero_for_unlimited_members')" is-last="true">
                <input
                    @input="
                        $updateInput(
                            `/subscriptions/admin/plans/${$route.params.id}/features`,
                            'max_team_members',
                            plan.attributes.features.max_team_members
                        )
                    "
                    v-model="plan.attributes.features.max_team_members"
                    :placeholder="$t('add_max_team_members')"
                    type="number"
                    min="1"
                    max="999999999"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
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
    name: 'PlanFixedSettings',
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
    created() {
        this.visible = this.plan.attributes.visible
    },
}
</script>
