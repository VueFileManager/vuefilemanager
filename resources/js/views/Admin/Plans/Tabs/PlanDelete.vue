<template>
	<div class="card shadow-card">
		<FormLabel>
			{{ $t('admin_page_plans.form.title_delete') }}
		</FormLabel>
		<ValidationObserver ref="deletePlan" @submit.prevent="deletePlan" v-slot="{ invalid }" tag="form">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Plan name" :rules="'required|is:' + plan.attributes.name">
				<AppInputText :title="$t('admin_page_user.label_delete_user', {user: plan.attributes.name})" :description="$t('admin_page_plans.disclaimer_delete_plan')" :error="errors[0]" :is-last="true">
					<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
						<input v-model="planName" :placeholder="$t('admin_page_plans.form.name_delete_plac')" type="text" :class="{'border-red-700': errors[0]}" class="focus-border-theme input-dark" />
						<ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="danger" class="sm:w-auto w-full">
							{{ $t('admin_page_plans.delete_plan_button') }}
						</ButtonBase>
					</div>
				</AppInputText>
			</ValidationProvider>
		</ValidationObserver>
	</div>
</template>

<script>
	import AppInputText from "../../../../components/Admin/AppInputText";
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {required, is} from 'vee-validate/dist/rules'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'PlanDelete',
        props: [
            'plan'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
			AppInputText,
            ButtonBase,
            FormLabel,
            required,
            InfoBox,
        },
        data() {
            return {
                isSendingRequest: false,
                isLoading: false,
                planName: '',
            }
        },
        methods: {
            async deletePlan() {
                // Validate fields
                const isValid = await this.$refs.deletePlan.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                axios
                    .post(`/api/subscriptions/admin/plans/${this.$route.params.id}`, {
                            _method: 'delete'
                        }
                    )
                    .then(() => {
						events.$emit('toaster', {
							type: 'success',
							message: this.$t('popup_deleted_plan.title'),
						})

                        this.$router.push({name: 'Plans'})
                    })
                    .catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
                    })
					.finally(() => {
                        this.isSendingRequest = false
					})
            }
        }
    }
</script>
