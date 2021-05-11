<template>
    <PopupWrapper name="share-invoice">
        <!--Title-->
        <PopupHeader :title="$t('in.share.share_invoice')" icon="share" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
			<TitlePreview
				v-if="pickedItem"
				class="item-thumbnail"
				icon="file-text"
				:title="pickedItem.name"
				:subtitle="$t('in.invoice') + ' - ' + pickedItem.invoice_number"
			/>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

				<ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
					<input v-model="shareOptions.email" :class="{'is-error': errors[0]}" type="text" class="focus-border-theme" :placeholder="$t('in.share.type_email')">
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>

            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="popup-button" @click.native="$closePopup()" button-style="secondary">
                {{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase class="popup-button" @click.native="submitShareOptions" button-style="theme" :loading="isLoading" :disabled="isLoading">
                {{ $t('in.share.submit_share') }}
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
import TitlePreview from '@/components/FilesView/TitlePreview'
import ActionButton from '@/components/Others/ActionButton'
import ButtonBase from '@/components/FilesView/ButtonBase'
import {required} from 'vee-validate/dist/rules'
import {events} from '@/bus'
import axios from 'axios'

export default {
    name: 'ShareInvoicePopup',
    components: {
        ValidationProvider,
        ValidationObserver,
		TitlePreview,
        ActionButton,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        required,
    },
    data() {
        return {
            shareOptions: {
                email: undefined
            },
            pickedItem: undefined,
            isLoading: false,
        }
    },
    methods: {
        async submitShareOptions() {

            // Validate fields
            const isValid = await this.$refs.shareForm.validate()

            if (!isValid) return

            this.isLoading = true

            // Send request to get share link
            axios
                .post(`/api/oasis/invoices/${this.pickedItem.id}/share`,
                    this.shareOptions
                )
                .then(() => {
                	this.$closePopup()

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('in.share.invoice_sended'),
					})
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
        }
    },
    mounted() {

        // Show popup
        events.$on('popup:open', args => {

            if (args.name !== 'share-invoice') return

            this.pickedItem = args.item
        })

        // Close popup
        events.$on('popup:close', () => {

            // Restore data
            setTimeout(() => {
                this.shareOptions = {
                    email: undefined
                }
            }, 150)
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_inapp-forms.scss";
@import '@assets/vuefilemanager/_forms';

.more-options {
    margin-bottom: 10px;
}

.input-wrapper {

    &.password {
        margin-top: -10px;
    }
}

.item-thumbnail {
    padding: 0 20px 20px;
}

</style>
