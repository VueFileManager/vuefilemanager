<template>
    <PageTab class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>General Settings</FormLabel>

                <div class="block-wrapper">
                    <label>App Title:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Title" rules="required" v-slot="{ errors }">
                        <input v-model="app.title" placeholder="Type your app title" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Description:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                        <input v-model="app.description" placeholder="Type your app description" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Logo (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo" v-slot="{ errors }">
                        <ImageInput v-model="app.logo" :error="errors[0]"/>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>App Favicon (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
                        <ImageInput v-model="app.favicon" :error="errors[0]"/>
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
                isLoading: false,
                app: {
                    title: '',
                    description: '',
                    logo: undefined,
                    favicon: undefined,
                },
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
