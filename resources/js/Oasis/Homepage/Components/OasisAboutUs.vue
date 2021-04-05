<template>
    <div id="o-nas" class="oasis-about-us">
        <Clouds class="clouds">
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
            <Cloud type="colored" class="cloud" />
        </Clouds>
        <div class="container">
            <div class="content-wrapper">
                <div class="title-wrapper">
                    <h3 class="main-title-sm">
                        Kto Jsme?
                    </h3>
                    <h4 class="sub-title-sm">
                        Jsme česká společnost, která pro fyzické a právnické osoby (živnostníky a firmy) poskytuje cloudové uložiště dle evropské směrnice (GDPR) a následného zákona o zpracování osobních údajů (zákon 110/2019 sb.).
                    </h4>
                </div>
                <ul class="info-list">
                    <li v-for="(item, i) in infoList" :key="i" class="info-list-item">
                        <check-icon size="30" class="icon" />
                        <p class="description">{{ item }}</p>
                    </li>
                </ul>
                <router-link :to="{name: 'SignUp'}" class="base-button theme-color">
                    Vyzkouset OasisDrive
                </router-link>
            </div>
        </div>

        <img src="/oasis/about-us-background.svg" alt="oasis-about-us" class="hero">
    </div>
</template>

<script>
    import Clouds from '@/Oasis/Homepage/Components/Clouds'
    import Cloud from '@/Oasis/Homepage/Components/Cloud'
    import {CheckIcon} from 'vue-feather-icons'

    export default {
        name: 'OasisAboutUs',
        components: {
            CheckIcon,
            Clouds,
            Cloud,
        },
        data() {
            return {
                infoList: [
                    'Zakládáme si na kvalitě a nejlepší možné bezpečnosti pro uchovávání a zpracování dat a dokumentů.',
                    'Spolupracujeme rovněž se specialisty v oboru práva a IT.',
                    'Důležitá je pro nás uživatelská jednoduchost, stoprocentní funkčnost a přehlednost.',
                    'Bezpečnost uložení dat tak, jak nařizuje směrnice EU (GDPR) a zákon o zpracování osobních údajů.',
                ]
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .clouds .cloud {

        &:nth-child(1) {
            opacity: 0.45;
            left: 14%;
            top: 13%;
        }

        &:nth-child(2) {
            opacity: 0.1;
            left: 2%;
            top: 49%;
            @include transform(scale(0.5));
        }

        &:nth-child(3) {
            opacity: 0.15;
            right: -2%;
            top: 10%;
            @include transform(scale(0.55));
        }

        &:nth-child(4) {
            opacity: 0.10;
            right: 16%;
            top: 23%;
            @include transform(scale(-0.6, 0.6));
        }
    }

    .oasis-about-us {
        padding-top: 255px;
        position: relative;

        .sub-title-sm {
            max-width: 890px;
        }

        .content-wrapper {
            margin-bottom: -910px;
            padding-top: 50px;
            padding-left: 22%;
        }

        .hero {
            width: 100%;
            margin-bottom: -10px;
        }
    }

    .info-list {
        margin: 65px 0;

        .info-list-item {
            display: flex;
            align-items: start;
            margin-bottom: 40px;

            &:last-child {
                margin-bottom: 0;
            }

            .icon {
                margin-top: 5px;
                margin-right: 15px;

                polyline {
                    color: $pink;
                }
            }

            .description {
                max-width: 700px;
                @include font-size(24);
                color: $text-primary;
                font-weight: 800;
                line-height: 1.5;
            }
        }
    }

    @media only screen and (max-width: 960px) {
        .info-list {
            margin: 65px 0;

            .info-list-item {
                display: flex;
                align-items: start;
                margin-bottom: 40px;

                .description {
                    max-width: 700px;
                    @include font-size(19);
                    color: $text-primary;
                    font-weight: 800;
                    line-height: 1.5;
                }
            }
        }
    }
</style>
