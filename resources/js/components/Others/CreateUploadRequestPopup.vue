<template>
    <PopupWrapper name="create-file-request">
        <!--Title-->
        <PopupHeader :title="$t('Create File Request')" icon="upload" />

		<!--Content-->
        <PopupContent>
            <!--Item Thumbnail-->
            <ThumbnailItem v-if="pickedItem" class="mb-5" :item="pickedItem" />

			<!--Form to set upload request-->
            <ValidationObserver
				v-if="!generatedUploadRequest"
				@submit.prevent="createUploadRequest"
				ref="createForm"
				v-slot="{ invalid }"
				tag="form"
			>
                <AppInputSwitch
					:title="$t('Send Request by Email')"
					:description="$t('Send your file request on recipients email')"
				>
                    <SwitchInput v-model="shareViaEmail" :state="shareViaEmail" class="switch" />
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
                    <AppInputText :error="errors[0]" class="-mt-2">
                        <input
							v-model="form.email"
							:class="{ 'border-red': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('Type email...')"
						/>
                    </AppInputText>
                </ValidationProvider>

				<!--Set note-->
                <ValidationProvider tag="div" mode="passive" name="Note" v-slot="{ errors }">
                    <AppInputText :title="$t('Message (optional)')" :description="$t('This message will be showed for your email recipient or in the upload page.')" :error="errors[0]" :is-last="true">
                        <textarea
							v-model="form.notes"
							rows="2"
							:class="{ 'border-red': errors[0] }"
							type="text"
							ref="input"
							class="focus-border-theme input-dark"
							:placeholder="$t('Type message for recipient...')"
						></textarea>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>

			<!--Copy generated link-->
            <AppInputText v-if="generatedUploadRequest" :title="$t('Copy your upload request link')" :is-last="true">
                <CopyInput :str="generatedUploadRequest.data.attributes.url" />
            </AppInputText>
        </PopupContent>

		<!--Actions-->
        <PopupActions v-if="!generatedUploadRequest">
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
			>{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="createUploadRequest" :loading="isLoading" button-style="theme"
			>{{ $t('Create Request') }}
            </ButtonBase>
        </PopupActions>

		<!--Actions-->
        <PopupActions v-if="generatedUploadRequest">
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="theme"
			>{{ $t('shared_form.button_done') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import AppInputSwitch from '../Admin/AppInputSwitch'
import {required} from 'vee-validate/dist/rules'
import ButtonBase from '../FilesView/ButtonBase'
import AppInputText from '../Admin/AppInputText'
import PopupWrapper from './Popup/PopupWrapper'
import PopupActions from './Popup/PopupActions'
import PopupContent from './Popup/PopupContent'
import PopupHeader from './Popup/PopupHeader'
import SwitchInput from './Forms/SwitchInput'
import ThumbnailItem from './ThumbnailItem'
import CopyInput from './Forms/CopyInput'
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
			},
			generatedUploadRequest: undefined,
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
				.post(`/api/upload-request`, this.form)
				.then((response) => {
					this.generatedUploadRequest = response.data
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
			if (args.name === 'create-file-request') this.pickedItem = args.item
			this.form.folder_id = args.item.data.id
		})

		// Close popup
		events.$on('popup:close', () => {

			// Restore data
			setTimeout(() => {
				this.generatedUploadRequest = undefined
				this.pickedItem = undefined

				this.shareViaEmail = false

				this.form = {
					email: undefined,
					notes: undefined,
					folder_id: undefined,
				}
			}, 150)
		})
	},
}
</script>
