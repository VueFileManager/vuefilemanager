<template>
    <PopupWrapper name="create-file-request">
        <!--Title-->
        <PopupHeader :title="$t('create_file_request')" icon="upload" />

		<!--Content-->
        <PopupContent>
            <!--Item Thumbnail-->
            <ThumbnailItem v-if="pickedItem" class="mb-5" :item="pickedItem" />

			<!--Form to set upload request-->
            <ValidationObserver
				v-if="!uploadRequest"
				@submit.prevent="createUploadRequest"
				ref="createForm"
				v-slot="{ invalid }"
				tag="form"
			>
				<!--Set name-->
                <ValidationProvider
					tag="div"
					mode="passive"
					name="Name"
					v-slot="{ errors }"
				>
                    <AppInputText :title="$t('folder_name_optional')" :description="$t('folder_name_optional_description')" :error="errors[0]">
                        <input
							v-model="form.name"
							:class="{ '!border-rose-600': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('type_name_')"
						/>
                    </AppInputText>
                </ValidationProvider>

				<!--Set note-->
                <ValidationProvider tag="div" mode="passive" name="Note" v-slot="{ errors }">
                    <AppInputText :title="$t('message_optional')" :description="$t('message_optional_description')" :error="errors[0]">
                        <textarea
							v-model="form.notes"
							rows="2"
							:class="{ '!border-rose-600': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('message_for_recipient')"
						></textarea>
                    </AppInputText>
                </ValidationProvider>

				<!--Send Request by Email-->
                <AppInputSwitch
					:title="$t('send_request_by_email')"
					:description="$t('send_request_by_email_description')"
					:is-last="! shareViaEmail"
				>
                    <SwitchInput v-model="shareViaEmail" :state="shareViaEmail" />
                </AppInputSwitch>

				<!--Set email-->
                <ValidationProvider
					v-if="shareViaEmail"
					tag="div"
					mode="passive"
					name="Email"
					rules="required"
					v-slot="{ errors }"
				>
                    <AppInputText :error="errors[0]" class="-mt-2" :is-last="true">
                        <input
							v-model="form.email"
							:class="{ '!border-rose-600': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('type_email_')"
						/>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>

			<!--Copy generated link-->
            <AppInputText v-if="uploadRequest" :title="$t('copy_upload_request_link')" :is-last="true">
                <CopyInput :str="uploadRequestURL" />
            </AppInputText>
        </PopupContent>

		<!--Actions-->
        <PopupActions v-if="!uploadRequest">
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
			>{{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="createUploadRequest" :loading="isLoading" button-style="theme"
			>{{ $t('create_request') }}
            </ButtonBase>
        </PopupActions>

		<!--Actions-->
        <PopupActions v-if="uploadRequest">
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="theme"
			>{{ $t('awesome_iam_done') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import {required} from 'vee-validate/dist/rules'
import ButtonBase from '../UI/Buttons/ButtonBase'
import AppInputText from '../Forms/Layouts/AppInputText'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupActions from '../Popups/Components/PopupActions'
import PopupContent from '../Popups/Components/PopupContent'
import PopupHeader from '../Popups/Components/PopupHeader'
import SwitchInput from '../Inputs/SwitchInput'
import ThumbnailItem from '../UI/Entries/ThumbnailItem'
import CopyInput from '../Inputs/CopyInput'
import {events} from '../../bus'
import axios from 'axios'

export default {
	name: 'CreateUploadRequestPopup',
	components: {
		ValidationProvider,
		ValidationObserver,
		AppInputSwitch,
		ThumbnailItem,
		AppInputText,
		PopupWrapper,
		PopupActions,
		PopupContent,
		SwitchInput,
		PopupHeader,
		ButtonBase,
		CopyInput,
		required,
	},
	data() {
		return {
			form: {
				email: undefined,
				notes: undefined,
				folder_id: undefined,
				name: undefined,
			},
			uploadRequest: undefined,
			uploadRequestURL: undefined,
			shareViaEmail: false,
			pickedItem: undefined,
			isLoading: false,
		}
	},
	methods: {
		async createUploadRequest() {
			// Validate fields
			const isValid = await this.$refs.createForm.validate()

			if (!isValid) return

			this.isLoading = true

			// Send request to get share link
			axios
				.post(`/api/file-request`, this.form)
				.then((response) => {
					this.uploadRequest = response.data

					// Format upload request url
					this.uploadRequestURL = `${this.$store.getters.config.host}/request/${response.data.data.id}/upload`
				})
				.catch(() => {
					events.$emit('alert:open', {
						title: this.$t('popup_error.title'),
						message: this.$t('popup_error.message'),
					})
				})
				.finally(() => {
					this.isLoading = false
				})
		},
	},
	created() {
		events.$on('popup:open', (args) => {
			if (args.name === 'create-file-request') {
				this.pickedItem = args.item
				this.form.folder_id = args.item?.data.id
			}
		})

		// Close popup
		events.$on('popup:close', () => {

			// Restore data
			setTimeout(() => {
				this.uploadRequest = undefined
				this.pickedItem = undefined

				this.shareViaEmail = false

				this.form = {
					name: undefined,
					email: undefined,
					notes: undefined,
					folder_id: undefined,
				}
			}, 150)
		})
	},
}
</script>
