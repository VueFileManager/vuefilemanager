<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader :title="popupTitle" icon="share" />

        <!--Qr Code-->
        <div v-if="pickedItem && activeSection === 'qr-code'">
            <PopupContent class="flex items-center justify-center">
                <div v-if="!qrCode" class="relative my-8">
                    <Spinner />
                </div>
                <div v-if="qrCode" v-html="qrCode" class="my-5 overflow-hidden rounded-xl"></div>
            </PopupContent>

            <PopupActions>
                <ButtonBase class="w-full" @click="showSection(undefined)" button-style="secondary">
                    {{ $t('show_details') }}
                </ButtonBase>
                <ButtonBase class="w-full" @click="$closePopup()" button-style="theme">
                    {{ $t('awesome_iam_done') }}
                </ButtonBase>
            </PopupActions>
        </div>

        <!--Share via email-->
        <div v-if="pickedItem && activeSection === 'email-sharing'">
            <PopupContent>
                <!--Item Thumbnail-->
                <ThumbnailItem class="mb-4" :item="pickedItem" />

                <ValidationObserver @submit.prevent v-slot="{ invalid }" ref="shareEmail" tag="form">
                    <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                        <AppInputText title="Share with" :error="errors[0]" :is-last="true">
                            <MultiEmailInput
                                rules="required"
                                v-model="emails"
                                :label="$t('shared_form.label_send_to_recipients')"
								:is-error="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>
                </ValidationObserver>
            </PopupContent>

            <PopupActions>
                <ButtonBase class="w-full" @click="showSection(undefined)" button-style="secondary">
                    {{ $t('show_details') }}
                </ButtonBase>
                <ButtonBase
                    class="w-full"
                    @click="sendViaEmail"
                    button-style="theme"
                    :loading="isLoading"
                    :disabled="isLoading"
                >
                    {{ $t('send') }}
                </ButtonBase>
            </PopupActions>
        </div>

        <!--Update sharing-->
        <div v-if="pickedItem && !activeSection">
            <PopupContent class="overflow-initial!">
                <!--Item Thumbnail-->
                <ThumbnailItem class="mb-5" :item="pickedItem" />

                <!--Get share link-->
                <AppInputText :title="$t('get_your_link')">
                    <CopyShareLink :item="pickedItem" />
                </AppInputText>

                <ValidationObserver @submit.prevent ref="shareForm" v-slot="{ invalid }" tag="form">
                    <!--Permission Select-->
                    <ValidationProvider
                        v-if="isFolder"
                        tag="div"
                        mode="passive"
                        name="Permission"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :title="$t('permission')" :error="errors[0]">
                            <SelectInput
                                v-model="shareOptions.permission"
                                :options="$translateSelectOptions(permissionOptions)"
                                :default="shareOptions.permission"
                                :placeholder="$t('shared_form.placeholder_permission')"
                                :isError="errors[0]"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <!--Password Switch-->
                    <div>
                        <AppInputSwitch
                            :title="$t('password_protected')"
                            :description="$t('popup.share.password_description')"
                        >
                            <SwitchInput
                                v-model="shareOptions.isProtected"
                                class="switch"
                                :state="shareOptions.isProtected"
                            />
                        </AppInputSwitch>

                        <ActionButton
                            v-if="
                                pickedItem.data.relationships.shared.data.attributes.protected &&
                                canChangePassword &&
                                shareOptions.isProtected
                            "
                            @click="changePassword"
                            class="mb-6 -mt-4"
                        >
                            {{ $t('popup_share_edit.change_pass') }}
                        </ActionButton>

                        <!--Set password-->
                        <ValidationProvider
                            v-if="shareOptions.isProtected && !canChangePassword"
                            tag="div"
                            mode="passive"
                            name="Password"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText :error="errors[0]" class="-mt-2">
                                <input
                                    v-model="shareOptions.password"
                                    :class="{ 'border-rose-600!': errors[0] }"
                                    type="text"
                                    class="focus-border-theme input-dark"
                                    :placeholder="$t('page_sign_in.placeholder_password')"
                                />
                            </AppInputText>
                        </ValidationProvider>
                    </div>

                    <!--Expiration switch-->
                    <div>
                        <AppInputSwitch
                            :title="$t('expiration')"
                            :description="$t('popup.share.expiration_description')"
                            :is-last="!shareOptions.expiration"
                        >
                            <SwitchInput
                                v-model="shareOptions.expiration"
                                class="switch"
                                :state="shareOptions.expiration ? 1 : 0"
                            />
                        </AppInputSwitch>

                        <!--Set expiration-->
                        <AppInputText v-if="shareOptions.expiration" class="-mt-2" :is-last="true">
                            <SelectBoxInput
                                v-model="shareOptions.expiration"
                                :data="$translateSelectOptions(expirationList)"
                                class="box"
                            />
                        </AppInputText>
                    </div>
                </ValidationObserver>
            </PopupContent>

            <PopupActions>
                <ButtonBase
                    class="w-full"
                    @click="destroySharing"
                    :button-style="destroyButtonStyle"
                    :loading="isDeleting"
                    :disabled="isDeleting"
                >
                    {{ destroyButtonText }}
                </ButtonBase>
                <ButtonBase
                    class="w-full"
                    @click="updateShareOptions"
                    button-style="theme"
                    :loading="isLoading"
                    :disabled="isLoading"
                >
                    {{ $t('store_changes') }}
                </ButtonBase>
            </PopupActions>
        </div>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from '../Validation/index.js'
