<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading && page">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <ValidationObserver ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form form-fixed-width">
                    <FormLabel>
                        {{ page.data.attributes.title }}
                    </FormLabel>

                    <!--Visible-->
                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">{{ $t('admin_pages.form.visibility') }}:</label>
                                    <small class="input-help">{{ $t('admin_pages.form.visibility_help') }}</small>
                                </div>
                                <SwitchInput @input="changeStatus" class="switch" :state="page.data.attributes.visibility"/>
                            </div>
                        </div>
                    </div>

                    <div class="block-wrapper">
                        <label>{{ $t('admin_pages.form.title') }}:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/pages/' + $route.params.slug, 'title', page.data.attributes.title)" v-model="page.data.attributes.title"
                                   :placeholder="$t('admin_pages.form.title_plac')" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="block-wrapper">
                        <label>{{ $t('admin_pages.form.slug') }}:</label>
                        <div class="input-wrapper">
                            <input v-model="page.data.attributes.slug" type="text" disabled/>
                        </div>
                    </div>

                    <div class="block-wrapper">
                        <label>{{ $t('admin_pages.form.content') }}:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                            <textarea
                                    @input="$updateText('/pages/' + $route.params.slug, 'content', page.data.attributes.content)"
                                    v-model="page.data.attributes.content"
                                    :placeholder="$t('admin_pages.form.content_plac')"
                                    :class="{'is-error': errors[0]}"
                                    rows="18"
                            ></textarea>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </ValidationObserver>
            </div>
        </div>

        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import {required} from 'vee-validate/dist/rules'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'PageEdit',
        components: {
            ValidationProvider,
            ValidationObserver,
            FormLabel,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            PageHeader,
            ButtonBase,
            required,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
                page: undefined,
            }
        },
        methods: {
            changeStatus(val) {
                this.$updateText('/pages/' + this.$route.params.slug , 'visibility', val)
            }
        },
        created() {
            axios.get('/api/pages/' + this.$route.params.slug)
                .then(response => {
                    this.page = response.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';
</style>
