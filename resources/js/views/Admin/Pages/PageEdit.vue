<template>
    <div>
        <div v-if="!isLoading && page" class="card shadow-card">
            <FormLabel>
                {{ page.data.attributes.title }}
            </FormLabel>
            <AppInputSwitch
                :title="$t('visibility')"
                :description="$t('admin_pages.form.visibility_help')"
            >
                <SwitchInput @input="changeStatus" class="switch" :state="page.data.attributes.visibility" />
            </AppInputSwitch>
            <AppInputText :title="$t('title')">
                <input
                    @input="$updateText('/admin/pages/' + $route.params.slug, 'title', page.data.attributes.title)"
                    v-model="page.data.attributes.title"
                    :placeholder="$t('admin_pages.form.title_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
            <AppInputText :title="$t('slug')">
                <input v-model="page.data.attributes.slug" type="text" class="focus-border-theme input-dark" disabled />
            </AppInputText>
            <AppInputText :title="$t('content')" :is-last="true">
                <textarea
                    @input="$updateText('/admin/pages/' + $route.params.slug, 'content', page.data.attributes.content)"
                    v-model="page.data.attributes.content"
                    :placeholder="$t('admin_pages.form.content_plac')"
                    class="focus-border-theme input-dark"
                    rows="18"
                ></textarea>
            </AppInputText>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner />
        </div>
    </div>
</template>

<script>
import AppInputSwitch from '../../../components/Forms/Layouts/AppInputSwitch'
import AppInputText from '../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import FormLabel from '../../../components/UI/Labels/FormLabel'
import { required } from 'vee-validate/dist/rules'
import SwitchInput from '../../../components/Inputs/SwitchInput'
import MobileHeader from '../../../components/Mobile/MobileHeader'
import SectionTitle from '../../../components/UI/Labels/SectionTitle'
import ButtonBase from '../../../components/UI/Buttons/ButtonBase'
import Spinner from '../../../components/UI/Others/Spinner'
import axios from 'axios'

export default {
    name: 'PageEdit',
    components: {
        AppInputSwitch,
        AppInputText,
        ValidationProvider,
        ValidationObserver,
        FormLabel,
        SectionTitle,
        MobileHeader,
        SwitchInput,
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
            this.$updateText('/admin/pages/' + this.$route.params.slug, 'visibility', val)
        },
    },
    created() {
        axios.get('/api/admin/pages/' + this.$route.params.slug).then((response) => {
            this.page = response.data
            this.isLoading = false
        })
    },
}
</script>
