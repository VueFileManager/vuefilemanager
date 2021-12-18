<template>
	<div v-if="user" class="card shadow-card">
		<FormLabel>
			{{ $t('user_box_delete.title') }}
		</FormLabel>
		<InfoBox>
			<p>{{ $t('user_box_delete.description') }}</p>
		</InfoBox>
		<ValidationObserver ref="deleteUser" @submit.prevent="deleteUser" v-slot="{ invalid }" tag="form">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="User name" rules="required">
				<AppInputText :title="$t('admin_page_user.label_delete_user', {user: user.data.relationships.settings.data.attributes.name})" :error="errors[0]" :is-last="true">
					<div class="flex space-x-4">
						<input v-model="userName"
							   :placeholder="$t('admin_page_user.placeholder_delete_user')"
							   type="text"
							   class="focus-border-theme input-dark"
							   :class="{'border-red-700': errors[0]}"
						/>
						<ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="danger" class="submit-button">
							{{ $t('admin_page_user.delete_user') }}
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

    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import {required, is} from 'vee-validate/dist/rules'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'UserDelete',
        props: [
            'user'
        ],
        components: {
			AppInputText,
            FormLabel,
            InfoBox,
            PageTabGroup,
            PageTab,
            ValidationProvider,
            ValidationObserver,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isSendingRequest: false,
                isLoading: false,
                userName: '',
            }
        },
        methods: {
            async deleteUser() {

                // Validate fields
                const isValid = await this.$refs.deleteUser.validate();

                if (!isValid) return;

                if (this.userName !== this.user.data.relationships.settings.data.attributes.name) {

                    this.$refs.deleteUser.setErrors({
                        'User name': 'The user name is not the same.'
                    });

                    return
                }

                this.isSendingRequest = true

                axios
                    .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/delete',
                        {
                            name: this.userName,
                            _method: 'delete'
                        }
                    )
                    .then((response) => {

                        if (response.status === 202) {
                            events.$emit('alert:open', {
                                emoji: '☹️',
                                title: this.$t('popup_deleted_user_aborted.title'),
                                message: this.$t('popup_deleted_user_aborted.message'),
                            })
                        }

                        if (response.status === 204) {
                            events.$emit('success:open', {
                                emoji: '👍',
                                title: this.$t('popup_deleted_user.title'),
                                message: this.$t('popup_deleted_user.message'),
                            })

                            this.$router.push({name: 'Users'})
                        }
                    })
                    .catch(() => {

                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
                    .finally(() => {
                        this.isSendingRequest = false
                    })
            }
        },
    }
</script>
