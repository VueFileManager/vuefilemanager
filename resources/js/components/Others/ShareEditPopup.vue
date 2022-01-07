<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_edit.title')" icon="share" />

        <!--Content-->
        <PopupContent v-if="pickedItem && pickedItem.data.relationships.shared">

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

			<!--Get share link-->
            <div v-if="! sendToRecipientsMenu || (sendToRecipientsMenu && isEmailSended)" class="input-wrapper copy-input">
                <label class="input-label">{{ $t('shared_form.label_share_vie_email') }}:</label>
                <CopyShareLink :item="pickedItem" />
            </div>

			<!--Share via email-->
            <ValidationObserver @submit.prevent v-if="sendToRecipientsMenu && !isEmailSended" v-slot="{ invalid }" ref="shareEmail" tag="form" class="form-wrapper">
                <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
					<AppInputText :error="errors[0]">
						<MultiEmailInput rules="required" v-model="emails" :label="$t('shared_form.label_send_to_recipients')" :isError="errors[0]" />
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent v-if="! sendToRecipientsMenu" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Permission Select-->
				<ValidationProvider v-if="isFolder" tag="div" mode="passive" name="Permission" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('shared_form.label_permission')" :error="errors[0]">
						<SelectInput v-model="shareOptions.permission" :options="$translateSelectOptions(permissionOptions)" :default="shareOptions.permission" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]"/>
					</AppInputText>
				</ValidationProvider>

				<!--Password Switch-->
				<div>
					<AppInputSwitch :title="$t('shared_form.label_password_protection')" :description="$t('popup.share.password_description')">
						<SwitchInput v-model="shareOptions.isProtected" class="switch" :state="shareOptions.isProtected" />
					</AppInputSwitch>

					<ActionButton v-if="(pickedItem.data.relationships.shared.data.attributes.protected && canChangePassword) && shareOptions.isProtected" @click.native="changePassword" class="mb-6 -mt-4">
                        {{ $t('popup_share_edit.change_pass') }}
                    </ActionButton>

					<!--Set password-->
					<ValidationProvider v-if="shareOptions.isProtected && ! canChangePassword" tag="div" mode="passive" name="Password" rules="required" v-slot="{ errors }">
						<AppInputText :error="errors[0]" class="-mt-2">
							<input v-model="shareOptions.password" :class="{'border-red': errors[0]}" type="text" class="focus-border-theme input-dark" :placeholder="$t('page_sign_in.placeholder_password')">
						</AppInputText>
					</ValidationProvider>
				</div>

				<!--Expiration switch-->
				<div>
					<AppInputSwitch :title="$t('expiration')" :description="$t('popup.share.expiration_description')">
						<SwitchInput v-model="shareOptions.expiration" class="switch" :state="shareOptions.expiration ? 1 : 0" />
					</AppInputSwitch>

					<!--Set expiration-->
					<AppInputText v-if="shareOptions.expiration" class="-mt-2" :is-last="true">
						<SelectBoxInput v-model="shareOptions.expiration" :data="$translateSelectOptions(expirationList)" :value="shareOptions.expiration" class="box" />
					</AppInputText>
				</div>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase v-if="! sendToRecipientsMenu || (sendToRecipientsMenu && !isEmailSended)"
                    class="popup-button"
                    @click.native="destroySharing"
                    :button-style="destroyButtonStyle"
                    :loading="isDeleting"
                    :disabled="isDeleting"
            >{{ destroyButtonText }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="updateShareOptions"
                    button-style="theme"
                    :loading="isLoading"
                    :disabled="isLoading"
            >{{ secondButtonText }}
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
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import MultiEmailInput from '/resources/js/components/Others/Forms/MultiEmailInput'
    import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
    import ActionButton from '/resources/js/components/Others/ActionButton'
    import CopyShareLink from '/resources/js/components/Others/Forms/CopyShareLink'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '/resources/js/bus'
    import axios from 'axios'

    export default {
        name: 'ShareEditPopup',
        components: {
			AppInputText,
			AppInputSwitch,
            ValidationProvider,
            ValidationObserver,
            SelectBoxInput,
            ThumbnailItem,
            ActionButton,
            PopupWrapper,
            PopupActions,
            PopupContent,
            PopupHeader,
            SelectInput,
            MultiEmailInput,
            SwitchInput,
            ButtonBase,
            CopyShareLink,
            required,
            InfoBox,
        },
        computed: {
            ...mapGetters([
                'permissionOptions',
                'expirationList',
                'currentFolder',
                'user',
            ]),
            isFolder() {
                return this.pickedItem && this.pickedItem.data.type === 'folder'
            },
            destroyButtonText() {
                if(! this.sendToRecipientsMenu)
                    return this.isConfirmedDestroy ? this.$t('popup_share_edit.confirm') : this.$t('popup_share_edit.stop')

                if(this.sendToRecipientsMenu)
                    return this.$t('popup_share_edit.go_back')
            },
            destroyButtonStyle() {
				if(! this.sendToRecipientsMenu)
					return this.isConfirmedDestroy ? 'danger-solid' : 'secondary'

				if(this.sendToRecipientsMenu)
					return 'secondary'
            },
            secondButtonText(){
                if(! this.sendToRecipientsMenu)
                    return this.$t('popup_share_edit.save')

                if(this.sendToRecipientsMenu)
                    return this.isEmailSended ? this.$t('shared_form.button_done') : this.$t('popup_share_edit.send_to_recipients')
            },
        },
		watch: {
			'shareOptions.expiration': function (val) {
				if (!val) {
					this.shareOptions.expiration = undefined
				}
			}
		},
        data() {
            return {
                sendToRecipientsMenu: false,
                isConfirmedDestroy: false,
                canChangePassword: false,
                shareOptions: undefined,
                pickedItem: undefined,
                isMoreOptions: false,
                isEmailSended:false,
                isDeleting: false,
                emails:undefined,
                isLoading: false,
            }
        },
        methods: {
            changePassword() {
                this.canChangePassword = false
            },
            async sendViaEmail() {

                // Validate email field
                const isValid = await this.$refs.shareEmail.validate();

                if (!isValid) return;

                this.isLoading = true

                axios.
                    post(`/api/share/${this.shareOptions.token}/email`, {
                        emails: this.emails
                    })
                    .catch(() => {
                        this.$isSomethingWrong()
                    })
                    .finally(() => {

                        this.isEmailSended = true
                        this.isLoading = false
                    })
            },
            async destroySharing() {

                if(this.sendToRecipientsMenu) {
                    this.sendToRecipientsMenu = false
                    return
                }

                // Set confirm button
                if (! this.isConfirmedDestroy) {

                    this.isConfirmedDestroy = true
                } else {

                    // Start deleting spinner button
                    this.isDeleting = true

                    // Send delete request
                    await this.$store.dispatch('shareCancel' , this.pickedItem)
                        .then(() => {

                            // End deleting spinner button
                            setTimeout(() => this.isDeleting = false, 150)

                            this.$closePopup()
                        })
                        .catch(() => {

                            // End deleting spinner button
                            this.isDeleting = false
                        })
                }
            },
            async updateShareOptions() {

                // If is open send share via email 
                if(this.sendToRecipientsMenu && !this.isEmailSended) {
                    await this.sendViaEmail()
                    return
                }

                // Is is open send share via email and email was already sended
                if(this.sendToRecipientsMenu && this.isEmailSended){
                    events.$emit('popup:close')
                    return
                }

                // If shared was generated, then close popup
                if (this.isGeneratedShared) {

                    events.$emit('popup:close')
                    return
                }

                // Validate fields
                const isValid = await this.$refs.shareForm.validate();

                if (!isValid) return;

                this.isLoading = true

                // Send request to get share link
                axios
                    .post('/api/share/' + this.shareOptions.token, {
                        permission: this.shareOptions.permission,
                        protected: this.shareOptions.isProtected,
                        expiration: this.shareOptions.expiration,
                        password: this.shareOptions.password ? this.shareOptions.password : undefined,
                        _method: 'patch'
                    })
                    .then(response => {

                        // Update shared data
                        this.$store.commit('UPDATE_SHARED_ITEM', response.data.data.attributes)

                        events.$emit('popup:close')
                    })
                    .catch(() => {
                        this.$isSomethingWrong()
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
        },
        mounted() {
            events.$on('emailsInputValues', emails => this.emails = emails)

            // Show popup
            events.$on('popup:open', args => {

                if (args.name !== 'share-edit') return

                // Store picked item
                this.pickedItem = args.item

                // Store shared options
                this.shareOptions = {
                    id: args.item.data.relationships.shared.data.id,
                    token: args.item.data.relationships.shared.data.attributes.token,
                    expiration: args.item.data.relationships.shared.data.attributes.expire_in,
                    isProtected: args.item.data.relationships.shared.data.attributes.protected,
                    permission: args.item.data.relationships.shared.data.attributes.permission,
                    password: undefined,
                }

                if (args.sentToEmail)
                    this.sendToRecipientsMenu = true
                    this.isEmailSended = false

                this.canChangePassword = args.item.data.relationships.shared.data.attributes.protected
            })

            events.$on('popup:close', () => {

                // Restore data
                setTimeout(() => {
                    this.sendToRecipientsMenu = false
                    this.isConfirmedDestroy = false
                    this.canChangePassword = false
                    this.shareOptions = undefined
                    this.pickedItem = undefined
                    this.isEmailSended = false
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

        &.copy-input {
            padding: 0px 20px;
        }
    }

    .change-password {
        opacity: 0.7;
        text-decoration: underline;
		margin-top: -8px;
    }

    .item-thumbnail {
        margin-bottom: 20px;
    }
</style>