import MultiEmailInput from '../Inputs/MultiEmailInput.vue'
import SelectBoxInput from '../Inputs/SelectBoxInput.vue'
import CopyShareLink from '../Inputs/CopyShareLink.vue'
import PopupWrapper from './Components/PopupWrapper.vue'
import PopupActions from './Components/PopupActions.vue'
import PopupContent from './Components/PopupContent.vue'
import PopupHeader from './Components/PopupHeader.vue'
import SwitchInput from '../Inputs/SwitchInput.vue'
import SelectInput from '../Inputs/SelectInput.vue'
import ThumbnailItem from '../UI/Entries/ThumbnailItem.vue'
import ActionButton from '../UI/Buttons/ActionButton.vue'
import ButtonBase from '../UI/Buttons/ButtonBase.vue'
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch.vue'
import AppInputText from '../Forms/Layouts/AppInputText.vue'
import Spinner from '../UI/Others/Spinner.vue'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'ShareEditPopup',
    components: {
        ValidationProvider,
        ValidationObserver,
        MultiEmailInput,
        AppInputSwitch,
        SelectBoxInput,
        ThumbnailItem,
        CopyShareLink,
        ActionButton,
        PopupWrapper,
        PopupActions,
        AppInputText,
        PopupContent,
        PopupHeader,
        SelectInput,
        SwitchInput,
        ButtonBase,
        Spinner,
    },
    computed: {
        ...mapGetters(['permissionOptions', 'expirationList', 'user']),
        popupTitle() {
            return (
                {
                    'qr-code': this.$t('get_qr_code'),
                    'email-sharing': this.$t('share_with_multiple_emails'),
                }[this.activeSection] || this.$t('popup_share_edit.title')
            )
        },
        isFolder() {
            return this.pickedItem && this.pickedItem.data.type === 'folder'
        },
        destroyButtonText() {
            return this.isConfirmedDestroy ? this.$t('popup_share_edit.confirm') : this.$t('popup_share_edit.stop')
        },
        destroyButtonStyle() {
            return this.isConfirmedDestroy ? 'danger-solid' : 'danger'
        },
    },
    watch: {
        'shareOptions.expiration': function (val) {
            if (!val) {
                this.shareOptions.expiration = undefined
            }
        },
    },
    data() {
        return {
            activeSection: undefined,
            shareOptions: undefined,
            pickedItem: undefined,
            emails: undefined,
            qrCode: undefined,
            isConfirmedDestroy: false,
            canChangePassword: false,
            isMoreOptions: false,
            isDeleting: false,
            isLoading: false,
        }
    },
    methods: {
        getQrCode() {
            axios
                .get(`/api/share/${this.shareOptions.token}/qr`)
                .then((response) => {
                    this.qrCode = response.data.data.svg
                })
                .catch(() => this.$isSomethingWrong())
        },
        showSection(section = undefined) {
            this.activeSection = section
        },
        changePassword() {
            this.canChangePassword = false
        },
        async sendViaEmail() {
            // Validate email field
            const isValid = await this.$refs.shareEmail.validate()

            if (!isValid) return

            this.isLoading = true

            axios
                .post(`/api/share/${this.shareOptions.token}/email`, {
                    emails: this.emails,
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => {
                    this.isLoading = false
                    this.$closePopup()
                })
        },
        async destroySharing() {
            // Set confirm button
            if (!this.isConfirmedDestroy) {
                this.isConfirmedDestroy = true

                return
            }

            // Start deleting spinner button
            this.isDeleting = true

            // Send delete request
            await this.$store
                .dispatch('shareCancel', this.pickedItem)
                .catch(() => {
                    // End deleting spinner button
                    this.isDeleting = false
                })
                .finally(() => this.$closePopup())
        },
        async updateShareOptions() {
            // Validate fields
            const isValid = await this.$refs.shareForm.validate()

            if (!isValid) return

            this.isLoading = true

            // Send request to get share link
            axios
                .post('/api/share/' + this.shareOptions.token, {
                    permission: this.shareOptions.permission,
                    protected: this.shareOptions.isProtected,
                    expiration: this.shareOptions.expiration,
                    password: this.shareOptions.password ? this.shareOptions.password : undefined,
                    _method: 'patch',
                })
                .then((response) => {
                    // Update shared data
                    this.$store.commit('UPDATE_SHARED_ITEM', response.data)

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
        events.$on('emailsInputValues', (emails) => (this.emails = emails))

        // Show popup
        events.$on('popup:open', (args) => {
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

            if (args.section) this.activeSection = args.section

            if (args.section === 'qr-code') this.getQrCode()

            this.canChangePassword = args.item.data.relationships.shared.data.attributes.protected
        })

        events.$on('popup:close', () => {
            // Reset data
            setTimeout(() => {
                this.isDeleting = false
                this.isConfirmedDestroy = false
                this.canChangePassword = false
                this.shareOptions = undefined
                this.pickedItem = undefined
                this.activeSection = undefined
                this.qrCode = undefined
            }, 150)
        })
    },
}
</script>
