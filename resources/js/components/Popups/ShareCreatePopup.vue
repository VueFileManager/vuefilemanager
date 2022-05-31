<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_create.title', { item: itemTypeTitle })" icon="share" />

        <!--Content-->
        <PopupContent class="!overflow-initial">
            <!--Item Thumbnail-->
            <ThumbnailItem class="mb-5" :item="pickedItem" />

            <!--Form to set sharing-->
            <ValidationObserver
                v-if="!isGeneratedShared"
                @submit.prevent
                ref="shareForm"
                v-slot="{ invalid }"
                tag="form"
            >
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
                            v-model="shareOptions.isPassword"
                            class="switch"
                            :state="shareOptions.isPassword"
                        />
                    </AppInputSwitch>

                    <!--Set password-->
                    <ValidationProvider
                        v-if="shareOptions.isPassword"
                        tag="div"
                        mode="passive"
                        name="Password"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :error="errors[0]" class="-mt-2">
                            <input
                                v-model="shareOptions.password"
                                :class="{ '!border-rose-600': errors[0] }"
                                type="text"
                                class="focus-border-theme input-dark"
                                :placeholder="$t('page_sign_in.placeholder_password')"
                            />
                        </AppInputText>
                    </ValidationProvider>
                </div>

                <!--Expiration switch-->
                <div>
                    <AppInputSwitch :title="$t('expiration')" :description="$t('popup.share.expiration_description')">
                        <SwitchInput v-model="isExpiration" class="switch" :state="isExpiration" />
                    </AppInputSwitch>

                    <!--Set expiration-->
                    <AppInputText v-if="isExpiration" class="-mt-2">
                        <SelectBoxInput
                            v-model="shareOptions.expiration"
                            :data="$translateSelectOptions(expirationList)"
                            class="box"
                        />
                    </AppInputText>
                </div>

                <!--Send on emails switch-->
                <div>
                    <AppInputSwitch
                        :title="$t('popup.share.email_send')"
                        :description="$t('popup.share.email_description')"
                        :is-last="!isEmailSharing"
                    >
                        <SwitchInput v-model="isEmailSharing" class="switch" :state="isEmailSharing" />
                    </AppInputSwitch>

                    <!--Emails-->
                    <ValidationProvider
                        v-if="isEmailSharing"
                        tag="div"
                        mode="passive"
                        name="Email"
                        rules="required"
                        v-slot="{ errors }"
                        class="-mt-2 mb-1"
                    >
						<AppInputText :error="errors[0]" class="-mt-2" :is-last="true">
							<MultiEmailInput
								rules="required"
								v-model="shareOptions.emails"
								:label="$t('recipients')"
								:is-error="errors[0]"
							/>
						</AppInputText>
                    </ValidationProvider>
                </div>
            </ValidationObserver>

            <!--Copy generated link-->
            <AppInputText v-if="isGeneratedShared" :title="$t('get_your_link')" :is-last="true">
                <CopyShareLink :item="pickedItem" />
            </AppInputText>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase v-if="!isGeneratedShared" class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
                @click.native="submitShareOptions"
                button-style="theme"
                :loading="isLoading"
                :disabled="isLoading"
            >
                {{ submitButtonText }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from '../Forms/Layouts/AppInputText'
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SelectBoxInput from '../Inputs/SelectBoxInput'
import PopupWrapper from './Components/PopupWrapper'
import PopupActions from './Components/PopupActions'
import PopupContent from './Components/PopupContent'
import PopupHeader from './Components/PopupHeader'
import MultiEmailInput from '../Inputs/MultiEmailInput'
import SwitchInput from '../Inputs/SwitchInput'
import SelectInput from '../Inputs/SelectInput'
import ThumbnailItem from '../UI/Entries/ThumbnailItem'
import ActionButton from '../UI/Buttons/ActionButton'
import CopyShareLink from '../Inputs/CopyShareLink'
import ButtonBase from '../UI/Buttons/ButtonBase'
import InfoBox from '../UI/Others/InfoBox'
import { LinkIcon, MailIcon } from 'vue-feather-icons'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
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
        InfoBox,
    },
    computed: {
        ...mapGetters(['permissionOptions', 'expirationList']),
        itemTypeTitle() {
            return this.pickedItem && this.pickedItem.data.type === 'folder'
                ? this.$t('folder')
                : this.$t('file')
        },
        isFolder() {
            return this.pickedItem && this.pickedItem.data.type === 'folder'
        },
        submitButtonText() {
            return this.isGeneratedShared ? this.$t('awesome_iam_done') : this.$t('generate_link')
        },
    },
    watch: {
        isExpiration(val) {
            if (!val) this.shareOptions.expiration = undefined
        },
    },
    data() {
        return {
			id: undefined,
            isExpiration: false,
            isEmailSharing: false,
            shareOptions: {
				id: undefined,
                isPassword: undefined,
                expiration: undefined,
                password: undefined,
                permission: undefined,
                type: undefined,
                emails: undefined,
            },
            pickedItem: undefined,
            isGeneratedShared: false,
            isLoading: false,
            sharedViaEmail: false,
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
                .post('/api/share', this.shareOptions)
                .then((response) => {
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
        },
    },
    mounted() {
        events.$on('emailsInputValues', (emails) => (this.shareOptions.emails = emails))

        // Show popup
        events.$on('popup:open', (args) => {
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
					id: undefined,
                    isPassword: false,
                    expiration: undefined,
                    password: undefined,
                    permission: undefined,
                    type: undefined,
                    emails: undefined,
                }
            }, 150)
        })
    },
}
</script>
