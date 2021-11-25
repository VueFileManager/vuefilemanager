<template>
	<div class="card shadow-card">
		<FormLabel>
			{{ $t('admin_page_plans.form.title_delete') }}
		</FormLabel>
		<InfoBox>
			<p>{{ $t('admin_page_plans.disclaimer_delete_plan') }}</p>
		</InfoBox>
		<ValidationObserver ref="deletePlan" @submit.prevent="deletePlan" v-slot="{ invalid }" tag="form">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Plan name" :rules="'required|is:' + plan.attributes.name">
				<AppInputText :title="$t('admin_page_user.label_delete_user', {user: plan.attributes.name})" :is-last="true">
					<div class="flex space-x-4">
						<input v-model="planName" :placeholder="$t('admin_page_plans.form.name_delete_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
						<ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="danger" class="submit-button">
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
                    .post('/api/subscription/plans/' + this.$route.params.id,
                        {
                            data: {
                                name: this.planName
                            },
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
							type: 'success',
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
