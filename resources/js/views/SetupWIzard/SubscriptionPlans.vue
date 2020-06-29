<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up plans for your customers.</h2>
            </div>

            <ValidationObserver @submit.prevent="subscriptionPlansSubmit" ref="subscriptionPlans" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>Create your plans</FormLabel>
                <InfoBox>
                    <p>Your plans will be <b>sorted automatically</b> in ascent order by plan price.</p>
                </InfoBox>

                <div class="duplicator">
                    <div class="plan-item duplicator-item" v-for="(plan, index) in subscriptionPlans" :key="index++">
                        <x-icon @click="removeRow(plan)" v-if="index !== 1" size="22" class="delete-item"></x-icon>
                        <b class="duplicator-title">{{ index }}. Plan</b>
                        <div class="block-wrapper">
                            <label>Name:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.name" placeholder="Type your plan name"
                                       type="text"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description (optional):</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description"
                                                rules="required" v-slot="{ errors }">
                                <textarea v-model="plan.description" placeholder="Type your plan description"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Price:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Price"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.price" placeholder="Type your plan price" type="text"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Storage Capacity:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage Capacity"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.storage_capacity" placeholder="Type your storage capacity" type="text"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>

                    <ButtonBase
                            @click.native="addRow"
                            class="duplicator-add-button"
                            button-style="theme-solid"
                    >Add New Plan</ButtonBase>
                </div>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Save and Go Next" :loading="isLoading" :disabled="isLoading"/>
                </div>

            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import {SettingsIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import { XIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'subscriptionPlans',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            SettingsIcon,
            SelectInput,
            AuthContent,
            ButtonBase,
            AuthButton,
            FormLabel,
            required,
            InfoBox,
            XIcon,
        },
        data() {
            return {
                isLoading: false,
                subscriptionPlans: [
                    {
                        id: 1,
                        name: '',
                        description: '',
                        price: '',
                        storage_capacity: '',
                    }
                ]
            }
        },
        methods: {
            async  subscriptionPlansSubmit() {
                this.$router.push({name: 'EnvironmentSetup'})
            },
            addRow() {
                this.subscriptionPlans.push({
                    id: Math.floor(Math.random() * 10000000),
                    name: '',
                    description: '',
                    price: '',
                    storage_capacity: '',
                })
            },
            removeRow(plan) {
                this.subscriptionPlans = this.subscriptionPlans.filter(item => item.id !== plan.id)
            },
        },
        created() {
            var container = document.getElementById('vue-file-manager')
            container.scrollTop = 0
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';
</style>
