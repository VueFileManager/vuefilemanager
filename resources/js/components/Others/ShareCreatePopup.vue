<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_create.title', {item: itemTypeTitle})" icon="share" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata" />

            <!--Form to set sharing-->
            <ValidationObserver v-if="! isGeneratedShared" @submit.prevent ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Permission Select-->
				<ValidationProvider v-if="isFolder" tag="div" mode="passive" name="Permission" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('shared_form.label_permission')" :error="errors[0]">
						<SelectInput v-model="shareOptions.permission" :options="$translateSelectOptions(permissionOptions)" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]" />
					</AppInputText>
				</ValidationProvider>

                <!--Password Switch-->
				<div>
					<AppInputSwitch :title="$t('shared_form.label_password_protection')" :description="$t('popup.share.password_description')">
						<SwitchInput v-model="shareOptions.isPassword" class="switch" :state="0" />
					</AppInputSwitch>

					<!--Set password-->
					<ValidationProvider v-if="shareOptions.isPassword" tag="div" mode="passive" name="Password" rules="required" v-slot="{ errors }">
						<AppInputText :error="errors[0]" class="-mt-2">
							<input v-model="shareOptions.password" :class="{'border-red-700': errors[0]}" type="text" class="focus-border-theme input-dark" :placeholder="$t('page_sign_in.placeholder_password')">
						</AppInputText>
					</ValidationProvider>
				</div>

                <!--Expiration switch-->
				<div>
					<AppInputSwitch :title="$t('expiration')" :description="$t('popup.share.expiration_description')">
						<SwitchInput v-model="isExpiration" class="switch" :state="0" />
					</AppInputSwitch>

                    <!--Set expiration-->
					<AppInputText v-if="isExpiration" class="-mt-2">
                        <SelectBoxInput v-model="shareOptions.expiration" :data="$translateSelectOptions(expirationList)" class="box" />
					</AppInputText>
				</div>

                <!--Send on emails switch-->
				<div>
					<AppInputSwitch :title="$t('popup.share.email_send')" :description="$t('popup.share.email_description')">
						<SwitchInput v-model="isEmailSharing" class="switch" :state="0" />
					</AppInputSwitch>

                    <!--Set expiration-->
                    <ValidationProvider v-if="isEmailSharing" tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }" class="-mt-2">
						<MultiEmailInput rules="required" v-model="shareOptions.emails" :label="$t('shared_form.recipients_label')" :isError="errors[0]" />
					</ValidationProvider>
				</div>
            </ValidationObserver>

            <!--Copy generated link-->
			<AppInputText v-if="isGeneratedShared" :title="$t('shared_form.label_share_vie_email')" class="px-6" :is-last="true">
				<CopyShareLink size="small" :item="pickedItem" />
			</AppInputText>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase v-if="! isGeneratedShared" class="popup-button" @click.native="$closePopup()" button-style="secondary">
                {{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase class="popup-button" @click.native="submitShareOptions" button-style="theme" :loading="isLoading" :disabled="isLoading">
                {{ submitButtonText }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from "../Admin/AppInputText";
import AppInputSwitch from "../Admin/AppInputSwitch";
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import SelectBoxInput from '/resources/js/components/Others/Forms/SelectBoxInput'
import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
import MultiEmailInput from '/resources/js/components/Others/Forms/MultiEmailInput'
import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
import ActionButton from '/resources/js/components/Others/ActionButton'
import CopyShareLink from '/resources/js/components/Others/Forms/CopyShareLink'
import TabWrapper from '/resources/js/components/Others/TabWrapper'
import TabOption from '/resources/js/components/Others/TabOption'
import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
import {LinkIcon, MailIcon} from 'vue-feather-icons'
import {required} from 'vee-validate/dist/rules'
import {mapGetters} from 'vuex'
import {events} from '/resources/js/bus'
import axios from 'axios'

export default {
    name: 'ShareCreatePopup',
    components: {
        ValidationProvider,
        ValidationObserver,
		AppInputText,
		AppInputSwitch,
        SelectBoxInput,
        ThumbnailItem,
        ActionButton,
        PopupWrapper,
        PopupActions,
        TabWrapper,
        TabOption,
        PopupContent,
        PopupHeader,
        MultiEmailInput,
        SelectInput,
        SwitchInput,
        ButtonBase,
        CopyShareLink,
        MailIcon,
        required,
        LinkIcon,
        InfoBox
    },
    computed: {
        ...mapGetters([
            'permissionOptions',
            'expirationList'
        ]),
        itemTypeTitle() {
            return this.pickedItem && this.pickedItem.data.type === 'folder' ? this.$t('types.folder') : this.$t('types.file')
        },
        isFolder() {
            return this.pickedItem && this.pickedItem.data.type === 'folder'
        },
        submitButtonText() {
            return this.isGeneratedShared ? this.$t('shared_form.button_done') : this.$t('shared_form.button_generate')
        }
    },
	watch: {
		isExpiration(val) {
			if (! val) this.shareOptions.expiration = undefined
		}
	},
    data() {
        return {
        	isExpiration: false,
        	isEmailSharing: false,
            shareOptions: {
                isPassword: false,
                expiration: undefined,
                password: undefined,
                permission: undefined,
                type: undefined,
                id: undefined,
                emails: undefined
            },
            pickedItem: undefined,
            isGeneratedShared: false,
            isLoading: false,
            sharedViaEmail: false
        }
    },
    methods: {
        async submitShareOptions() {

            // If shared was generated, then close popup
            if (this.isGeneratedShared) {
                events.$emit('popup:close')

                return
            }

            // Validate fields
            const isValid = await this.$refs.shareForm.validate()

            if (!isValid) return

            this.isLoading = true

            // Send request to get share link
            axios
                .post(`/api/share`, this.shareOptions)
                .then(response => {

                    // End loading
                    this.isGeneratedShared = true

                    this.$store.commit('UPDATE_SHARED_ITEM', response.data)

					this.pickedItem.data.relationships.shared = response.data
                })
                .catch(() => {
                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })

                    // End loading
                    this.isLoading = false
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    },
    mounted() {

        events.$on('emailsInputValues', emails => this.shareOptions.emails = emails)

        // Show popup
        events.$on('popup:open', args => {

            if (args.name !== 'share-create') return

            // Store picked item
            this.pickedItem = args.item

            this.shareOptions.type = args.item.data.type
            this.shareOptions.id = args.item.data.id
        })

        // Close popup
        events.$on('popup:close', () => {

            // Restore data
            setTimeout(() => {
					this.isGeneratedShared = false
					this.isExpiration = false
					this.isEmailSharing = false
					this.shareOptions = {
						isPassword: false,
						expiration: undefined,
						password: undefined,
						permission: undefined,
						type: undefined,
						id: undefined,
						emails: undefined
					}
            }, 150)
        })
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

.input-wrapper {

    &.password {
        margin-top: -10px;
    }
}

.item-thumbnail {
    margin-bottom: 20px;
}

</style>
