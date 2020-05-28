<template>
    <div id="single-page">
        <div id="page-content" class="small-width">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <ValidationObserver @submit.prevent="createUser" ref="createUser" v-slot="{ invalid }" tag="form" class="form block-form">

                    <div class="form-group">
                        <b class="form-group-label">
                            {{ $t('admin_page_user.create_user.group_details') }}
                        </b>

                        <!--Avatar-->
                        <div class="block-wrapper">
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="avatar" v-slot="{ errors }">
                                <ImageInput v-model="user.avatar" :error="errors[0]" />
                            </ValidationProvider>
                        </div>

                        <!--Email-->
                        <div class="block-wrapper">
                            <label>{{ $t('page_registration.label_email') }}</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" rules="required" v-slot="{ errors }">
                                <input v-model="user.email" :placeholder="$t('admin_page_user.create_user.label_email')" type="email" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Name-->
                        <div class="block-wrapper">
                            <label>{{ $t('page_registration.label_name') }}</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="user name" rules="required" v-slot="{ errors }">
                                <input v-model="user.name" :placeholder="$t('admin_page_user.create_user.label_name')" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Password-->
                        <div class="wrapper-inline">
                            <div class="block-wrapper">
                                <label>{{ $t('page_registration.label_pass') }}</label>
                                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="password" rules="required" v-slot="{ errors }">
                                    <input v-model="user.password" :placeholder="$t('page_registration.placeholder_pass')" type="password" :class="{'is-error': errors[0]}"/>
                                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>

                            <div class="block-wrapper">
                                <label>{{ $t('page_registration.label_confirm_pass') }}</label>
                                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="password confirm" rules="required" v-slot="{ errors }">
                                    <input v-model="user.password_confirmation" :placeholder="$t('admin_page_user.create_user.label_conf_pass')" type="password" :class="{'is-error': errors[0]}"/>
                                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <b class="form-group-label">
                            {{ $t('admin_page_user.create_user.group_settings') }}
                        </b>


                        <!--User Role-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_user.select_role') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="permission" rules="required" v-slot="{ errors }">
                                <SelectInput v-model="user.role" :options="roles" :placeholder="$t('admin_page_user.select_role')" :isError="errors[0]"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Storage Capacity-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_user.label_change_capacity') }}</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="storage capacity" rules="required" v-slot="{ errors }">
                                <input v-model="user.storage_capacity" min="1" max="999999999" :placeholder="$t('admin_page_user.label_change_capacity')" type="number" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>

                    <div class="form-group">
                        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
                            {{ $t('admin_page_user.create_user.submit') }}
                        </ButtonBase>
                    </div>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            ValidationProvider,
            ValidationObserver,
            SectionTitle,
            MobileHeader,
            SelectInput,
            ButtonBase,
            ImageInput,
            PageHeader,
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
                    storage_capacity: 5,
                },
            }
        },
        methods: {
            async createUser() {

                // Validate fields
                const isValid = await this.$refs.createUser.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Create form
                let formData = new FormData()

                // Add image to form
                formData.append('name', this.user.name)
                formData.append('role', this.user.role)
                formData.append('email', this.user.email)
                formData.append('password', this.user.password)
                formData.append('storage_capacity', this.user.storage_capacity)
                formData.append('password_confirmation', this.user.password_confirmation)

                // Append avatar if exist
                if (this.user.avatar)
                    formData.append('avatar', this.user.avatar)

                // Send request to get user token
                axios
                    .post('/api/users/create', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.created_user'),
                        })

                        // Go to User page
                        this.$router.push({name: 'UserDetail', params: {id: response.data.data.id}})
                    })
                    .catch(error => {

                        // Validation errors
                        if (error.response.status == 422) {

                            // Email validation error
                            if (error.response.data.errors['email']) {

                                this.$refs.createUser.setErrors({
                                    'email': error.response.data.errors['email']
                                });
                            }

                            // Password validation error
                            if (error.response.data.errors['password']) {

                                this.$refs.createUser.setErrors({
                                    'password': error.response.data.errors['password']
                                });
                            }

                            // Password validation error
                            if (error.response.data.errors['storage_capacity']) {

                                this.$refs.createUser.setErrors({
                                    'storage capacity': 'The storage capacity must be lower than 10 digit number.'
                                });
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
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';
</style>
