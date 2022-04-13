<template>
    <PageTab :is-loading="isLoading">
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('admin_settings.appearance.section_general') }}
            </FormLabel>

            <AppInputSwitch :title="$t('color_theme')" :description="$t('color_theme_description')">
                <input
                    @input="$updateText('/admin/settings', 'app_color', app.color)"
                    v-model="app.color"
                    :placeholder="$t('admin_settings.appearance.title_plac')"
                    type="color"
                />
            </AppInputSwitch>

            <AppInputText :title="$t('admin_settings.appearance.title')">
                <input
                    @input="$updateText('/admin/settings', 'app_title', app.title)"
                    v-model="app.title"
                    :placeholder="$t('admin_settings.appearance.title_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputText :title="$t('admin_settings.appearance.description')" :is-last="true">
                <input
                    @input="$updateText('/admin/settings', 'app_description', app.description)"
                    v-model="app.description"
                    :placeholder="$t('admin_settings.appearance.description_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>
        <div v-if="app" class="card shadow-card">
            <FormLabel>
                {{ $t('branding') }}
            </FormLabel>

            <AppInputText :title="$t('admin_settings.appearance.logo')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_logo', app.logo)"
                    :image="$getImage(app.logo)"
                    v-model="app.logo"
                />
            </AppInputText>

            <AppInputText :title="$t('app_logo_dark_mode')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_logo_dark', app.logo_dark)"
                    :image="$getImage(app.logo_dark)"
                    v-model="app.logo_dark"
                />
            </AppInputText>

            <AppInputText :title="$t('admin_settings.appearance.logo_horizontal')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_logo_horizontal', app.logo_horizontal)"
                    :image="$getImage(app.logo_horizontal)"
                    v-model="app.logo_horizontal"
                />
            </AppInputText>

            <AppInputText :title="$t('app_logo_horizontal_dark_mode')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_logo_horizontal_dark', app.logo_horizontal_dark)"
                    :image="$getImage(app.logo_horizontal_dark)"
                    v-model="app.logo_horizontal_dark"
                />
            </AppInputText>

            <AppInputText :title="$t('admin_settings.appearance.favicon')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_favicon', app.favicon)"
                    :image="$getImage(app.favicon)"
                    v-model="app.favicon"
                />
            </AppInputText>

            <AppInputText :title="$t('og_image')" :description="$t('og_image_description')">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_og_image', app.og_image)"
                    :image="$getImage(app.og_image)"
                    v-model="app.og_image"
                />
            </AppInputText>

            <AppInputText :title="$t('app_touch_icon')" :description="$t('app_touch_icon_description')" :is-last="true">
                <ImageInput
                    @input="$updateImage('/admin/settings', 'app_touch_icon', app.touch_icon)"
                    :image="$getImage(app.touch_icon)"
                    v-model="app.touch_icon"
                />
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import SelectInput from '../../../../components/Inputs/SelectInput'
import ImageInput from '../../../../components/Inputs/ImageInput'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import { required } from 'vee-validate/dist/rules'
import axios from 'axios'

export default {
    name: 'AppAppearance',
    components: {
        AppInputSwitch,
        AppInputText,
        ValidationObserver,
        ValidationProvider,
        PageTabGroup,
        SelectInput,
        ImageInput,
        ButtonBase,
        FormLabel,
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
        axios
            .get('/api/admin/settings', {
                params: {
                    column: 'app_title|app_description|app_logo|app_logo_dark|app_favicon|app_logo_horizontal|app_logo_horizontal_dark|app_color|app_og_image|app_touch_icon',
                },
            })
            .then((response) => {
                this.app = {
                    logo_horizontal: response.data.app_logo_horizontal,
                    logo_horizontal_dark: response.data.app_logo_horizontal_dark,
                    description: response.data.app_description,
                    favicon: response.data.app_favicon,
                    title: response.data.app_title,
                    color: response.data.app_color,
                    logo: response.data.app_logo,
                    logo_dark: response.data.app_logo_dark,
                    og_image: response.data.app_og_image,
                    touch_icon: response.data.app_touch_icon,
                }
            })
            .finally(() => {
                this.isLoading = false
            })
    },
}
</script>
