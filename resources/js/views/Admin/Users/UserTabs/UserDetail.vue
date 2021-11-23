<template>
    <PageTab>
        <!--Change role-->
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('user_box_role.title') }}
            </FormLabel>
            <InfoBox>
                <p>{{ $t('user_box_role.description') }}</p>
            </InfoBox>
            <ValidationObserver ref="changeRole" @submit.prevent="changeRole" v-slot="{ invalid }" tag="form">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Role" rules="required">
					<AppInputText :title="$t('admin_page_user.select_role')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<SelectInput v-model="userRole" :options="$translateSelectOptions(roles)" :placeholder="$t('admin_page_user.select_role')" :isError="errors[0]" />
							<ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
								{{ $t('admin_page_user.save_role') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </div>
        <div class="card shadow-card">
            <FormLabel>{{ $t('admin_page_user.label_person_info') }}</FormLabel>
			<!--Email-->
            <AppInputText :title="$t('page_registration.label_email')">
                <input :value="user.data.attributes.email"
					   :placeholder="$t('page_registration.placeholder_email')"
					   type="email"
					   class="focus-border-theme input-dark"
					   disabled
				/>
            </AppInputText>
			<!--Name-->
            <AppInputText :title="$t('page_registration.label_name')" :is-last="true">
                <input :value="user.data.relationships.settings.data.attributes.name"
					   :placeholder="$t('page_registration.placeholder_name')"
					   type="text"
					   class="focus-border-theme input-dark"
					   disabled
				/>
            </AppInputText>
        </div>
        <div class="card shadow-card">
            <FormLabel>{{ $t('user_settings.title_billing') }}</FormLabel>
            <AppInputText :title="$t('user_settings.name')">
                <input :value="user.data.relationships.settings.data.attributes.name"
					   type="text"
					   class="focus-border-theme input-dark"
					   disabled
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.address')">
                <input :value="user.data.relationships.settings.data.attributes.address"
					   type="text"
					   disabled
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.country')">
                <input :value="user.data.relationships.settings.data.attributes.country"
					   type="text"
					   disabled
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <div class="flex space-x-4">
                <AppInputText :title="$t('user_settings.city')" class="w-full">
                    <input :value="user.data.relationships.settings.data.attributes.city"
						   type="text"
						   disabled
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
                <AppInputText :title="$t('user_settings.postal_code')" class="w-full">
                    <input :value="user.data.relationships.settings.data.attributes.postal_code"
						   type="text"
						   disabled
						   class="focus-border-theme input-dark"
					/>
                </AppInputText>
            </div>
            <AppInputText :title="$t('user_settings.state')">
                <input :value="user.data.relationships.settings.data.attributes.state"
					   type="text"
					   disabled
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
            <AppInputText :title="$t('user_settings.phone_number')" :is-last="true">
                <input :value="user.data.relationships.settings.data.attributes.phone_number"
					   type="text"
					   disabled
					   class="focus-border-theme input-dark"
				/>
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
	import AppInputText from "../../../../components/Admin/AppInputText";
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'UserDetail',
        props: [
            'user'
        ],
        components: {
			AppInputText,
            PageTabGroup,
            PageTab,
            InfoBox,
            FormLabel,
            ValidationProvider,
            ValidationObserver,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        computed: {
            ...mapGetters(['roles', 'config']),
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
                userRole: undefined,
            }
        },
        methods: {
            async changeRole() {

                // Validate fields
                const isValid = await this.$refs.changeRole.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/role', {
                        attributes: {
                            role: this.userRole,
                        },
                        _method: 'patch'
                    })
                    .then(() => {

                        // Reset errors
                        this.$refs.changeRole.reset()

                        this.$emit('reload-user')

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.changed_user'),
                        })
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
