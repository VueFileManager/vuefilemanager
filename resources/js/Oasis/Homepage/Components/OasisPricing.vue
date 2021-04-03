<template>
    <div class="oasis-pricing text-center">

        <div class="title-wrapper container">
            <h3 class="main-title">
                Kolik stoji OasisDrive?
            </h3>
            <h4 class="sub-title-sm">
                Cloudové uložiště je v podstatě virtuální šanon, kam uživatelé ukládají svá data, ke kterým se mohou přihlásit odkudkoli v nezávislosti na zařízení. OasisDrive umožňuje bezpečně chránit Vaše firemní data.
            </h4>
        </div>

        <ul class="pricing-box container">
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
</template>

<script>
    import {
        FolderIcon,
    } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'OasisPricing',
        components: {
            FolderIcon,
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
</style>
