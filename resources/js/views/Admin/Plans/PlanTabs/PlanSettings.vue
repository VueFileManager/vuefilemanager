<template>
    <PageTab>
        <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form form-fixed-width">
            <PageTabGroup>
                <FormLabel>
                    {{ $t('admin_page_plans.form.title_details') }}
                </FormLabel>

                <!--Visible-->
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">{{ $t('admin_page_plans.form.status') }}:</label>
                                <small class="input-help">{{ $t('admin_page_plans.form.status_help') }}</small>
                            </div>
                            <SwitchInput @input="changeStatus" class="switch" :state="plan.attributes.status"/>
                        </div>
                    </div>
                </div>

                <!--Name-->
                <div class="block-wrapper">
                    <label>{{ $t('admin_page_plans.form.name') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/admin/plans/' + $route.params.id, 'name', plan.attributes.name)" v-model="plan.attributes.name" :placeholder="$t('admin_page_plans.form.name_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <!--Description-->
                <div class="block-wrapper">
                    <label>{{ $t('admin_page_plans.form.description') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description" v-slot="{ errors }">
                        <textarea @input="$updateText('/admin/plans/' + $route.params.id, 'description', plan.attributes.description)" v-model="plan.attributes.description" :placeholder="$t('admin_page_plans.form.description_plac')" :class="{'is-error': errors[0]}" class="focus-border-theme"></textarea>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <!--Storage Capacity-->
                <div class="block-wrapper">
                    <label>{{ $t('admin_page_plans.form.storage') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage capacity" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/admin/plans/' + $route.params.id, 'capacity', plan.attributes.capacity)" v-model="plan.attributes.capacity" :placeholder="$t('admin_page_plans.form.storage_plac')" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}" class="focus-border-theme"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <small class="input-help">
                        {{ $t('admin_page_plans.form.storage_helper') }}
                    </small>
                </div>

                <InfoBox>
                    <p>{{ $t('admin_page_plans.disclaimer_edit_price') }}</p>
                </InfoBox>
            </PageTabGroup>
        </ValidationObserver>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'

    export default {
        name: 'PlanSettings',
        props: [
            'plan'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
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
                this.$updateText('/admin/plans/' + this.$route.params.id, 'is_active', val)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
    @import '/resources/sass/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
