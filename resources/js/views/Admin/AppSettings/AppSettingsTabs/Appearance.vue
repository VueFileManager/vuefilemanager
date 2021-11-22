<template>
    <PageTab :is-loading="isLoading">

        <!--Personal Information-->
        <PageTabGroup v-if="app">
            <div class="form block-form">

				<div class="card shadow-card">
					<FormLabel>
						{{ $t('admin_settings.appearance.section_general') }}
					</FormLabel>

					<AppInputText :title="$t('admin_settings.appearance.title')">
						<input @input="$updateText('/admin/settings', 'app_title', app.title)" v-model="app.title" :placeholder="$t('admin_settings.appearance.title_plac')" type="text" class="focus-border-theme input-dark"/>
					</AppInputText>

					<AppInputText :title="$t('admin_settings.appearance.description')">
						<input @input="$updateText('/admin/settings', 'app_description', app.description)" v-model="app.description" :placeholder="$t('admin_settings.appearance.description_plac')" type="text" class="focus-border-theme input-dark"/>
					</AppInputText>

					<AppInputSwitch :title="$t('color_theme')" :description="$t('color_theme_description')">
						<input @input="$updateText('/admin/settings', 'app_color', app.color)" v-model="app.color" :placeholder="$t('admin_settings.appearance.title_plac')" type="color"/>
					</AppInputSwitch>
				</div>

				<div class="card shadow-card">
					<FormLabel>
						{{ $t('Branding') }}
					</FormLabel>
					<div class="block-wrapper">
						<label>{{ $t('admin_settings.appearance.logo') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo" v-slot="{ errors }">
							<ImageInput @input="$updateImage('/admin/settings', 'app_logo', app.logo)" :image="$getImage(app.logo)" v-model="app.logo" :error="errors[0]"/>
						</ValidationProvider>
					</div>
					<div class="block-wrapper">
						<label>{{ $t('admin_settings.appearance.logo_horizontal') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Logo Horizontal" v-slot="{ errors }">
							<ImageInput @input="$updateImage('/admin/settings', 'app_logo_horizontal', app.logo_horizontal)" :image="$getImage(app.logo_horizontal)"
										v-model="app.logo_horizontal" :error="errors[0]"/>
						</ValidationProvider>
					</div>
					<div class="block-wrapper">
						<label>{{ $t('admin_settings.appearance.favicon') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
							<ImageInput @input="$updateImage('/admin/settings', 'app_favicon', app.favicon)" :image="$getImage(app.favicon)" v-model="app.favicon" :error="errors[0]"/>
						</ValidationProvider>
					</div>
					<div class="block-wrapper">
						<label>{{ $t('og_image') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
							<ImageInput @input="$updateImage('/admin/settings', 'app_og_image', app.og_image)" :image="$getImage(app.og_image)" v-model="app.og_image" :error="errors[0]"/>
							<small class="input-help">{{ $t('og_image_description') }}</small>
						</ValidationProvider>
					</div>
					<div class="block-wrapper">
						<label>{{ $t('app_touch_icon') }}:</label>
						<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Favicon" v-slot="{ errors }">
							<ImageInput @input="$updateImage('/admin/settings', 'app_touch_icon', app.touch_icon)" :image="$getImage(app.touch_icon)" v-model="app.touch_icon" :error="errors[0]"/>
							<small class="input-help">{{ $t('app_touch_icon_description') }}</small>
						</ValidationProvider>
					</div>
				</div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch";
	import AppInputText from "../../../../components/Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '/resources/js/components/Others/StorageItemDetail'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import axios from 'axios'

    export default {
        name: 'AppAppearance',
        components: {
			AppInputSwitch,
			AppInputText,
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
            axios.get('/api/admin/settings', {
                params: {
                    column: 'app_title|app_description|app_logo|app_favicon|app_logo_horizontal|app_color|app_og_image|app_touch_icon'
                }
            })
                .then(response => {
                    this.app = {
                        logo_horizontal: response.data.app_logo_horizontal,
                        description: response.data.app_description,
                        favicon: response.data.app_favicon,
                        title: response.data.app_title,
                        color: response.data.app_color,
                        logo: response.data.app_logo,
                        og_image: response.data.app_og_image,
                        touch_icon: response.data.app_touch_icon,
                    }
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    }
</script>
