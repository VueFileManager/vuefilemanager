<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_create.title', {item: itemTypeTitle})" icon="share" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <div class="select-share-wrapper" v-if="!isGeneratedShared">
                <div @click="shareBy = 'link'" :class="{'active' : shareBy === 'link'}">
                    <link-icon class="icon" size="17" />
                    <h1>{{$t('shared_form.share_by_link')}}</h1> 
                </div>
                <div @click="shareBy = 'email'" :class="{'active' : shareBy === 'email'}">
                    <mail-icon class="icon" size="17"/> 
                    <h1> {{$t('shared_form.share_by_email')}}</h1>
                </div>
            </div>

            <!-- Message after successfully sned shared link via email -->
            <div v-if="shareBy === 'email' && isGeneratedShared" class="successfully-send-wrapper">
                <div class="successfully-send"> {{$t('shared_form.email_successfully_send_message')}} </div>
            </div>



            <!--Form to set sharing-->
            <ValidationObserver v-if="! isGeneratedShared" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                 <!-- Email input for the shared via Email1 -->
                <ValidationProvider v-if="shareBy === 'email' && ! isGeneratedShared " tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                    <EmailsInput  rules="required" v-model="shareOptions.emails" :isError="errors[0]" />
                </ValidationProvider>
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
                    <label class="input-label">{{ $t('shared_form.label_shared_url') }}:</label>
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
    import ButtonBase from '@/components/FilesView/ButtonBase'
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
            PopupContent,
            PopupHeader,
            EmailsInput,
            SelectInput,
            SwitchInput,
            ButtonBase,
            CopyInput,
            MailIcon, 
            required,
            LinkIcon
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
                shareBy: "link"
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

                this.shareBy = 'link'

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

    .successfully-send-wrapper {
        padding: 0px 20px;
        margin-bottom: 20px;
        .successfully-send {
            width: 100%;
            height: 34px;
            border-radius: 8px;
            background: $light_background ;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            p {
                color: $theme;
            }
        }
    }

    .select-share-wrapper {
        display: flex;
        justify-content: center;
        padding: 0px 20px;
        margin-bottom: 20px;
        cursor: pointer;
        
        & > * {
            width: 100%;
            height: 42px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: $light_background;
            color: $text;
        }
        & > :first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        & > :last-child {
             border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .icon {
            margin-right: 10px;
            path,
            polyline {
                color: $theme !important;
            }
        }
    }

    .select-share-wrapper {
        .active {
            background: $text;
                h1 {
                    color: $light_background !important;
                }
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

    @media (prefers-color-scheme: dark) {
        .select-share-wrapper {   
            & > * {
                background: $dark_mode_foreground;
                color: $dark_mode_text_primary;
            }
             .active {
            background: $dark_mode_text_primary;
                h1 {
                    color: $dark_mode_foreground !important;
                }
            }
        }
         .successfully-send {
            background: $dark_mode_foreground !important;
            p {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
