<template>
    <PageTab class="form-fixed-width">
        <PageTabGroup>
            <FormLabel>
                {{ $t('admin_page_plans.form.title_delete') }}
            </FormLabel>
            <InfoBox>
                <p>{{ $t('admin_page_plans.disclaimer_delete_plan') }}</p>
            </InfoBox>
            <ValidationObserver ref="deletePlan" @submit.prevent="deletePlan" v-slot="{ invalid }" tag="form" class="form block-form">
                <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive" name="Plan name" :rules="'required|is:' + plan.attributes.name">
                    <label>{{ $t('admin_page_user.label_delete_user', {user: plan.attributes.name}) }}:</label>
                    <div class="single-line-form">
                        <input v-model="planName" :placeholder="$t('admin_page_plans.form.name_delete_plac')" type="text" :class="{'is-error': errors[0]}" />
                        <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="danger" class="submit-button">
                            {{ $t('admin_page_plans.delete_plan_button') }}
                        </ButtonBase>
                    </div>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'

    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required, is} from 'vee-validate/dist/rules'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'PlanDelete',
        props: [
            'plan'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
            PageTabGroup,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            InfoBox,
            PageTab,
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
                    .post(this.$store.getters.api + '/plans/' + this.$route.params.id,
                        {
                            data: {
                                name: this.planName
                            },
                            _method: 'delete'
                        }
                    )
                    .then(() => {
                        this.isSendingRequest = false

                        // Show message
                        events.$emit('success:open', {
                            emoji: '👍',
                            title: this.$t('popup_deleted_plan.title'),
                            message: this.$t('popup_deleted_plan.message'),
                        })

                        this.$router.push({name: 'Plans'})
                    })
                    .catch(() => {

                        this.isSendingRequest = false

                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
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
</style>
