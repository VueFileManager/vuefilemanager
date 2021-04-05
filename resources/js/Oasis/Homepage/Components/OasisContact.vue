<template>
    <div id="kontakt-a-podpora" class="oasis-contact">
        <div class="container content-position">
            <div class="title-wrapper">
                <h3 class="main-title-sm">
                    Kontakt a Podpora
                </h3>
                <h4 class="sub-title-sm">
                    Jsme česká společnost, která pro fyzické a právnické osoby (živnostníky a firmy) poskytuje cloudové uložiště dle evropské směrnice.
                </h4>
            </div>

            <div class="info-container">
                <div class="info-grid">
                    <div class="info-wrapper">
                        <b class="info-title">Společnost:</b>
                        <p class="info-description">GDPR Cloud Solution, s.r.o., </p>
                        <p class="info-description">ICO: 08995281</p>
                        <p class="info-description">Sídlo: Zbraslavská 12/11, Malá Chuchle, 159 00 Praha 5</p>
                    </div>

                    <div class="info-wrapper">
                        <b class="info-title">Prodej:</b>
                        <div class="info-group">
                            <p class="info-description">John Doe</p>
                            <p class="info-description">+420 922 123 456</p>
                        </div>
                        <div class="info-group">
                            <p class="info-description">Jane Does</p>
                            <p class="info-description">+420 982 510 182</p>
                        </div>
                    </div>

                    <div class="info-wrapper">
                        <b class="info-title">Podpora:</b>
                        <p class="info-description">podpora@oasisdrive.cz</p>
                        <p class="info-description">+420 922 123 456</p>
                    </div>

                    <div class="info-wrapper">
                        <b class="info-title">Dokumenty:</b>

                        <div class="info-icon">
                            <file-text-icon size="22" />
                            <a href="/oasis/eu-smernice-gdpr.pdf" target="_blank" class="info-description">Směrnice EU (GDPR)</a>
                        </div>
                    </div>

                </div>
                <ValidationObserver v-if="! isSuccess" @submit.prevent="contactForm" ref="contactForm" v-slot="{ invalid }" tag="form" class="contact-form">
                    <b class="info-title">Zanechte nám vzkaz:</b>
                    <div class="block-wrapper">
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required" v-slot="{ errors }">
                            <input v-model="contact.email" :placeholder="$t('page_contact_us.form.email_plac')" type="email" class="focus-border-theme" :class="{'is-error': errors[0]}" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Message" rules="required" v-slot="{ errors }">
                            <textarea v-model="contact.message" :placeholder="$t('page_contact_us.form.message_plac')" rows="7" class="focus-border-theme" :class="{'is-error': errors[0]}"></textarea>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <InfoBox v-if="isError">
                        <p>{{ $t('page_contact_us.error_message') }}</p>
                    </InfoBox>
                    <button :disabled="isLoading" class="base-button theme-color" type="submit">
                        {{ $t('page_contact_us.form.submit_button') }}
                    </button>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {FileTextIcon} from 'vue-feather-icons'
    import axios from 'axios'

    export default {
        name: 'OasisAboutUs',
        components: {
            ValidationProvider,
            ValidationObserver,
            FileTextIcon,
            required,
            InfoBox,
        },
        data() {
            return {
                isLoading: false,
                isSuccess: false,
                isError: false,
                contact: {
                    email: '',
                    message: '',
                },
            }
        },
        methods: {
            async contactForm() {

                // Validate fields
                const isValid = await this.$refs.contactForm.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post('/api/contact', this.contact)
                    .then(() => {
                        this.isSuccess = true
                    })
                    .catch(() => {
                        this.isError = true
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .oasis-contact {
        background: $theme-bg-dark;
        padding-top: 90px;
        padding-bottom: 90px;

        .title-wrapper {
            margin-bottom: 65px;
        }

        .main-title-sm {
            color: white;
        }

        .sub-title-sm {
            color: $text-dark-subtitle;
            max-width: 750px;
        }

        .info-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-grid {
            gap: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }

    .contact-form {
        width: 100%;

        .block-wrapper {
            margin-bottom: 30px;
        }

        .error-message {
            color: $pink;
            margin-top: 5px;
            display: block;
        }

        input, textarea {
            background: $theme-bg-light;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 13px 20px;
            appearance: none;
            outline: 0;
            color: $text-dark-secondary;
            @include font-size(16);
            font-weight: 600;
            width: 100%;

            &.is-error {
                border-color: $pink;
            }

            &::placeholder {
                color: $text-dark-subtitle;
                @include font-size(16);
                font-weight: 600;
            }
        }
    }

    @media only screen and (max-width: 960px) {
        .oasis-contact {

            .info-container {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .info-grid {
                gap: 0;
                grid-template-columns: 1fr;
            }
        }

        .info-wrapper {
            padding-bottom: 30px;

            .info-description {
                @include font-size(18);
                margin-bottom: 3px;
            }
        }

        .contact-form {
            padding-bottom: 40px;

            button {
                width: 100%;
            }
        }
    }
</style>
