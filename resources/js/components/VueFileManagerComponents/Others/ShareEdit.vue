<template>
    <PopupWrapper name="share-edit">
        <!--Title-->
        <PopupHeader title="Update sharing options" />

        <!--Content-->
        <PopupContent v-if="pickedItem && pickedItem.shared">

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!--Form to set sharing-->
            <ValidationObserver ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Share link-->
                <div class="input-wrapper">
                    <label class="input-label">Share url:</label>
                    <CopyInput size="small" :value="pickedItem.shared.link" />
                </div>

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">Permission:</label>
                    <SelectInput v-model="shareOptions.permission" :options="permissionOptions" :default="shareOptions.permission" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">Password Protected:</label>
                        <SwitchInput v-model="shareOptions.isProtected" :state="shareOptions.isProtected" class="switch"/>
                    </div>
                    <ActionButton v-if="(pickedItem.shared.protected && canChangePassword) && shareOptions.isProtected" @click.native="changePassword" icon="pencil-alt">Change Password</ActionButton>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isProtected && ! canChangePassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
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
            >Ok
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
            ...mapGetters(['app', 'permissionOptions', 'currentFolder']),
            isFolder() {
                return this.pickedItem && this.pickedItem.type === 'folder'
            },
            destroyButtonText() {
                return this.isConfirmedDestroy ? 'Confirm' : 'Stop Sharing'
            },
            destroyButtonStyle() {
                return this.isConfirmedDestroy ? 'danger-solid' : 'secondary'
            },
            isSharedLocation() {
                return this.currentFolder && this.currentFolder.location === 'shared'
            },
        },
        data() {
            return {
                shareOptions: undefined,
                pickedItem: undefined,
                isLoading: false,
                isDeleting: false,
                canChangePassword: false,
                isConfirmedDestroy: false,
            }
        },
        methods: {
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
                        .delete('/api/share/remove', {
                            params: {
                                token: this.pickedItem.shared.token
                            }
                        })
                        .then(() => {

                            // Remove item from file browser
                            if ( this.isSharedLocation ) {
                                this.$store.commit('REMOVE_ITEM', this.pickedItem.unique_id)
                            }

                            // Flush shared data
                            this.$store.commit('FLUSH_SHARED', this.pickedItem.unique_id)

                            // End deleting spinner button
                            this.isDeleting = false

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
                    .post('/api/share/update', this.shareOptions)
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Update shared data
                        this.$store.commit('UPDATE_SHARED_ITEM', response.data)

                        events.$emit('popup:close')
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

                // Store shared options
                this.shareOptions = {
                    token: args.item.shared.token,
                    isProtected: args.item.shared.protected,
                    permission: args.item.shared.permission,
                    password: undefined,
                }

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
