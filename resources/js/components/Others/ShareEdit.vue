<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_edit.title')" icon="share" />

        <!--Content-->
        <PopupContent v-if="pickedItem && pickedItem.shared">

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!-- Infobox for successfull sended email -->
            <InfoBox v-if="sendToRecipientsMenu && isEmailSended" class="info-box-wrapper">
                <p v-html="$t('shared_form.email_successfully_send_message')"></p>
            </InfoBox>

            <div v-if="! sendToRecipientsMenu || (sendToRecipientsMenu && isEmailSended)" class="input-wrapper copy-input">
                <label class="input-label">{{ $t('shared_form.label_share_vie_email') }}:</label>
                <CopyInput size="small" :item="pickedItem" />
            </div>

            <ValidationObserver @submit.prevent v-if="sendToRecipientsMenu && !isEmailSended" v-slot="{ invalid }" ref="shareEmail" tag="form" class="form-wrapper">

                <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                    <MultiEmailInput  rules="required" v-model="emails" :label="$t('shared_form.label_send_to_recipients')" :isError="errors[0]" />
                </ValidationProvider>

            </ValidationObserver>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent v-if="! sendToRecipientsMenu" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('shared_form.label_permission') }}:</label>
                    <SelectInput v-model="shareOptions.permission" :options="$translateSelectOptions(permissionOptions)" :default="shareOptions.permission" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_password_protection') }}:</label>
                        <SwitchInput v-model="shareOptions.isProtected" :state="shareOptions.isProtected" class="switch"/>
                    </div>
                    <ActionButton v-if="(pickedItem.shared.is_protected && canChangePassword) && shareOptions.isProtected" @click.native="changePassword" class="change-password">
                        {{ $t('popup_share_edit.change_pass') }}
                    </ActionButton>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isProtected && ! canChangePassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" class="focus-border-theme" :placeholder="$t('page_sign_in.placeholder_password')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--More options-->
                <div class="more-options" v-if="isMoreOptions">

                    <!--Set expiration-->
                    <div class="input-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_expiration') }}:</label>
                        <SelectBoxInput v-model="shareOptions.expiration" :data="$translateSelectOptions(expirationList)" :value="shareOptions.expiration" class="box"/>
                    </div>
                </div>

                <ActionButton @click.native="moreOptions" :icon="isMoreOptions || shareOptions.expiration ? 'x' : 'pencil-alt'">
                    {{ moreOptionsTitle }}
                </ActionButton>

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
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import SelectBoxInput from '@/components/Others/Forms/SelectBoxInput'
    import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/Others/Popup/PopupActions'
    import PopupContent from '@/components/Others/Popup/PopupContent'
    import PopupHeader from '@/components/Others/Popup/PopupHeader'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import MultiEmailInput from '@/components/Others/Forms/MultiEmailInput'
    import ThumbnailItem from '@/components/Others/ThumbnailItem'
    import ActionButton from '@/components/Others/ActionButton'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'ShareEdit',
        components: {
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
            CopyInput,
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
                return this.pickedItem && this.pickedItem.type === 'folder'
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
            isSharedLocation() {
                return this.currentFolder && this.currentFolder.location === 'shared'
            },
            moreOptionsTitle() {
                return this.isMoreOptions ? this.$t('shared_form.button_close_options') : this.$t('shared_form.button_more_options')
            }
        },
        data() {
            return {
                sendToRecipientsMenu: false,
                isConfirmedDestroy: false,
                canChangePassword: false,
                shareOptions: undefined,
                pickedItem: undefined,
                emails:undefined,
                isMoreOptions: false,
                isEmailSended:false,
                isDeleting: false,
                isLoading: false,
            }
        },
        methods: {
            moreOptions() {
                this.isMoreOptions = ! this.isMoreOptions

                if (! this.isMoreOptions)
                    this.shareOptions.expiration = undefined
            },
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

                        // End loading
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
                        .then((response) => {

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
                    this.sendViaEmail()
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

            this.sendToRecipientsMenu = false

            events.$on('emailsInputValues', (emails) => {
                this.emails = emails
            })

            // Show popup
            events.$on('popup:open', args => {

                if (args.name !== 'share-edit') return

                // Store picked item
                this.pickedItem = args.item

                // Store shared options
                this.shareOptions = {
                    token: args.item.shared.token,
                    expiration: args.item.shared.expire_in,
                    isProtected: args.item.shared.is_protected,
                    permission: args.item.shared.permission,
                    password: undefined,
                }

                if (args.item.shared.expire_in)
                    this.isMoreOptions = true

                if (args.sentToEmail)
                    this.sendToRecipientsMenu = true
                    this.isEmailSended = false

                this.canChangePassword = args.item.shared.is_protected
            })

            // Close popup
            events.$on('popup:close', () => {

                // Restore data
                setTimeout(() => {
                    this.sendToRecipientsMenu = false
                    this.isEmailSended = false
                    this.isConfirmedDestroy = false
                    this.canChangePassword = false
                    this.pickedItem = undefined
                    this.shareOptions = undefined
                }, 150)
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vuefilemanager/_inapp-forms.scss";
    @import '@assets/vuefilemanager/_forms';

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
    }

    .item-thumbnail {
        margin-bottom: 20px;
    }
</style>
