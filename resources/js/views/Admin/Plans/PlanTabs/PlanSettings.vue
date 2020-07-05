<template>
    <PageTab>
        <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form form-fixed-width">
            <PageTabGroup>
                <FormLabel>Plan details</FormLabel>

                <!--Visible-->
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Status:</label>
                                <small class="input-help">Status of your plan availability on website.</small>
                            </div>
                            <SwitchInput @input="changeStatus" class="switch" :state="plan.attributes.status"/>
                        </div>
                    </div>
                </div>

                <!--Name-->
                <div class="block-wrapper">
                    <label>Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/plans/' + $route.params.id + '/update', 'name', plan.attributes.name)" v-model="plan.attributes.name" placeholder="Plan name" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <!--Description-->
                <div class="block-wrapper">
                    <label>Description (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description" v-slot="{ errors }">
                        <textarea @input="$updateText('/plans/' + $route.params.id + '/update', 'description', plan.attributes.description)" v-model="plan.attributes.description" placeholder="Plan description" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <!--Storage Capacity-->
                <div class="block-wrapper">
                    <label>Storage Capacity in GB:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage capacity" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/plans/' + $route.params.id + '/update', 'capacity', plan.attributes.capacity)" v-model="plan.attributes.capacity" placeholder="Storage capacity" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <small class="input-help">You have to type only number e.g. value '5' means, user will have 5GB of storage capacity.</small>
                </div>

                <InfoBox>
                    <p>Price change for your plan is not available due to Stripe service design. If you wish change your price plan, please, create new plan.</p>
                </InfoBox>
            </PageTabGroup>
        </ValidationObserver>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'

    export default {
        name: 'PlanSettings',
        props: [
            'plan'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            PageTabGroup,
            SwitchInput,
            SelectInput,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            InfoBox,
            PageTab,
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
            }
        },
        methods: {
            changeStatus(val) {
                this.$updateText('/plans/' + this.$route.params.id + '/update', 'is_active', val)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
