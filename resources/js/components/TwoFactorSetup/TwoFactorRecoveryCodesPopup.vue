<template>
    <PopupWrapper name="two-factor-recovery-codes">
        <PopupHeader :title="$t('your_security_codes')" icon="key" />

        <PopupContent style="padding: 0 20px">
            <div class="mobile-actions">
                <MobileActionButton @click.native="copyCodes" icon="copy">
                    {{ $t('copy') }}
                </MobileActionButton>

                <MobileActionButton @click.native="downloadCodes" icon="download">
                    {{ $t('download') }}
                </MobileActionButton>

                <MobileActionButton @click.native="regenerateCodes" icon="refresh">
                    {{ $t('regenerate_codes') }}
                </MobileActionButton>
            </div>

            <ul v-if="!isLoading" class="codes-list">
                <li v-for="(code, i) in codes" :key="i">{{ code }}</li>
            </ul>

            <div v-if="isLoading" class="spinner-wrapper">
                <Spinner />
            </div>

            <textarea v-model="inputCodes" ref="codes" class="codes-output"></textarea>

            <InfoBox style="margin-bottom: 0">
                <p v-html="$t('popup_2fa.popup_codes_disclaimer')"></p>
            </InfoBox>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="theme">
                {{ $t('awesome_iam_done') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import MobileActionButton from '../UI/Buttons/MobileActionButton'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupActions from '../Popups/Components/PopupActions'
import PopupContent from '../Popups/Components/PopupContent'
import PopupHeader from '../Popups/Components/PopupHeader'
import ButtonBase from '../UI/Buttons/ButtonBase'
import InfoBox from '../UI/Others/InfoBox'
import Spinner from '../UI/Others/Spinner'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'TwoFactorRecoveryCodesPopup',
    components: {
        MobileActionButton,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        Spinner,
        InfoBox,
    },
    data() {
        return {
            codes: undefined,
            isLoading: true,
            inputCodes: undefined,
        }
    },
    computed: {
        ...mapGetters(['user']),
    },
    methods: {
        copyCodes() {
            let copyText = this.$refs.codes

            copyText.select()
            copyText.setSelectionRange(0, 99999)

            document.execCommand('copy')

            events.$emit('toaster', {
                type: 'success',
                message: this.$t('popup_2fa.toaster_codes_copied'),
            })
        },
        downloadCodes() {
            // Create txt content
            let recoveryCodes = 'data:x-application/xml;charset=utf-8,' + escape(this.codes.join('\n'))

            // Create download link
            let downloadLink = document.createElement('a')

            downloadLink.href = recoveryCodes
            downloadLink.download = 'recovery-codes.txt'

            // Download .txt
            document.body.appendChild(downloadLink)
            downloadLink.click()
            document.body.removeChild(downloadLink)
        },
        regenerateCodes() {
            this.isLoading = true

            axios
                .post('/user/two-factor-recovery-codes')
                .then(() => {
                    this.getRecoveryCodes()

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('popup_2fa.toaster_codes_regenerated'),
                    })
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => (this.isLoading = false))
        },
        getRecoveryCodes() {
            axios
                .get('/user/two-factor-recovery-codes')
                .then((response) => {
                    this.codes = response.data
                    this.inputCodes = response.data.join('\n')
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => (this.isLoading = false))
        },
    },
    created() {
        events.$on('popup:open', ({ name }) => {
            if ('two-factor-recovery-codes' === name) this.getRecoveryCodes()
        })
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.mobile-actions {
    white-space: nowrap;
    overflow-x: auto;
    margin: 0 -20px;
    padding: 10px 0 10px 20px;
}

.codes-list {
    margin: 5px 0 15px;
    padding-left: 30px;

    li {
        @include font-size(14);
        font-weight: bold;
        padding: 10px 0;
        border-bottom: 1px solid $light_mode_border;
        list-style: circle;

        &:last-child {
            border-bottom: none;
        }
    }
}

.codes-output {
    position: absolute;
    right: -9999px;
}

.spinner-wrapper {
    height: 339px;
    position: relative;

    .spinner {
        top: 46% !important;
    }
}

.dark {
    .codes-list {
        li {
            border-color: $dark_mode_border_color;
        }
    }

    .info-box,
    .mobile-action-button {
        background: lighten($dark_mode_foreground, 3%);
    }
}
</style>
