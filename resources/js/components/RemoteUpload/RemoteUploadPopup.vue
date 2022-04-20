<template>
    <PopupWrapper name="remote-upload">
        <PopupHeader :title="$t('upload_files_remotely')" icon="remote-upload" />

        <PopupContent>
			<ValidationObserver @submit.prevent ref="createForm" v-slot="{ invalid }" tag="form">
                <ValidationProvider tag="div" mode="passive" name="Remote Links" rules="required" v-slot="{ errors }">
					<AppInputText
						:title="$t('remote_links')"
						:description="$t('remote_links_help')"
						:error="errors[0]"
						:is-last="true"
					>
						<textarea
							v-model="links"
							class="focus-border-theme input-dark whitespace-nowrap"
							rows="6"
							:placeholder="$t('paste_remote_links_here')"
							:class="{ '!border-rose-600': errors[0] }"
							ref="textarea"
						>
						</textarea>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="upload" button-style="theme" :loading="loading">
                {{ $t('upload') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupContent from '../Popups/Components/PopupContent'
import PopupActions from '../Popups/Components/PopupActions'
import PopupHeader from '../Popups/Components/PopupHeader'
import AppInputText from '../Forms/Layouts/AppInputText'
import { required } from 'vee-validate/dist/rules'
import ButtonBase from '../UI/Buttons/ButtonBase'
import { events } from '../../bus'

export default {
    name: 'RemoteUploadPopup',
    components: {
		ValidationProvider,
		ValidationObserver,
		required,
        PopupWrapper,
        PopupContent,
        PopupHeader,
        PopupActions,
        AppInputText,
        ButtonBase,
    },
    data() {
        return {
            links: undefined,
            loading: false,
        }
    },
    methods: {
        async upload() {
			// Validate fields
			const isValid = await this.$refs.createForm.validate()

			if (!isValid) return

            this.loading = true

			let route = this.$store.getters.sharedDetail
				? `/api/editor/upload/remote/${this.$router.currentRoute.params.token}`
				: '/api/upload/remote'

			let parentId = this.$store.getters.currentFolder
				? this.$store.getters.currentFolder.data.id
				: undefined

			axios
				.post(route, {
					urls: this.links
						.replace(/^(?=\n)$|^\s*|\s*$|\n\n+/gm, "")
						.split(/\r?\n/),
					parent_id: parentId,
				})
				.then(() => {
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('remote_download_submitted'),
					})

					events.$emit('popup:close')
				})
				.catch((error) => {
					if (error.response.status === 422) {
						this.$refs.createForm.setErrors({
							'Remote Links': error.response.data.message,
						})
					}

					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('popup_error.title'),
					})
				})
				.finally(() => {
					this.loading = false
				})
        },
    },
    mounted() {
        events.$on('popup:open', (args) => {
            if (args.name !== 'remote-upload') return

            this.links = undefined

            this.$nextTick(() => {
				setTimeout(() => this.$refs.textarea.focus(), 100)
            })
        })
    },
}
</script>
