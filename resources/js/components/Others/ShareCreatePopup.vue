<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_create.title', {item: itemTypeTitle})" icon="share" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata" />

            <!-- Infobox for successful send email -->
            <InfoBox v-if="isGeneratedShared && sharedViaEmail" class="info-box-wrapper">
                <p v-html="$t('shared_form.email_successfully_send_message')"></p>
            </InfoBox>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent v-if="! isGeneratedShared" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <TabWrapper>

                    <!-- Share via link -->
                    <TabOption :selected="true" :title="$t('shared_form.share_by_link')" icon="link" />

                    <!-- Share via Email -->
                    <TabOption :title="$t('shared_form.share_by_email')" icon="email">
                        <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                            <MultiEmailInput rules="required" v-model="shareOptions.emails" :label="$t('shared_form.recipients_label')" :isError="errors[0]" />
                        </ValidationProvider>
                    </TabOption>

                </TabWrapper>

                <!--Permission Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('shared_form.label_permission') }}:</label>
                    <SelectInput v-model="shareOptions.permission" :options="$translateSelectOptions(permissionOptions)" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_password_protection') }}:</label>
                        <SwitchInput v-model="shareOptions.isPassword" class="switch" :state="0" />
                    </div>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isPassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" class="focus-border-theme" :placeholder="$t('page_sign_in.placeholder_password')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--More options-->
                <div class="more-options" v-if="isMoreOptions">

                    <!--Set expiration-->
                    <div class="input-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_expiration') }}:</label>
                        <SelectBoxInput v-model="shareOptions.expiration" :data="$translateSelectOptions(expirationList)" class="box" />
                    </div>
                </div>

                <ActionButton @click.native="moreOptions" :icon="isMoreOptions ? 'x' : 'pencil-alt'">
                    {{ moreOptionsTitle }}
                </ActionButton>
            </ValidationObserver>

            <!--Copy generated link-->
            <div v-if="isGeneratedShared" class="form-wrapper">
                <div class="input-wrapper">
                    <label class="input-label">{{ this.sharedViaEmail ? $t('shared_form.label_share_vie_email') : $t('shared_form.label_shared_url') }}:</label>
                    <CopyShareLink size="small" :item="pickedItem" />
                </div>
            </div>
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
            return this.pickedItem && this.pickedItem.type === 'folder' ? this.$t('types.folder') : this.$t('types.file')
        },
        isFolder() {
            return this.pickedItem && this.pickedItem.type === 'folder'
        },
        submitButtonText() {
            return this.isGeneratedShared ? this.$t('shared_form.button_done') : this.$t('shared_form.button_generate')
        },
        moreOptionsTitle() {
            return this.isMoreOptions ? this.$t('shared_form.button_close_options') : this.$t('shared_form.button_more_options')
        }
    },
    data() {
        return {
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
            isMoreOptions: false,
            sharedViaEmail: false
        }
    },
    methods: {
        moreOptions() {
            this.isMoreOptions = !this.isMoreOptions

            if (!this.isMoreOptions)
                this.shareOptions.expiration = undefined
        },
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

                    // Show infobox and reset emails container
                    if (this.shareOptions.emails)
                        this.sharedViaEmail = true

                    // End loading
                    this.isGeneratedShared = true

                    this.$store.commit('UPDATE_SHARED_ITEM', response.data.data.attributes)
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

        events.$on('emailsInputValues', (emails) => this.shareOptions.emails = emails)

        // Show popup
        events.$on('popup:open', args => {

            if (args.name !== 'share-create') return

            // Store picked item
            this.pickedItem = args.item

            this.shareOptions.type = args.item.type
            this.shareOptions.id = args.item.id
        })

        // Close popup
        events.$on('popup:close', () => {

            // Restore data
            setTimeout(() => {
                this.shareOptions = {
                    permission: undefined,
                    password: undefined,
                    isPassword: false,
                    expiration: undefined,
                    type: undefined,
                    id: undefined,
                    emails: undefined
                }
                this.isGeneratedShared = false
                this.isMoreOptions = false
                this.sharedViaEmail = false
            }, 150)
        })
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

.more-options {
    margin-bottom: 10px;
}

.input-wrapper {

    &.password {
        margin-top: -10px;
    }
}

.item-thumbnail {
    margin-bottom: 20px;
}

</style>
