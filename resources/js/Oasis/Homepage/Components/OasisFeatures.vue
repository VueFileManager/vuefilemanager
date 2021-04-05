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
