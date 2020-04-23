<template>
    <PopupWrapper name="share-create">
        <!--Title-->
        <PopupHeader :title="'Share Your ' + itemTypeTitle" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!--Form to set sharing-->
            <ValidationObserver v-if="! isGeneratedShared" ref="shareForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Permision Select-->
                <ValidationProvider v-if="isFolder" tag="div" mode="passive" class="input-wrapper" name="Permission" rules="required" v-slot="{ errors }">
                    <label class="input-label">Permission:</label>
                    <SelectInput v-model="shareOptions.permission" :options="permissionOptions" :isError="errors[0]"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <!--Password Switch-->
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <label class="input-label">Password Protected:</label>
                        <SwitchInput v-model="shareOptions.isPassword" class="switch" :state="0"/>
                    </div>
                </div>

                <!--Set password-->
                <ValidationProvider v-if="shareOptions.isPassword" tag="div" mode="passive" class="input-wrapper password" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="shareOptions.password" :class="{'is-error': errors[0]}" type="text" placeholder="Type your password">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <!--Copy generated link-->
            <div v-if="isGeneratedShared" class="form-wrapper">
                <div class="input-wrapper">
                    <label class="input-label">Share url:</label>
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
    import PopupWrapper from '@/components/VueFileManagerComponents/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/VueFileManagerComponents/Others/Popup/PopupActions'
    import PopupContent from '@/components/VueFileManagerComponents/Others/Popup/PopupContent'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PopupHeader from '@/components/VueFileManagerComponents/Others/Popup/PopupHeader'
    import SwitchInput from '@/components/VueFileManagerComponents/Others/Forms/SwitchInput'
    import SelectInput from '@/components/VueFileManagerComponents/Others/Forms/SelectInput'
    import ThumbnailItem from '@/components/VueFileManagerComponents/Others/ThumbnailItem'
    import CopyInput from '@/components/VueFileManagerComponents/Others/Forms/CopyInput'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'ShareCreate',
        components: {
            ValidationProvider,
            ValidationObserver,
            ThumbnailItem,
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
            ...mapGetters(['app', 'permissionOptions']),
            itemTypeTitle() {
                return this.pickedItem && this.pickedItem.type === 'folder' ? 'Folder' : 'File'
            },
            isFolder() {
                return this.pickedItem && this.pickedItem.type === 'folder'
            },
            submitButtonText() {
                return this.isGeneratedShared ? 'Awesome, I’m done!' : 'Generate Link'
            }
        },
        data() {
            return {
                shareOptions: {
                    isPassword: false,
                    password: undefined,
                    permission: undefined,
                    type: undefined,
                    unique_id: undefined,
                },
                pickedItem: undefined,
                shareLink: undefined,
                isGeneratedShared: false,
                isLoading: false,
            }
        },
        methods: {
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

                        this.shareLink = response.data.link
                        this.isGeneratedShared = true

                        this.$store.commit('UPDATE_SHARED_ITEM', response.data)
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
                    this.isGeneratedShared = false
                    this.shareLink = undefined
                    this.shareOptions = {
                        permission: undefined,
                        password: undefined,
                        isPassword: false,
                        type: undefined,
                        unique_id: undefined,
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
