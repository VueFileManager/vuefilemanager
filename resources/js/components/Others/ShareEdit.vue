<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader :title="$t('popup_share_edit.title')" icon="share" />

        <!--Content-->
        <PopupContent v-if="pickedItem && pickedItem.shared">

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!--Form to set sharing-->
            <ValidationObserver ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Share link-->
                <div class="input-wrapper">
                    <label class="input-label">{{ $t('shared_form.label_shared_url') }}:</label>
                    <CopyInput size="small" :value="pickedItem.shared.link" />
                </div>

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('shared_form.label_permission') }}:</label>
                    <SelectInput v-model="shareOptions.permission" :options="permissionOptions" :default="shareOptions.permission" :placeholder="$t('shared_form.placeholder_permission')" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_password_protection') }}:</label>
                        <SwitchInput v-model="shareOptions.isProtected" :state="shareOptions.isProtected" class="switch"/>
                    </div>
                    <ActionButton v-if="(pickedItem.shared.protected && canChangePassword) && shareOptions.isProtected" @click.native="changePassword" class="change-password">{{ $t('popup_share_edit.change_pass') }}</ActionButton>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isProtected && ! canChangePassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" :placeholder="$t('page_sign_in.placeholder_password')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--More options-->
                <div class="more-options" v-if="isMoreOptions">

                    <!--Set expiration-->
                    <div class="input-wrapper">
                        <label class="input-label">{{ $t('shared_form.label_expiration') }}:</label>
                        <SelectBoxInput v-model="shareOptions.expiration" :data="expirationList" :value="shareOptions.expiration" class="box"/>
                    </div>
                </div>

                <ActionButton @click.native="moreOptions" :icon="isMoreOptions || shareOptions.expiration ? 'x' : 'pencil-alt'">{{ moreOptionsTitle }}</ActionButton>

            </ValidationObserver>

        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
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
            >{{ $t('popup_share_edit.save') }}
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
    import ThumbnailItem from '@/components/Others/ThumbnailItem'
    import ActionButton from '@/components/Others/ActionButton'
    import CopyInput from '@/components/Others/Forms/CopyInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
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
            SwitchInput,
            ButtonBase,
            CopyInput,
            required,
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
                return this.isConfirmedDestroy ? this.$t('popup_share_edit.confirm') : this.$t('popup_share_edit.stop')
            },
            destroyButtonStyle() {
                return this.isConfirmedDestroy ? 'danger-solid' : 'secondary'
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
                isConfirmedDestroy: false,
                canChangePassword: false,
                shareOptions: undefined,
                pickedItem: undefined,
                isMoreOptions: false,
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
            destroySharing() {

                // Set confirm button
                if (! this.isConfirmedDestroy) {

                    this.isConfirmedDestroy = true
                } else {

                    // Start deleting spinner button
                    this.isDeleting = true

                    // Send delete request
                    axios
                        .post('/api/share/' + this.pickedItem.shared.token, {
                            _method: 'delete'
                        })
                        .then(() => {
                            // Remove item from file browser
                            if ( this.isSharedLocation ) {
                                this.$store.commit('REMOVE_ITEM', this.pickedItem.unique_id)
                            }

                            // Flush shared data
                            this.$store.commit('FLUSH_SHARED', this.pickedItem.unique_id)

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

                        // End loading
                        this.isLoading = false
                    })
            },
        },
        mounted() {

            // Show popup
            events.$on('popup:open', args => {

                if (args.name !== 'share-edit') return

                // Store picked item
                this.pickedItem = args.item

                // Store shared options
                this.shareOptions = {
                    token: args.item.shared.token,
                    expiration: args.item.shared.expire_in,
                    isProtected: args.item.shared.protected,
                    permission: args.item.shared.permission,
                    password: undefined,
                }

                if (args.item.shared.expire_in)
                    this.isMoreOptions = true

                this.canChangePassword = args.item.shared.protected
            })

            // Close popup
            events.$on('popup:close', () => {

                // Restore data
                setTimeout(() => {
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
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .input-wrapper {

        &.password {
            margin-top: -10px;
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
