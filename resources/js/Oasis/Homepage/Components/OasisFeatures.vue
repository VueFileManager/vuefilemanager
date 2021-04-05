<template>
    <div class="oasis-features text-center">

        <div class="title-wrapper container">
            <h3 class="main-title-sm">
                Proč je OasisDrive Výnimočný?
            </h3>
            <h4 class="sub-title-sm">
                Cloudové uložiště je v podstatě virtuální šanon, kam uživatelé ukládají svá data, ke kterým se mohou přihlásit odkudkoli v nezávislosti na zařízení. OasisDrive umožňuje bezpečně chránit Vaše firemní data.
            </h4>
        </div>

        <ul class="features-box container">
            <li v-for="(feature, i) in features" :key="i" class="box">
                <cloud-icon v-if="feature.icon === 'cloud'" size="72" class="box-icon" />
                <file-text-icon v-if="feature.icon === 'file'" size="72" class="box-icon" />
                <lock-icon v-if="feature.icon === 'lock'" size="72" class="box-icon" />

                <h5 class="box-title">
                    {{ feature.title }}
                </h5>
                <p class="box-description">
                    {{ feature.excerp }}
                </p>
                <div class="box-more-info">
                    <span>Více Informací</span>
                    <chevron-right-icon size="18" class="icon"/>
                </div>
            </li>
        </ul>

        <router-link :to="{name: 'SignUp'}" class="base-button theme-color cta">
            Vyzkouset OasisDrive
        </router-link>

        <img src="/oasis/wave-background.svg" alt="oasis wave background" class="wave-background">
    </div>
</template>

<script>
    import {
        ChevronRightIcon,
        FileTextIcon,
        CloudIcon,
        LockIcon,
    } from 'vue-feather-icons'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'OasisFeatures',
        components: {
            ChevronRightIcon,
            FileTextIcon,
            CloudIcon,
            LockIcon,
        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                features: [
                    {
                        icon: 'lock',
                        title: 'Bezpecnost na prvnim miste',
                        excerp: 'Naše cloudové uložiště umožňuje bezpečně chránit Vaše firemní data. kdy využíváme několik vysoce zabezpečených serverů po Evropské unii a vícenásobným šifrováním souborů (tzv. kryptováním). ',
                        content: '',
                    },
                    {
                        icon: 'file',
                        title: 'Bezpečnost v rámci GDPR',
                        excerp: 'bezpečnost uložení dat v rámci evropské směrnice o GDPR a přidružených zákonů o zpracování a ochraně citlivých údajů. Naši klienti jsou tedy chráněni před zneužitím dat a tím pádem i před pokutami vyplývajícími z legislativy, které být astronomické. ',
                        content: '',
                    },
                    {
                        icon: 'cloud',
                        title: 'Jednoduchost pouzivani',
                        excerp: 'Naše cloudové uložiště umožňuje bezpečně chránit Vaše firemní data. kdy využíváme několik vysoce zabezpečených serverů po Evropské unii a vícenásobným šifrováním souborů (tzv. kryptováním). ',
                        content: '',
                    },
                ]
            }
        },
        mounted() {

        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .oasis-features {
        background: $theme-bg-dark;
        padding-top: 90px;
        position: relative;

        .title-wrapper {
            margin-bottom: 90px;
        }

        .main-title-sm {
            color: white;
            max-width: 690px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }

        .sub-title-sm {
            margin: 0 auto;
            color: $text-dark-subtitle;
        }

        .features-box {
            margin-bottom: -230px;
        }

        .cta {
            top: 300px;
            position: relative;
        }
        
        .wave-background {
            transform: translateY(100%);
            position: absolute;
            width: 100%;
            z-index: 0;
            bottom: 3px;
            left: 0;
            right: 0;
        }
    }

    .features-box {
        grid-template-columns: 1fr 1fr 1fr;
        position: relative;
        display: grid;
        z-index: 1;
        gap: 60px;

        .box {
            background: $theme-bg-light;
            box-shadow: 0 22px 34px -20px #232142;
            border-radius: 12px;
            text-align: center;
            padding: 40px 25px 45px;
            display: block;

            .box-title {
                color: white;
                margin-top: 22px;
                margin-bottom: 25px;
                @include font-size(32);
            }

            .box-description {
                color: $text-dark-secondary;
                @include font-size(19);
                line-height: 1.55;
                margin-bottom: 25px;
            }

            .box-more-info {
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;

                .icon {
                    margin-left: 5px;

                    polyline {
                        color: $theme;
                        @include transition(150ms);
                    }
                }

                span {
                    @include font-size(17);
                    color: $theme;
                    font-weight: 700;
                    @include transition(150ms);
                }

                &:hover {
                    span, polyline {
                        color: lighten($theme, 10%);
                    }
                }
            }

            &:nth-child(1) {

                .box-icon {
                    rect, path {
                        color: $theme;
                    }
                }
            }

            &:nth-child(2) {

                .box-icon {
                    line, polyline, path {
                        color: $cyan;
                    }
                }
            }

            &:nth-child(3) {

                .box-icon {
                    path {
                        color: $pink;
                    }
                }
            }
        }
    }

    @media only screen and (max-width: 960px) {
        .features-box {
            grid-template-columns: 1fr;
            gap: 30px;

            .box {

                .box-title {
                    @include font-size(26);
                }

                .box-description {
                    @include font-size(18);
                    line-height: 1.55;
                }
            }
        }
    }
</style>
