<template>
    <div id="cenik" class="oasis-pricing text-center">

        <Clouds class="clouds">
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
        </Clouds>

        <div class="title-wrapper container">
            <h3 class="main-title-sm">
                Kolik stoji OasisDrive?
            </h3>
            <h4 class="sub-title-sm">
                Cloudové uložiště je v podstatě virtuální šanon, kam uživatelé ukládají svá data, ke kterým se mohou přihlásit odkudkoli v nezávislosti na zařízení. OasisDrive umožňuje bezpečně chránit Vaše firemní data.
            </h4>
        </div>

        <div class="container">
            <img src="/oasis/trees.svg" alt="oasis-trees" class="trees">

            <ul class="pricing-box">
                <li v-for="(plan, i) in pricing" :key="i" class="box">
                    <folder-icon size="34" class="pricing-icon" />

                    <h5 class="pricing-data">
                        {{ plan.data.attributes.capacity_formatted }}
                    </h5>
                    <b class="pricing-name">
                        {{ plan.data.attributes.name }}
                    </b>
                    <p class="pricing-description">
                        {{ plan.data.attributes.description }}
                    </p>
                    <router-link :to="{name: 'SignUp'}" :class="{'base-button': i == 1,'theme-button': i !== 1, }">
                        {{ plan.data.attributes.price }}/{{ $t('global.monthly_ac') }}
                    </router-link>
                    <small v-if="plan.data.attributes.tax_rates.length > 0" class="pricing-vat">
                        {{ $t('page_pricing_tables.vat_excluded') }}
                    </small>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import {
        FolderIcon,
    } from 'vue-feather-icons'
    import Clouds from '@/Oasis/Homepage/Components/Clouds'
    import Cloud from '@/Oasis/Homepage/Components/Cloud'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'OasisPricing',
        components: {
            FolderIcon,
            Clouds,
            Cloud,
        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                pricing: [{
                    "data": {
                        "id": "virtualni-sanon-basic",
                        "type": "plans",
                        "attributes": {
                            "name": "Virtu\u00e1ln\u00ed \u0161anon BASIC",
                            "description": "Obsahuje 5 GB pro Va\u0161e firemn\u00ed data",
                            "price": "CZK\u00a0699.00",
                            "capacity_formatted": "50GB",
                            "capacity": 50,
                            "currency": "CZK",
                            "tax_rates": [{"id": "txr_1IYQK9BwlPpoyJNw2lR0zgOr", "active": true, "country": "CZ", "percentage": 20, "plan_price_formatted": "CZK\u00a0838.80"}]
                        }
                    }
                }, {
                    "data": {
                        "id": "virtualni-sanon-standard",
                        "type": "plans",
                        "attributes": {
                            "name": "Virtu\u00e1ln\u00ed \u0161anon STANDARD",
                            "description": "Obsahuje 10 GB pro Va\u0161e firemn\u00ed data, 2 dokumenty zdarma",
                            "price": "CZK\u00a0799.00",
                            "capacity_formatted": "100GB",
                            "capacity": 100,
                            "currency": "CZK",
                            "tax_rates": [{"id": "txr_1IYQK9BwlPpoyJNw2lR0zgOr", "active": true, "country": "CZ", "percentage": 20, "plan_price_formatted": "CZK\u00a0958.80"}]
                        }
                    }
                }, {
                    "data": {
                        "id": "virtualni-sanon-premium",
                        "type": "plans",
                        "attributes": {
                            "name": "Virtu\u00e1ln\u00ed \u0161anon PREMIUM",
                            "description": "Obsahuje 20 GB pro Va\u0161e firemn\u00ed data, 5 dokument\u016f dle vlastn\u00edho v\u00fdb\u011bru zdarma",
                            "price": "CZK\u00a01,249.00",
                            "capacity_formatted": "200GB",
                            "capacity": 200,
                            "currency": "CZK",
                            "tax_rates": [{"id": "txr_1IYQK9BwlPpoyJNw2lR0zgOr", "active": true, "country": "CZ", "percentage": 20, "plan_price_formatted": "CZK\u00a01,498.80"}]
                        }
                    }
                }]
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

    .clouds .cloud {

        &:nth-child(1) {
            opacity: 0.2;
            left: 4%;
            top: 15%;
            @include transform(scale(1.1))
        }

        &:nth-child(2) {
            opacity: 0.15;
            left: 18%;
            top: 41%;
            @include transform(scale(-0.7, 0.7))
        }

        &:nth-child(3) {
            opacity: 0.30;
            right: 9%;
            top: 6%;
            @include transform(scale(-1.55, 1.55))
        }

        &:nth-child(4) {
            opacity: 0.25;
            right: 16%;
            top: 32%;
            @include transform(scale(1))
        }
    }

    .oasis-pricing {
        margin-top: 290px;
        position: relative;

        .main-title-sm,
        .sub-title-sm {
            margin-left: auto;
            margin-right: auto;
        }

        .title-wrapper {
            padding-top: 220px;
            margin-bottom: 110px;
        }

        .theme-button {
            @include font-size(18);
        }
    }

    .trees {
        margin-bottom: -95px;
        margin-left: 250px;
    }

    .pricing-box {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        max-width: 1325px;
        margin: 0 auto;

        .box {
            background: white;
            box-shadow: 0 5px 333px -22px #1B253935;
            border-radius: 12px;
            display: block;
            padding: 38px 40px 35px;
            z-index: 1;

            &:nth-child(2) {
                z-index: 2;
                padding: 65px 40px 20px;
                background: $theme-bg-light;
                margin-top: -40px;
                margin-bottom: -40px;

                .pricing-name, .pricing-data {
                    color: white;
                }

                .pricing-description {
                    color: $text-dark-secondary;
                    margin-bottom: 80px;
                }

                .pricing-data {
                    margin-top: 10px;
                }

                .pricing-vat {
                    color: $text-dark-subtitle;
                }
            }

            .pricing-icon {
                margin-bottom: 45px;

                path {
                    color: $theme;
                }
            }

            .pricing-data {
                @include font-size(50);
                font-weight: 900;
                margin-bottom: 10px;
            }

            .pricing-name {
                @include font-size(24);
                font-weight: 800;
                margin-bottom: 30px;
                display: block;
            }

            .pricing-description {
                @include font-size(19);
                margin-bottom: 50px;
            }

            .pricing-vat {
                @include font-size(12);
                color: $text-secondary;
                display: block;
                font-weight: 300;
                opacity: 0.45;
                margin-top: 15px;
            }
        }
    }

    @media only screen and (max-width: 960px) {
        .pricing-box {
            grid-template-columns: 1fr;
            gap: 25px;

            .box {

                &:nth-child(2) {
                    padding: 38px 40px 35px;
                    margin-top: 0px;
                    margin-bottom: 0px;

                    .pricing-data {
                        margin-top: 0;
                    }

                    .pricing-description {
                        margin-bottom: 50px;
                    }
                }
            }
        }
    }
</style>
