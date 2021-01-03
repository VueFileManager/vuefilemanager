<template>
    <PopupWrapper name="rename-item">
        <!--Title-->
        <PopupHeader :title="$t('popup_rename.title', {item: itemTypeTitle})" icon="edit" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata"/>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="changeName" ref="renameForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Set password-->
                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Name" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('popup_rename.label') }}:</label>
                    <input v-model="pickedItem.name" :class="{'is-error': errors[0]}" ref="input" type="text" :placeholder="$t('popup_rename.placeholder')">
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
                    @click.native="changeName"
                    button-style="theme"
            >{{ $t('popup_share_edit.save') }}
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
        name: 'RenameItem',
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
        computed: {
            itemTypeTitle() {
                return this.pickedItem && this.pickedItem.type === 'folder' ? this.$t('types.folder') : this.$t('types.file')
            },
        },
        data() {
            return {
                pickedItem: undefined,
            }
        },
        methods: {
            changeName() {
                if (this.pickedItem.name && this.pickedItem.name !== '') {

                    let item = {
                        unique_id: this.pickedItem.unique_id,
                        type: this.pickedItem.type,
                        name: this.pickedItem.name
                    }

                    // Rename item request
                    this.$store.dispatch('renameItem', item)

                    // Rename item in view
                    events.$emit('change:name', item)

                    this.$closePopup()
                }
            },
        },
        mounted() {

            // Show popup
            events.$on('popup:open', args => {

                if (args.name !== 'rename-item') return

                this.$nextTick(() => {
                    this.$refs.input.focus()
                })

                // Store picked item
                this.pickedItem = args.item
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
