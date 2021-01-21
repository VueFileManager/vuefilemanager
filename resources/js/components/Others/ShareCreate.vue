<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_create.title', {item: itemTypeTitle})" icon="share" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!-- Infobox for successfull sended email -->
            <div v-if="isGeneratedShared && shareViaEmail" class="info-box">
                <InfoBox v-html="$t('shared_form.email_successfully_send_message')"/>
            </div>


            <!--Form to set sharing-->
            <ValidationObserver v-if="! isGeneratedShared" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">
                
                <TableWrapper>

                    <!-- Share via link -->
                    <TableOption :title="$t('shared_form.share_by_link')" icon="link"/>
                    
                    <!-- Share via Email -->
                    <TableOption :title="$t('shared_form.share_by_email')" icon="email">
                        <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                            <EmailsInput  rules="required" v-model="shareOptions.emails" :label="$t('shared_form.recipients_label')" :isError="errors[0]" />
                        </ValidationProvider>
                    </TableOption>

                </TableWrapper>

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('shared_form.label_permission') }}:</label>
                    <SelectInput v-model="shareOptions.permission" :options="permissionOptions" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_password_protection') }}:</label>
                        <SwitchInput v-model="shareOptions.isPassword" class="switch" :state="0"/>
                    </div>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isPassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" :placeholder="$t('page_sign_in.placeholder_password')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--More options-->
                <div class="more-options" v-if="isMoreOptions">

                    <!--Set expiration-->
                    <div class="input-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_expiration') }}:</label>
                        <SelectBoxInput v-model="shareOptions.expiration" :data="expirationList" class="box"/>
                    </div>
                </div>

                <ActionButton @click.native="moreOptions" :icon="isMoreOptions ? 'x' : 'pencil-alt'">{{ moreOptionsTitle }}</ActionButton>
            </ValidationObserver>

            <!--Copy generated link-->
            <div v-if="isGeneratedShared" class="form-wrapper">
                <div class="input-wrapper">
                    <label class="input-label">{{ this.shareViaEmail ? $t('shared_form.label_share_vie_email') : $t('shared_form.label_shared_url') }}:</label>
                    <CopyInput size="small" :value="shareLink" />
                </div>
            </div>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    v-if="! isGeneratedShared"
                    class="popup-button"
                    @click.native="$closePopup()"
                    button-style="secondary"
            >{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="submitShareOptions"
                    button-style="theme"
                    :loading="isLoading"
                    :disabled="isLoading"
            >{{ submitButtonText }}
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
    import EmailsInput from '@/components/Others/Forms/EmailsInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ThumbnailItem from '@/components/Others/ThumbnailItem'
    import ActionButton from '@/components/Others/ActionButton'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import TableWrapper from '@/components/Others/TableWrapper'
    import TableOption from '@/components/Others/TableOption'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {LinkIcon, MailIcon } from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'ShareCreate',
        components: {
            ValidationProvider,
            ValidationObserver,
            SelectBoxInput,
            ThumbnailItem,
            ActionButton,
            PopupWrapper,
            PopupActions,
            TableWrapper,
            TableOption,
            PopupContent,
            PopupHeader,
            EmailsInput,
            SelectInput,
            SwitchInput,
            ButtonBase,
            CopyInput,
            MailIcon, 
            required,
            LinkIcon,
            InfoBox
        },
        computed: {
            ...mapGetters([
                'permissionOptions',
                'expirationList',
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
                    unique_id: undefined,
                    emails:undefined
                },
                pickedItem: undefined,
                shareLink: undefined,
                isGeneratedShared: false,
                isLoading: false,
                isMoreOptions: false,
                shareViaEmail: false
            }
        },
        methods: {
            moreOptions() {
                this.isMoreOptions = ! this.isMoreOptions

                if (! this.isMoreOptions)
                    this.shareOptions.expiration = undefined
            },
            async submitShareOptions() {

                // If shared was generated, then close popup
                if (this.isGeneratedShared) {
                    events.$emit('popup:close')

                    return;
                }

                // Validate fields
                const isValid = await this.$refs.shareForm.validate();

                if (!isValid) return;

                this.isLoading = true

                // Send request to get share link
                axios
                    .post('/api/share', this.shareOptions)
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        this.shareViaEmail = response.data.data.attributes.email_share

                        this.shareLink = response.data.data.attributes.link
                        this.isGeneratedShared = true

                        this.$store.commit('UPDATE_SHARED_ITEM', response.data.data.attributes)
                    })
                    .catch(error => {

                        // todo: catch errors

                        // End loading
                        this.isLoading = false
                    })
            },
        },
        mounted() {

            events.$on('emailsInputValues', (emails) => this.shareOptions.emails = emails)

            // Show popup
            events.$on('popup:open', args => {

                if (args.name !== 'share-create') return

                // Store picked item
                this.pickedItem = args.item

                this.shareOptions.type = args.item.type
                this.shareOptions.unique_id = args.item.unique_id
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
                        unique_id: undefined,
                    }
                    this.isGeneratedShared = false
                    this.isMoreOptions = false
                    this.shareLink = undefined
                }, 150)
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .info-box {
        padding: 0px 20px;
        /deep/.info-box {
            @include font-size(14);
            font-weight: 700;
            height: 40px;
            display: flex;
            align-items: center;
        }
    }

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
