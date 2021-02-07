<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <PageTabGroup v-if="app">
            <div class="form block-form">

                <FormLabel>Home Page</FormLabel>

                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">
                                    Allow Homepage
                                </label>
                                <small class="input-help">
                                    When this is turned on, your visitors can visit your default homepage.
                                </small>
                            </div>
                            <SwitchInput @input="$updateText('/settings', 'allow_homepage', app.allow_homepage)" v-model="app.allow_homepage" class="switch" :state="app.allow_homepage"/>
                        </div>
                    </div>
                </div>

                <!--Header-->
                <div>
                    <FormLabel>Header Title</FormLabel>

                    <div class="block-wrapper">
                        <img src="/assets/images/admin/main-header.jpg" alt="Main Header" class="page-image">
                    </div>

                    <div class="block-wrapper">
                        <label>Title:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/settings', 'header_title', app.header_title)" v-model="app.header_title" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="block-wrapper">
                        <label>Description:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'header_description', app.header_description)" rows="2" v-model="app.header_description" :class="{'is-error': errors[0]}"></textarea>
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
                                <SwitchInput @input="$updateText('/settings', 'section_features', app.section_features)" v-model="app.section_features" class="switch" :state="app.section_features"/>
                            </div>
                        </div>
                    </div>


                    <div v-if="app.section_features">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/main-features.jpg" alt="Main Features" class="page-image">
                        </div>

                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'features_title', app.features_title)" v-model="app.features_title" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'features_description', app.features_description)" rows="2" v-model="app.features_description" :class="{'is-error': errors[0]}"></textarea>
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
                                <SwitchInput @input="$updateText('/settings', 'section_feature_boxes', app.section_feature_boxes)" v-model="app.section_feature_boxes" class="switch" :state="app.section_feature_boxes"/>
                            </div>
                        </div>
                    </div>

                    <div v-if="app.section_feature_boxes">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/feature-boxes.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>First Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 1" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_1', app.feature_title_1)" v-model="app.feature_title_1" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>First Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 1" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_1', app.feature_description_1)" rows="2" v-model="app.feature_description_1" :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Second Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 2" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_2', app.feature_title_2)" v-model="app.feature_title_2" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Second Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 2" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_2', app.feature_description_2)" rows="2" v-model="app.feature_description_2" :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Third Box Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Title 3" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'feature_title_3', app.feature_title_3)" v-model="app.feature_title_3" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="block-wrapper">
                            <label>Third Box Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Feature Description 3" rules="required" v-slot="{ errors }">
                                <textarea @input="$updateText('/settings', 'feature_description_3', app.feature_description_3)" rows="2" v-model="app.feature_description_3" :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <!--Pricing Content-->
                <div v-if="config.isSaaS">
                    <FormLabel class="mt-70">Pricing Content</FormLabel>

                    <div class="block-wrapper">
                        <div class="input-wrapper">
                            <div class="inline-wrapper">
                                <div class="switch-label">
                                    <label class="input-label">
                                        Show section:
                                    </label>
                                </div>
                                <SwitchInput @input="$updateText('/settings', 'section_pricing_content', app.section_pricing_content)" v-model="app.section_pricing_content" class="switch" :state="app.section_pricing_content"/>
                            </div>
                        </div>
                    </div>

                    <div v-if="app.section_pricing_content">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/pricing-content.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'pricing_title', app.pricing_title)" v-model="app.pricing_title" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'pricing_description', app.pricing_description)" rows="2" v-model="app.pricing_description" :class="{'is-error': errors[0]}"></textarea>
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
                                <SwitchInput @input="$updateText('/settings', 'section_get_started', app.section_get_started)" v-model="app.section_get_started" class="switch" :state="app.section_get_started"/>
                            </div>
                        </div>
                    </div>


                    <div v-if="app.section_get_started">
                        <div class="block-wrapper">
                            <img src="/assets/images/admin/get-started-content.jpg" alt="Main Features" class="page-image">
                        </div>
                        <div class="block-wrapper">
                            <label>Title:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                                <input @input="$updateText('/settings', 'get_started_title', app.get_started_title)" v-model="app.get_started_title" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                            <textarea @input="$updateText('/settings', 'get_started_description', app.get_started_description)" rows="2" v-model="app.get_started_description" :class="{'is-error': errors[0]}"></textarea>
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
                            <input @input="$updateText('/settings', 'footer_content', app.footer_content)" v-model="app.footer_content" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
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
import { required } from 'vee-validate/dist/rules'
import axios from 'axios'
import { mapGetters } from 'vuex'

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
        InfoBox
    },
    computed: {
        ...mapGetters(['config'])
    },
    data() {
        return {
            isLoading: true,
            app: undefined
        }
    },
    mounted() {
        axios.get('/api/settings', {
            params: {
                column: 'allow_homepage|footer_content|get_started_description|get_started_title|pricing_description|pricing_title|feature_description_3|feature_title_3|feature_description_2|feature_title_2|feature_description_1|feature_title_1|features_description|features_title|header_description|header_title|section_get_started|section_pricing_content|section_feature_boxes|section_features'
            }
        })
            .then(response => {
                this.app = {
                    allow_homepage: parseInt(response.data.allow_homepage),
                    section_features: parseInt(response.data.section_features),
                    section_feature_boxes: parseInt(response.data.section_feature_boxes),
                    section_pricing_content: parseInt(response.data.section_pricing_content),
                    section_get_started: parseInt(response.data.section_get_started),
                    header_title: response.data.header_title,
                    header_description: response.data.header_description,
                    features_title: response.data.features_title,
                    features_description: response.data.features_description,
                    feature_title_1: response.data.feature_title_1,
                    feature_description_1: response.data.feature_description_1,
                    feature_title_2: response.data.feature_title_2,
                    feature_description_2: response.data.feature_description_2,
                    feature_title_3: response.data.feature_title_3,
                    feature_description_3: response.data.feature_description_3,
                    pricing_title: response.data.pricing_title,
                    pricing_description: response.data.pricing_description,
                    get_started_title: response.data.get_started_title,
                    get_started_description: response.data.get_started_description,
                    footer_content: response.data.footer_content
                }

                console.log(this.app);
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
