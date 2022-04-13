<template>
    <PageTab>
        <!--Adsense basic setup-->
        <div v-if="adsense" class="card shadow-card">
            <FormLabel>
                {{ $t('Basic Setup') }}
            </FormLabel>

            <AppInputSwitch :title="$t('Allow Google Adsense')" :description="$t('Allow ads on app pages.')">
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_adsense', adsense.allowedService)"
                    v-model="adsense.allowedService"
                    class="switch"
                    :state="adsense.allowedService"
                />
            </AppInputSwitch>

            <AppInputText
                :title="$t('Client Id')"
                :description="$t('Paste your Adsense Client ID e.g. ca-pub-XXXXXXXXXXXXXXXXX')"
                :is-last="true"
            >
                <input
                    @input="$updateText('/admin/settings', 'adsense_client_id', adsense.clientId, true)"
                    v-model="adsense.clientId"
                    :placeholder="$t('Client Id...')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>

        <!--Adsense places-->
        <div v-if="adsense" class="card shadow-card">
            <FormLabel>
                {{ $t('Ads') }}
            </FormLabel>

            <AppInputText
                :title="$t('File Viewport Banner')"
                :description="$t('This banner will be showed above user files')"
            >
                <textarea
                    rows="3"
                    @input="$updateText('/admin/settings', 'adsense_banner_01', adsense.banner01, true)"
                    v-model="adsense.banner01"
                    :placeholder="$t('Paste the <ins></ins> tag here...')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputText
                :title="$t('Download Page Banner')"
                :description="$t('This banner will be showed below file download page')"
            >
                <textarea
                    rows="3"
                    @input="$updateText('/admin/settings', 'adsense_banner_02', adsense.banner02, true)"
                    v-model="adsense.banner02"
                    :placeholder="$t('Paste the <ins></ins> tag here...')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputText
                :title="$t('Homepage Banner')"
                :description="$t('This banner will be showed on the homepage')"
                :is-last="true"
            >
                <textarea
                    rows="3"
                    @input="$updateText('/admin/settings', 'adsense_banner_03', adsense.banner03, true)"
                    v-model="adsense.banner03"
                    :placeholder="$t('Paste the <ins></ins> tag here...')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import SwitchInput from '../../../../components/Inputs/SwitchInput'
import AppInputButton from '../../../../components/Forms/Layouts/AppInputButton'
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import PageTab from '../../../../components/Layout/PageTab'
import { mapGetters } from 'vuex'

export default {
    name: 'Adsense',
    components: {
        AppInputButton,
        AppInputSwitch,
        AppInputText,
        SwitchInput,
        FormLabel,
        PageTab,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            adsense: {
                allowedService: undefined,
                clientId: undefined,
                banner01: undefined,
            },
        }
    },
    created() {
        this.adsense = {
            allowedService: this.config.allowedAdsense,
            clientId: this.config.adsenseClientId,
            banner01: this.config.adsenseBanner01,
            banner02: this.config.adsenseBanner02,
            banner03: this.config.adsenseBanner03,
        }
    },
}
</script>
