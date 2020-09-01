<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup v-if="app">
            <div class="form block-form">
                <FormLabel>{{ $t('admin_settings.appearance.section_general') }}</FormLabel>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.appearance.title') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'app_title', app.title)" v-model="app.title" :placeholder="$t('admin_settings.appearance.title_plac')" type="text"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.appearance.description') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'app_description', app.description)" v-model="app.description"
                               :placeholder="$t('admin_settings.appearance.description_plac')" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">{{ $t('admin_settings.appearance.section_appearance') }}</FormLabel>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.appearance.logo') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo" v-slot="{ errors }">
                        <ImageInput @input="$updateImage('/settings', 'app_logo', app.logo)" :image="$getImage(app.logo)" v-model="app.logo" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.appearance.logo_horizontal') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo Horizontal" v-slot="{ errors }">
                        <ImageInput @input="$updateImage('/settings', 'app_logo_horizontal', app.logo_horizontal)" :image="$getImage(app.logo_horizontal)"
                                    v-model="app.logo_horizontal" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.appearance.favicon') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
                        <ImageInput @input="$updateImage('/settings', 'app_favicon', app.favicon)" :image="$getImage(app.favicon)" v-model="app.favicon" :error="errors[0]"/>
                    </ValidationProvider>
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
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import axios from 'axios'

    export default {
        name: 'AppAppearance',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
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
                app: undefined,
            }
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'app_title|app_description|app_logo|app_favicon|app_logo_horizontal'
                }
            })
                .then(response => {
                    this.app = {
                        logo_horizontal: response.data.app_logo_horizontal,
                        description: response.data.app_description,
                        favicon: response.data.app_favicon,
                        title: response.data.app_title,
                        logo: response.data.app_logo,
                    }
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

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
