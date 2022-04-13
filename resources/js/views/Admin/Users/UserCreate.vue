<template>
    <ValidationObserver @submit.prevent="createUser" ref="createUser" v-slot="{ invalid }" tag="form">
        <div class="card shadow-card">
            <FormLabel>{{ $t('account_details') }}</FormLabel>

            <!--Avatar-->
            <ValidationProvider tag="div" mode="passive" name="avatar" v-slot="{ errors }">
                <AppInputText :title="$t('avatar')" :error="errors[0]">
                    <ImageInput v-model="user.avatar" :error="errors[0]" />
                </AppInputText>
            </ValidationProvider>

            <!--User Role-->
            <ValidationProvider tag="div" mode="passive" name="permission" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('admin_page_user.select_role')" :error="errors[0]">
                    <SelectInput
                        v-model="user.role"
                        :options="$translateSelectOptions(roles)"
                        :placeholder="$t('admin_page_user.select_role')"
                        :isError="errors[0]"
                    />
                </AppInputText>
            </ValidationProvider>

            <!--Email-->
            <ValidationProvider tag="div" mode="passive" name="email" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('email')" :error="errors[0]">
                    <input
                        v-model="user.email"
                        :placeholder="$t('admin_page_user.create_user.label_email')"
                        type="email"
                        class="focus-border-theme input-dark"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                </AppInputText>
            </ValidationProvider>

            <!--Name-->
            <ValidationProvider tag="div" mode="passive" name="user name" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('full_name')" :error="errors[0]">
                    <input
                        v-model="user.name"
                        :placeholder="$t('admin_page_user.create_user.label_name')"
                        type="text"
                        class="focus-border-theme input-dark"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                </AppInputText>
            </ValidationProvider>

            <!--Password-->
            <div class="flex space-x-4">
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="password"
                    rules="required"
                    v-slot="{ errors }"
                    class="w-full"
                >
                    <AppInputText :title="$t('page_registration.label_pass')" :error="errors[0]" :is-last="true">
                        <input
                            v-model="user.password"
                            :placeholder="$t('new_password')"
                            type="password"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="password confirm"
                    rules="required"
                    v-slot="{ errors }"
                    class="w-full"
                >
                    <AppInputText
                        :title="$t('confirm_password')"
                        :error="errors[0]"
                        :is-last="true"
                    >
                        <input
                            v-model="user.password_confirmation"
                            :placeholder="$t('confirm_password')"
                            type="password"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>
        </div>
        <div class="form-group">
            <ButtonBase
                :disabled="isLoading"
                :loading="isLoading"
                button-style="theme"
                type="submit"
                class="w-full sm:w-auto"
            >
                {{ $t('admin_page_user.create_user.submit') }}
            </ButtonBase>
        </div>
    </ValidationObserver>
</template>

<script>
import AppInputText from '../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SelectInput from '../../../components/Inputs/SelectInput'
import ImageInput from '../../../components/Inputs/ImageInput'
import FormLabel from '../../../components/UI/Labels/FormLabel'
import MobileHeader from '../../../components/Mobile/MobileHeader'
import SectionTitle from '../../../components/UI/Labels/SectionTitle'
import ButtonBase from '../../../components/UI/Buttons/ButtonBase'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'
import axios from 'axios'

export default {
    name: 'Profile',
    components: {
        AppInputText,
        ValidationProvider,
        ValidationObserver,
        SectionTitle,
        MobileHeader,
        SelectInput,
        ButtonBase,
        ImageInput,
        FormLabel,
        required,
    },
    computed: {
        ...mapGetters(['roles']),
    },
    data() {
        return {
            isLoading: false,
            user: {
                role: '',
                avatar: undefined,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                max_storage_amount: 5,
            },
        }
    },
    methods: {
        async createUser() {
            // Validate fields
            const isValid = await this.$refs.createUser.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Create form
            let formData = new FormData()

            // Add image to form
            formData.append('name', this.user.name)
            formData.append('role', this.user.role)
            formData.append('email', this.user.email)
            formData.append('password', this.user.password)
            formData.append('password_confirmation', this.user.password_confirmation)

            // Append avatar if exist
            if (this.user.avatar) formData.append('avatar', this.user.avatar)

            // Send request to get user token
            axios
                .post('/api/admin/users', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    // Show toaster
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.created_user'),
                    })

                    // Go to User page
                    this.$router.push({
                        name: 'UserDetail',
                        params: { id: response.data.data.id },
                    })
                })
                .catch((error) => {
                    // Validation errors
					if (error.response.status === 409) {

						events.$emit('alert:open', {
							title: error.response.data.message,
						})

					} else if (error.response.status === 422) {
                        // Email validation error
                        if (error.response.data.errors['email']) {
                            this.$refs.createUser.setErrors({
                                email: error.response.data.errors['email'],
                            })
                        }

                        // Password validation error
                        if (error.response.data.errors['password']) {
                            this.$refs.createUser.setErrors({
                                password: error.response.data.errors['password'],
                            })
                        }

                        // Password validation error
                        if (error.response.data.errors['max_storage_amount']) {
                            this.$refs.createUser.setErrors({
                                'storage capacity': this.$t('errors.capacity_digit'),
                            })
                        }
                    } else {
                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    }

                    // End loading
                    this.isLoading = false
                })
        },
    },
}
</script>
