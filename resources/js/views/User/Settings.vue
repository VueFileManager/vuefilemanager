<template>
    <div>
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('Account Settings') }}
            </FormLabel>

            <div class="justify-items md:flex md:space-x-4">
                <AppInputText :title="$t('First Name')" class="w-full">
                    <input
                        @keyup="updateFirstName"
                        v-model="user.data.relationships.settings.data.attributes.first_name"
                        :placeholder="$t('page_registration.placeholder_name')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
                <AppInputText :title="$t('Last Name')" class="w-full">
                    <input
                        @keyup="updateLastName"
                        v-model="user.data.relationships.settings.data.attributes.last_name"
                        :placeholder="$t('page_registration.placeholder_name')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </div>

            <AppInputText :title="$t('GMT')" :is-last="true">
                <SelectInput
                    @input="$updateText('/user/settings', 'timezone', user.data.relationships.settings.data.attributes.timezone)"
                    v-model="user.data.relationships.settings.data.attributes.timezone"
                    :default="user.data.relationships.settings.data.attributes.timezone"
                    :options="timezones"
                    :placeholder="$t('user_settings.timezone_plac')"
                />
            </AppInputText>
        </div>

        <div class="card shadow-card">
            <FormLabel>
                {{ $t('user_settings.title_billing') }}
            </FormLabel>
            <AppInputText :title="$t('user_settings.address')">
                <input
                    @keyup="$updateText('/user/settings', 'address', user.data.relationships.settings.data.attributes.address)"
                    v-model="user.data.relationships.settings.data.attributes.address"
                    :placeholder="$t('user_settings.address_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
            <div class="flex space-x-4">
                <AppInputText :title="$t('user_settings.city')" class="w-full">
                    <input
                        @keyup="$updateText('/user/settings', 'city', user.data.relationships.settings.data.attributes.city)"
                        v-model="user.data.relationships.settings.data.attributes.city"
                        :placeholder="$t('user_settings.city_plac')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
                <AppInputText :title="$t('user_settings.postal_code')" class="w-full">
                    <input
                        @keyup="$updateText('/user/settings', 'postal_code', user.data.relationships.settings.data.attributes.postal_code)"
                        v-model="user.data.relationships.settings.data.attributes.postal_code"
                        :placeholder="$t('user_settings.postal_code_plac')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </div>
            <AppInputText :title="$t('user_settings.country')">
                <SelectInput
                    @input="$updateText('/user/settings', 'country', user.data.relationships.settings.data.attributes.country)"
                    v-model="user.data.relationships.settings.data.attributes.country"
                    :default="user.data.relationships.settings.data.attributes.country"
                    :options="countries"
                    :placeholder="$t('user_settings.country_plac')"
                />
            </AppInputText>
            <AppInputText :title="$t('user_settings.state')" :description="$t('State, county, province, or region.')">
                <input
                    @keyup="$updateText('/user/settings', 'state', user.data.relationships.settings.data.attributes.state)"
                    v-model="user.data.relationships.settings.data.attributes.state"
                    :placeholder="$t('user_settings.state_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
            <AppInputText :title="$t('user_settings.phone_number')" :is-last="true">
                <input
                    @keyup="$updateText('/user/settings', 'phone_number', user.data.relationships.settings.data.attributes.phone_number)"
                    v-model="user.data.relationships.settings.data.attributes.phone_number"
                    :placeholder="$t('user_settings.phone_number_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>

		<div class="card shadow-card">
            <FormLabel>
                {{ $t('Appearance') }}
            </FormLabel>

            <AppInputText :title="$t('Theme Mode')" :description="$t('Set your theme mode on dark, light or based on your system settings.')" :is-last="!$isApple()">
                <div class="items-center space-y-4 md:flex md:space-x-6 md:space-x-4 md:space-y-0">
                    <div
						v-for="(theme, i) in themeSetup"
						:key="i"
						:title="theme.title"
						@click="$store.dispatch('toggleThemeMode', theme.type)"
						class="w-full cursor-pointer overflow-hidden rounded-xl border-3 shadow-lg"
						:class="{
                            'border-theme': config.defaultThemeMode === theme.type,
                            'border-transparent': config.defaultThemeMode !== theme.type,
                        }"
					>
                        <img :src="theme.image" :alt="theme.type" />
                    </div>
                </div>
            </AppInputText>

            <AppInputText
				v-if="$isApple()"
				:title="$t('Default Emojis')"
				:description="$t('Set your default emojis for your folder custom icons. You can set Twemoji or default Apple emojis.')"
				:is-last="true"
			>
                <div class="items-center space-y-4 md:flex md:space-x-6 md:space-x-4 md:space-y-0">
                    <div
						v-for="(emoji, i) in emojiSetup"
						:key="i"
						:title="emoji.title"
						@click="$store.dispatch('toggleEmojiType', emoji.type)"
						class="w-full cursor-pointer overflow-hidden rounded-xl border-3 shadow-lg"
						:class="{
                            'border-theme': currentEmojis === emoji.type,
                            'border-transparent': currentEmojis !== emoji.type,
                        }"
					>
                        <img :src="isDarkMode ? emoji.image.dark : emoji.image.light" :alt="emoji.type" />
                    </div>
                </div>
            </AppInputText>
        </div>
    </div>
</template>

<script>
import AppInputText from '../../components/Admin/AppInputText'
import SelectInput from '../../components/Others/Forms/SelectInput'
import FormLabel from '../../components/Others/Forms/FormLabel'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'

export default {
    name: 'Settings',
    components: {
        AppInputText,
        SelectInput,
        FormLabel,
        required,
    },
    computed: {
        ...mapGetters(['isDarkMode', 'countries', 'timezones', 'config']),
        currentEmojis() {
            return this.config.defaultEmoji
        },
    },
    data() {
        return {
            user: this.$store.getters.user,
            isLoading: false,
            themeSetup: [
                {
                    title: this.$t('Light mode'),
                    type: 'light',
                    image: '/assets/setup/light-mode.jpg',
                },
                {
                    title: this.$t('Dark mode'),
                    type: 'dark',
                    image: '/assets/setup/dark-mode.jpg',
                },
                {
                    title: this.$t('Based on system settings'),
                    type: 'system',
                    image: '/assets/setup/system-mode.jpg',
                },
            ],
            emojiSetup: [
                {
                    title: 'Twemoji',
                    type: 'twemoji',
                    image: {
                        dark: '/assets/setup/twemoji-preview-dark.jpg',
                        light: '/assets/setup/twemoji-preview.jpg',
                    },
                },
                {
                    title: 'Applemoji',
                    type: 'applemoji',
                    image: {
                        dark: '/assets/setup/applemoji-preview-dark.jpg',
                        light: '/assets/setup/applemoji-preview.jpg',
                    },
                },
            ],
        }
    },
    methods: {
        updateFirstName() {
            this.$store.commit('UPDATE_FIRST_NAME', this.user.data.relationships.settings.data.attributes.first_name)
            this.$updateText('/user/settings', 'first_name', this.user.data.relationships.settings.data.attributes.first_name)
        },
        updateLastName() {
            this.$store.commit('UPDATE_LAST_NAME', this.user.data.relationships.settings.data.attributes.last_name)
            this.$updateText('/user/settings', 'last_name', this.user.data.relationships.settings.data.attributes.last_name)
        },
    },
}
</script>
