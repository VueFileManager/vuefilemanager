<template>
    <div class="page-tab">

        <!--Personal Information-->
        <div class="page-tab-group">
            <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form">

                <b class="form-group-label">Stripe Secret</b>
                <!---->
                <div class="block-wrapper">
                    <label>Stripe Client ID</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ClientID" rules="required"
                                        v-slot="{ errors }">
                        <input placeholder="Paste stripe client id here" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <!---->
                <div class="block-wrapper">
                    <label>Stripe Secret</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="StripeSecret" rules="required"
                                        v-slot="{ errors }">
                        <input placeholder="Paste stripe secret here" type="text" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserDetail',
        props: [
            'user'
        ],
        components: {
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
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .page-tab {

        .page-tab-group {
            margin-bottom: 45px;
        }
    }

    .block-form {
        max-width: 100%;
    }


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
