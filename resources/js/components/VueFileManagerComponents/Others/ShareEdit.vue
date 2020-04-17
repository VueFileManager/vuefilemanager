<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader title="Update sharing options" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!--Form to set sharing-->
            <ValidationObserver ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Share link-->
                <div class="input-wrapper">
                    <label class="input-label">Share url:</label>
                    <CopyInput size="small" :value="shareLink" />
                </div>

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">Permission:</label>
                    <SelectInput v-model="shareOptions.permission" :options="permissionOptions" default="visitor" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">Password Protected:</label>
                        <SwitchInput v-model="shareOptions.isPassword" class="switch" :state="0"/>
                    </div>
                    <ActionButton @click.native="changePassword" v-if="isPasswordChangeButton" icon="pencil-alt">Change Password</ActionButton>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="isPasswordInput || shareOptions.isPassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" placeholder="Type your password">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

            </ValidationObserver>

        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    class="popup-button"
                    @click.native="destroySharing"
                    :button-style="destroyButtonStyle"
            >{{ destroyButtonText }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="submitShareOptions"
                    button-style="theme"
                    :loading="isLoading"
                    :disabled="isLoading"
            >Save Changes
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import PopupWrapper from '@/components/VueFileManagerComponents/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/VueFileManagerComponents/Others/Popup/PopupActions'
    import PopupContent from '@/components/VueFileManagerComponents/Others/Popup/PopupContent'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PopupHeader from '@/components/VueFileManagerComponents/Others/Popup/PopupHeader'
    import SwitchInput from '@/components/VueFileManagerComponents/Others/Forms/SwitchInput'
    import SelectInput from '@/components/VueFileManagerComponents/Others/Forms/SelectInput'
    import ThumbnailItem from '@/components/VueFileManagerComponents/Others/ThumbnailItem'
    import ActionButton from '@/components/VueFileManagerComponents/Others/ActionButton'
    import CopyInput from '@/components/VueFileManagerComponents/Others/Forms/CopyInput'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'ShareEdit',
        components: {
            ValidationProvider,
            ValidationObserver,
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
            ...mapGetters(['app']),
            isFolder() {
                return this.pickedItem && this.pickedItem.type === 'folder'
            },
            destroyButtonText() {
                return this.isConfirmedDestroy ? 'Confirm' : 'Stop Sharing'
            },
            destroyButtonStyle() {
                return this.isConfirmedDestroy ? 'danger-solid' : 'secondary'
            }
        },
        data() {
            return {
                shareOptions: {
                    isPassword: false,
                    password: undefined,
                    permission: undefined,
                },
                permissionOptions: [
                    {
                        label: 'Can edit and upload files',
                        value: 'editor',
                        icon: 'user-edit',
                    },
                    {
                        label: 'Can only view and download',
                        value: 'visitor',
                        icon: 'user',
                    },
                ],
                pickedItem: undefined,
                isLoading: false,
                isPasswordInput: false,
                isPasswordChangeButton: true,
                isConfirmedDestroy: false,
                shareLink: 'http://192.168.1.131:8000/shared?token=3ZlQLIoCR8izoc0PemekHNq3UIMj6OrC0aQ2zowclfjFYa8P6go8fMKPnXTJomvz'
            }
        },
        methods: {
            changePassword() {
                this.isPasswordInput = true
                this.isPasswordChangeButton = false
            },
            destroySharing() {

                if (! this.isConfirmedDestroy) {
                    this.isConfirmedDestroy = true

                    return
                } else {

                    this.$closePopup()
                }
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
                    .post('/api/share/generate', this.shareOptions)
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        this.shareLink = response.data
                        this.isGeneratedShared = true
                    })
                    .catch(error => {

                        // todo: catch errors

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
            })

            // Close popup
            events.$on('popup:close', () => {

                // Restore data
                setTimeout(() => {
                    this.isConfirmedDestroy = false
                    this.isPasswordInput = false
                    this.isPasswordChangeButton = true
                    //this.shareLink = undefined
                    this.shareOptions = {
                        permission: undefined,
                        password: undefined,
                        isPassword: false,
                    }
                }, 150)
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";
    @import "@assets/vue-file-manager/_inapp-forms.scss";

    .input-wrapper {

        &.password {
            margin-top: -10px;
        }
    }

    .item-thumbnail {
        margin-bottom: 20px;
    }
</style>
