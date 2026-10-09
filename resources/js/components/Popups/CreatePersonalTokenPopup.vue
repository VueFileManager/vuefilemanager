<template>
    <PopupWrapper name="create-personal-token">
        <PopupHeader :title="$t('create_personal_token')" icon="key" />

        <PopupContent>
            <ValidationObserver
                v-if="!token"
                @submit.prevent="createTokenForm"
                ref="createToken"
                v-slot="{ invalid }"
                tag="form"
            >
                <ValidationProvider tag="div" mode="passive" name="Token Name" rules="required|min:3" v-slot="{ errors }">
                    <AppInputText :title="$t('token_name')" :error="errors[0]" :is-last="true">
                        <input
                            v-model="name"
                            :class="{ 'border-rose-600!': errors[0] }"
                            type="text"
                            ref="input"
                            class="focus-border-theme input-dark"
                            :placeholder="$t('popup_personal_token.plc')"
                        />
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>

            <AppInputText v-if="token" :title="$t('popup_personal_token.your_token')" :is-last="true">
                <CopyInput size="small" :str="token['plainTextToken']" />

                <InfoBox style="margin-bottom: 0; margin-top: 20px">
                    <p v-html="$t('popup_personal_token.copy_token')"></p>
                </InfoBox>
            </AppInputText>
        </PopupContent>

        <PopupActions v-if="!token">
            <ButtonBase class="w-full" @click="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
                @click="createTokenForm"
                button-style="theme"
                :loading="isLoading"
                :disabled="isLoading"
            >
                {{ $t('create_token') }}
            </ButtonBase>
        </PopupActions>

        <PopupActions v-if="token">
            <ButtonBase class="w-full" @click="$closePopup" button-style="theme">
                {{ $t('awesome_iam_done') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from '../Forms/Layouts/AppInputText.vue'
import { ValidationProvider, ValidationObserver } from '../Validation/index.js'
import PopupWrapper from './Components/PopupWrapper.vue'
import PopupActions from './Components/PopupActions.vue'
import PopupContent from './Components/PopupContent.vue'
import PopupHeader from './Components/PopupHeader.vue'
import CopyInput from '../Inputs/CopyInput.vue'
import ButtonBase from '../UI/Buttons/ButtonBase.vue'
import InfoBox from '../UI/Others/InfoBox.vue'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'CreatePersonalTokenPopup',
    components: {
        ValidationProvider,
        ValidationObserver,
        AppInputText,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        CopyInput,
        InfoBox,
    },
    data() {
        return {
            isLoading: false,
            name: undefined,
            token: undefined,
        }
    },
    methods: {
        async createTokenForm() {
            const isValid = await this.$refs.createToken.validate()

            if (!isValid) return

            this.isLoading = true

            axios
                .post('/api/user/tokens', {
                    name: this.name,
                })
                .then((response) => {
                    this.token = response.data

                    events.$emit('reload-personal-access-tokens')
                })
                .catch(() => this.$isSomethingWrong())
                .finally(() => {
                    this.isLoading = false
                    this.name = undefined
                })
        },
    },
	created() {
		events.$on('popup:close', () => this.token = undefined)
	}
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/inapp-forms';
@import '../../../sass/vuefilemanager/forms';

.dark {
    .info-box {
        background: lighten($dark_mode_foreground, 3%);
    }
}
</style>
