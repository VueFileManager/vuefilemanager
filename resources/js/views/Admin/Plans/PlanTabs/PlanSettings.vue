<template>
    <PageTab>
        <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form">
            <PageTabGroup>

                <!--Visible-->
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <label class="input-label">Status:</label>
                            <SwitchInput @input="changeStatus" class="switch" :state="plan.attributes.status"/>
                        </div>
                        <small class="input-help">Status of your payment gateway on website.</small>
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

                <!--Price-->
                <div class="block-wrapper">
                    <label>Price:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Plan price" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/plans/' + $route.params.id + '/update', 'price', plan.attributes.price)" v-model="plan.attributes.price" placeholder="Plan price" type="number" step="0.01" min="1" max="99999" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <!--Storage Capacity-->
                <div class="block-wrapper">
                    <label>Storage Capacity:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage capacity" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/plans/' + $route.params.id + '/update', 'capacity', plan.attributes.capacity)" v-model="plan.attributes.capacity" placeholder="Storage capacity" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <small class="input-help">You have to type only number e.g. value '5' means, user will have 5GB of storage capacity.</small>
                </div>
            </PageTabGroup>
        </ValidationObserver>
    </PageTab>
</template>

<script>
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'

    export default {
        name: 'PlanSettings',
        props: [
            'plan'
        ],
        components: {
            SwitchInput,
            PageTabGroup,
            PageTab,
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
            }
        },
        methods: {
            changeStatus(val) {
                this.$updateText('/plans/' + this.$route.params.id + '/update', 'state', val)
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
