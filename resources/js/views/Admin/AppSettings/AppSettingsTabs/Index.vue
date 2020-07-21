<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <PageTabGroup>
            <div class="form block-form">

                <!--Header-->
                <div>
                    <FormLabel>Header Title</FormLabel>

                    <div class="block-wrapper">
                        <img src="/assets/images/admin/main-header.jpg" alt="Main Header" class="page-image">
                    </div>

                    <div class="block-wrapper">
                        <label>Title:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/settings', 'header_title', header_title)" v-model="header_title"
                                   type="text"
                                   :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="block-wrapper">
                        <label>Description:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'header_description', header_description)" rows="2" v-model="header_description"
                                      :class="{'is-error': errors[0]}"></textarea>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>

                <!--Features title-->
                <div>
                    <FormLabel class="mt-70">Features Title</FormLabel>

                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">
                                        Show section:
                                    </label>
                                </div>
                                <SwitchInput
                                        @input="$updateText('/settings', 'section_features', section_features)"
                                        v-model="section_features"
                                        class="switch"
                                        :state="section_features"
                                />
                            </div>
                        </div>
                    </div>


                    <div v-if="section_features">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/main-features.jpg" alt="Main Features" class="page-image">
                        </div>

                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'features_title', features_title)" v-model="features_title"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'features_description', features_description)" rows="2" v-model="features_description"
                                          :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <!--Feature boxes-->
                <div>
                    <FormLabel class="mt-70">Feature Boxes</FormLabel>

                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">
                                        Show section:
                                    </label>
                                </div>
                                <SwitchInput
                                        @input="$updateText('/settings', 'section_feature_boxes', section_feature_boxes)"
                                        v-model="section_feature_boxes"
                                        class="switch"
                                        :state="section_feature_boxes"
                                />
                            </div>
                        </div>
                    </div>

                    <div v-if="section_feature_boxes">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/feature-boxes.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>First Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 1" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_1', feature_title_1)" v-model="feature_title_1"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>First Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 1" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_1', feature_description_1)" rows="2" v-model="feature_description_1"
                                          :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Second Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 2" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_2', feature_title_2)" v-model="feature_title_2"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Second Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 2" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_2', feature_description_2)" rows="2" v-model="feature_description_2"
                                          :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Third Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 3" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_3', feature_title_3)" v-model="feature_title_3"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Third Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 3" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_3', feature_description_3)" rows="2" v-model="feature_description_3"
                                          :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <!--Pricing Content-->
                <div>
                    <FormLabel class="mt-70">Pricing Content</FormLabel>

                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">
                                        Show section:
                                    </label>
                                </div>
                                <SwitchInput
                                        @input="$updateText('/settings', 'section_pricing_content', section_pricing_content)"
                                        v-model="section_pricing_content"
                                        class="switch"
                                        :state="section_pricing_content"
                                />
                            </div>
                        </div>
                    </div>


                    <div v-if="section_pricing_content">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/pricing-content.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'pricing_title', pricing_title)" v-model="pricing_title"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'pricing_description', pricing_description)" rows="2" v-model="pricing_description"
                                      :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <!--Get Started-->
                <div>
                    <FormLabel class="mt-70">Get Started Content</FormLabel>

                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">
                                        Show section:
                                    </label>
                                </div>
                                <SwitchInput
                                        @input="$updateText('/settings', 'section_get_started', section_get_started)"
                                        v-model="section_get_started"
                                        class="switch"
                                        :state="section_get_started"
                                />
                            </div>
                        </div>
                    </div>


                    <div v-if="section_get_started">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/get-started-content.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'get_started_title', get_started_title)" v-model="get_started_title"
                                       type="text"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'get_started_description', get_started_description)" rows="2" v-model="get_started_description"
                                      :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <!--Footer-->
                <div>
                    <FormLabel class="mt-70">Footer</FormLabel>

                    <div class="block-wrapper">
                        <label>Footer content:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/settings', 'footer_content', footer_content)" v-model="footer_content"
                                   type="text"
                                   :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import axios from 'axios'

    export default {
        name: 'AppIndex',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
            SwitchInput,
            SelectInput,
            ImageInput,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            PageTab,
            InfoBox,
        },
        data() {
            return {
                isLoading: true,
                section_features: 1,
                section_feature_boxes: 1,
                section_pricing_content: 1,
                section_get_started: 1,

                header_title: '',
                header_description: '',

                features_title: '',
                features_description: '',

                feature_title_1: '',
                feature_description_1: '',

                feature_title_2: '',
                feature_description_2: '',

                feature_title_3: '',
                feature_description_3: '',

                pricing_title: '',
                pricing_description: '',

                get_started_title: '',
                get_started_description: '',

                footer_content: '',
            }
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'footer_content|get_started_description|get_started_title|pricing_description|pricing_title|feature_description_3|feature_title_3|feature_description_2|feature_title_2|feature_description_1|feature_title_1|features_description|features_title|header_description|header_title|section_get_started|section_pricing_content|section_feature_boxes|section_features'
                }
            })
                .then(response => {
                    this.section_features = parseInt(response.data.section_features)
                    this.section_feature_boxes = parseInt(response.data.section_feature_boxes)
                    this.section_pricing_content = parseInt(response.data.section_pricing_content)
                    this.section_get_started = parseInt(response.data.section_get_started)

                    this.header_title = response.data.header_title
                    this.header_description = response.data.header_description

                    this.features_title = response.data.features_title
                    this.features_description = response.data.features_description

                    this.feature_title_1 = response.data.feature_title_1
                    this.feature_description_1 = response.data.feature_description_1

                    this.feature_title_2 = response.data.feature_title_2
                    this.feature_description_2 = response.data.feature_description_2

                    this.feature_title_3 = response.data.feature_title_3
                    this.feature_description_3 = response.data.feature_description_3

                    this.pricing_title = response.data.pricing_title
                    this.pricing_description = response.data.pricing_description

                    this.get_started_title = response.data.get_started_title
                    this.get_started_description = response.data.get_started_description

                    this.footer_content = response.data.footer_content
                })
                .finally(() => {
                    this.isLoading = false
                })
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

    .page-image {
        width: 100%;
        margin: 0 auto;
        display: block;
        border-radius: 8px;
        border: 1px solid #ececec;
    }
</style>
