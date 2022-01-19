<template>
    <PopupWrapper name="create-folder">

        <!--Title-->
        <PopupHeader :title="$t('popup_create_folder.title')" icon="edit" />

        <!--Content-->
        <PopupContent>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="createFolder" ref="createForm" v-slot="{ invalid }" tag="form" class="px-4">

                <!--Set folder name-->
                <ValidationProvider tag="div" mode="passive" name="Title" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('popup_create_folder.label')" :error="errors[0]">
    	                <input v-model="name" :class="{'border-red': errors[0]}" type="text" ref="input" class="focus-border-theme input-dark" :placeholder="$t('popup_create_folder.placeholder')">
					</AppInputText>
                </ValidationProvider>

                <SetFolderIcon v-if="isMoreOptions"/>

                <ActionButton @click.native.stop="moreOptions" :icon="isMoreOptions ? 'x' : 'pencil-alt'">{{ moreOptionsTitle }}</ActionButton>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    class="w-full"
                    @click.native="$closePopup()"
                    button-style="secondary"
            >{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
                    class="w-full"
                    @click.native="createFolder"
                    button-style="theme"
            >{{ $t('popup_create_folder.title') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
	import AppInputText from "../Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
    import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
    import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
    import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
    import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
    import SetFolderIcon from '/resources/js/components/Others/SetFolderIcon'
    import ActionButton from '/resources/js/components/Others/ActionButton'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {required} from 'vee-validate/dist/rules'
    import {events} from '/resources/js/bus'

    export default {
        name: 'CreateFolderPopup',
        components: {
			AppInputText,
            ValidationProvider,
            ValidationObserver,
            ThumbnailItem,
            SetFolderIcon,
            ActionButton,
            PopupWrapper,
            PopupActions,
            PopupContent,
            PopupHeader,
            ButtonBase,
            required,
        },
        computed: {
            moreOptionsTitle() {
                return this.isMoreOptions ? this.$t('shared_form.button_close_options') : this.$t('shared_form.button_folder_icon_open')
            }
        },
        data() {
            return {
                name: undefined,
                isMoreOptions: false,
                folderIcon: undefined
            }
        },
        methods: {
            moreOptions() {
                this.isMoreOptions = !this.isMoreOptions
            },
            async createFolder() {

                // Validate fields
                const isValid = await this.$refs.createForm.validate();

                if (isValid) {
                    this.$store.dispatch('createFolder', {'name':this.name, 'icon': this.folderIcon})

                    this.$closePopup()

                    this.name = undefined
                }
            },
        },
        mounted() {
            events.$on('setFolderIcon', (icon) => {
                this.folderIcon = icon
            })

            events.$on('popup:open', ({name}) => {

                if (name === 'create-folder' && ! this.$isMobile())
                    this.$nextTick(() => this.$refs.input.focus())
            })

            events.$on('setFolderIcon', (icon) => {
                this.setFolderIcon = icon
            })
        }
    }
</script>
