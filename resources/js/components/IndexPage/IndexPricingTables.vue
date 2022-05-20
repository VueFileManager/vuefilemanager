<template>
    <div
        class="page-wrapper medium pricing"
        v-if="!isEmpty && index.section_pricing_content === '1'"
    >
        <div id="pricing" class="page-title center">
            <h1 class="title" v-html="index.pricing_title"></h1>
        </div>

        <PricingTables class="pricing-tables" @load="pricingLoaded" />

        <div class="page-title center">
            <h2 class="description">
                {{ index.pricing_description }}
            </h2>
            <router-link class="sign-up-button" :to="{ name: 'SignUp' }">
                <AuthButton class="button" icon="chevron-right" :text="$t('page_index.sign_up_button')" />
            </router-link>
        </div>

        <cloud-icon size="800" class="cloud-bg"></cloud-icon>
        <cloud-icon size="560" class="cloud-bg"></cloud-icon>
    </div>
</template>

<script>
import PricingTables from './Components/PricingTables'
import AuthButton from '../UI/Buttons/AuthButton'
import { CloudIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'IndexPricingTables',
    components: {
        PricingTables,
        AuthButton,
        CloudIcon,
    },
    computed: {
        ...mapGetters(['index', 'config']),
    },
    data() {
        return {
            isEmpty: false,
        }
    },
    methods: {
        pricingLoaded(pricing) {
            if (pricing.length === 0) this.isEmpty = true
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/landing-page';
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.pricing {
    .cloud-bg {
        z-index: 0;

        path {
            stroke: none;
            fill: rgba($theme, 0.05);
        }

        &:first-of-type {
            position: absolute;
            top: 30px;
            right: -130px;
            transform: scale(-1, 1) rotate(-17deg);
        }

        &:last-of-type {
            position: absolute;
            bottom: 160px;
            left: -230px;
            transform: rotate(13deg);
        }
    }
}

.page-title {
    position: relative;
    z-index: 1;

    &.center {
        text-align: center;

        .title {
            margin-left: auto;
            margin-right: auto;
        }

        .description {
            margin-left: auto;
            margin-right: auto;
        }
    }

    .title {
        max-width: 580px;
        font-size: 48px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 15px;

        /deep/ span {
            font-size: 48px;
        }
    }

    .description {
        max-width: 580px;
        @include font-size(20);
        font-weight: 500;
        line-height: 1.6;
        margin-bottom: 30px;
    }
}

.pricing {
    padding-top: 250px;
    padding-bottom: 120px;
}

.pricing-tables {
    margin-top: 50px;
    margin-bottom: 60px;
    position: relative;
    z-index: 1;
}

.sign-up-button {
    padding-top: 10px;
    display: block;
}

@media only screen and (max-width: 1190px) {
    .cloud-bg {
        display: none;
    }

    .pricing {
        padding-top: 150px;
        padding-bottom: 60px;
    }
}

@media only screen and (max-width: 960px) {
    .page-title {
        .title {
            font-size: 28px;
            line-height: 1.25;
            margin-bottom: 15px;

            /deep/ span {
                font-size: 28px;
            }
        }

        .description {
            @include font-size(16);
            line-height: 1.6;
            margin-bottom: 30px;
        }
    }

    .pricing {
        padding-top: 50px;
        padding-bottom: 120px;
    }
}
</style>
