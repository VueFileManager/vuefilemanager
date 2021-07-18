<template>
    <PopupWrapper name="create-personal-token">

        <PopupHeader :title="$t('Create Personal Token')" icon="key" />

        <PopupContent>
             <ValidationObserver v-if="! token" @submit.prevent="createTokenForm" ref="createToken" v-slot="{ invalid }" tag="form" class="form-wrapper">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Token Name" rules="required" v-slot="{ errors }">
                    <label class="input-label"> {{ $t('Token Name') }}:</label>
                    <input v-model="name" :class="{'is-error': errors[0]}" type="text" ref="input" class="focus-border-theme" :placeholder="$t('Type token name...')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <div v-if="token" class="form-wrapper">
				<div v-if="token">
					<div class="input-wrapper">
						<label class="input-label">{{ $t('Your Personal Access Token') }}:</label>
						<CopyInput size="small" :str="token['plainTextToken']" />
					</div>
				</div>
				<InfoBox style="margin-bottom: 0; margin-top: 20px">
                	<p v-html="$t('Make sure to <b class=\'text-theme\'>copy your personal access token now</b>. You won’t be able to see it again!')"></p>
            	</InfoBox>
            </div>
        </PopupContent>

        <PopupActions v-if="! token">
            <ButtonBase
				class="popup-button"
				@click.native="$closePopup()"
				button-style="secondary"
			>
                {{ $t('global.cancel') }}
            </ButtonBase>
            <ButtonBase
				class="popup-button"
				@click.native="createTokenForm"
				button-style="theme"
				:loading="isLoading"
				:disabled="isLoading"
			>
               {{ $t('Create Token') }}
            </ButtonBase>
        </PopupActions>

        <PopupActions v-if="token">
            <ButtonBase
				class="popup-button"
				@click.native="closePopup"
				button-style="theme"
			>
                {{ $t('shared_form.button_done') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
import PopupActions from '@/components/Others/Popup/PopupActions'
import PopupContent from '@/components/Others/Popup/PopupContent'
import PopupHeader from '@/components/Others/Popup/PopupHeader'
import CopyInput from '@/components/Others/Forms/CopyInput'
import ButtonBase from '@/components/FilesView/ButtonBase'
import InfoBox from '@/components/Others/Forms/InfoBox'
import {required} from 'vee-validate/dist/rules'
import axios from 'axios'

export default {
	name: "CreatePersonaTokenPopup",
	components: {
		ValidationProvider,
		ValidationObserver,
		PopupWrapper,
		PopupActions,
		PopupContent,
		PopupHeader,
		ButtonBase,
		CopyInput,
		required,
		InfoBox,
	},
	data() {
		return {
			isLoading: false,
			name: undefined,
			token: undefined
		}
	},
	methods: {
		async createTokenForm() {

			const isValid = await this.$refs.createToken.validate()

			if (!isValid) return

			this.isLoading = true

			axios
				.post('/api/user/token/create', {
					name: this.name
				})
				.then(response => {
					this.token = response.data
				})
				.catch(() => this.$isSomethingWrong())
				.finally(() => {
					this.isLoading = false
					this.name = undefined
				})
		},
		closePopup() {
			this.$closePopup()
			this.token = undefined
		}
	}
}
</script>

<style lang="scss" scoped>
	@import "@assets/vuefilemanager/_inapp-forms.scss";
	@import '@assets/vuefilemanager/_forms';
</style>
