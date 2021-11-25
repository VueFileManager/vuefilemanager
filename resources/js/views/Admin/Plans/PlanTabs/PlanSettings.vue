<template>
	<div class="card shadow-card">
		<FormLabel>
			{{ $t('admin_page_plans.form.title_details') }}
		</FormLabel>

		<!--Visible-->
		<AppInputSwitch :title="$t('admin_page_plans.form.status')" :description="$t('admin_page_plans.form.status_help')">
			<SwitchInput @input="$updateText('/admin/plans/' + $route.params.id, 'visible', plan.attributes.visible)" v-model="visible" class="switch" :state="plan.attributes.visible"/>
		</AppInputSwitch>

		<!--Name-->
		<AppInputText :title="$t('admin_page_plans.form.name')">
			<input @input="$updateText('/admin/plans/' + $route.params.id, 'name', plan.attributes.name)" v-model="plan.attributes.name" :placeholder="$t('admin_page_plans.form.name_plac')" type="text" class="focus-border-theme input-dark"/>
		</AppInputText>

		<!--Description-->
		<AppInputText :title="$t('admin_page_plans.form.description')">
			<textarea @input="$updateText('/admin/plans/' + $route.params.id, 'description', plan.attributes.description)" v-model="plan.attributes.description" :placeholder="$t('admin_page_plans.form.description_plac')" class="focus-border-theme input-dark"></textarea>
		</AppInputText>

		<!--Storage Capacity-->
		<AppInputText :title="$t('admin_page_plans.form.storage')" :description="$t('admin_page_plans.form.storage_helper')" is-last="">
			<input @input="$updateText('/admin/plans/' + $route.params.id, 'capacity', plan.attributes.capacity)" v-model="plan.attributes.features.max_storage_amount" :placeholder="$t('admin_page_plans.form.storage_plac')" type="number" min="1" max="999999999" class="focus-border-theme input-dark"/>
		</AppInputText>

		<InfoBox style="margin-bottom: 0">
			<p>{{ $t('admin_page_plans.disclaimer_edit_price') }}</p>
		</InfoBox>
	</div>
</template>

<script>
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch"
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import AppInputText from "../../../../components/Admin/AppInputText"
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'

    export default {
        name: 'PlanSettings',
        props: [
            'plan'
        ],
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
				visible: undefined
			}
		},
		created() {
			this.visible = this.plan.attributes.visible
		}
	}
</script>
