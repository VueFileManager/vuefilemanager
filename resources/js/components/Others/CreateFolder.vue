<template>
    <PopupWrapper name="create-folder">

        <!--Title-->
        <PopupHeader :title="$t('popup_create_folder.title')" icon="edit" />

        <!--Content-->
        <PopupContent>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="createFolder" ref="createForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Set password-->
                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Title" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('popup_create_folder.label') }}:</label>
                    <input v-model="name" :class="{'is-error': errors[0]}" type="text" ref="input" :placeholder="$t('popup_create_folder.placeholder')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    class="popup-button"
                    @click.native="$closePopup()"
                    button-style="secondary"
            >{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="createFolder"
                    button-style="theme"
            >{{ $t('popup_create_folder.title') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/Others/Popup/PopupActions'
    import PopupContent from '@/components/Others/Popup/PopupContent'
    import PopupHeader from '@/components/Others/Popup/PopupHeader'
    import ThumbnailItem from '@/components/Others/ThumbnailItem'
    import ActionButton from '@/components/Others/ActionButton'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {required} from 'vee-validate/dist/rules'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'CreateFolder',
        components: {
            ValidationProvider,
            ValidationObserver,
            ThumbnailItem,
            ActionButton,
            PopupWrapper,
            PopupActions,
            PopupContent,
            PopupHeader,
            ButtonBase,
            required,
        },
        data() {
            return {
                name: undefined,
            }
        },
        methods: {
            async createFolder() {

                // Validate fields
                const isValid = await this.$refs.createForm.validate();

                if (isValid) {
                    this.$store.dispatch('createFolder', this.name)

                    this.$closePopup()

                    this.name = undefined
                }
            },
        },
        mounted() {
            events.$on('popup:open', ({name}) => {

                if (name === 'create-folder' && ! this.$isMobile())
                    this.$nextTick(() => this.$refs.input.focus())
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .item-thumbnail {
        margin-bottom: 20px;
    }
</style>
