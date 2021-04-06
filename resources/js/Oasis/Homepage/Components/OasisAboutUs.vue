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
                        {{ $t('homepage_about_us_title') }}
                    </h3>
                    <h4 class="sub-title-sm">
                        {{ $t('homepage_about_us_description') }}
                    </h4>
                </div>
                <ul class="info-list">
                    <li v-for="(item, i) in infoList" :key="i" class="info-list-item">
                        <check-icon size="30" class="icon" />
                        <p class="description">{{ item }}</p>
                    </li>
                </ul>
                <router-link :to="{name: 'SignUp'}" class="base-button theme-color">
                    {{ $t('try_drive') }}
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
                    this.$t('homepage_about_us_line_1'),
                    this.$t('homepage_about_us_line_2'),
                    this.$t('homepage_about_us_line_3'),
                    this.$t('homepage_about_us_line_4'),
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
            align-items: flex-start;
            margin-bottom: 40px;

            &:last-child {
                margin-bottom: 0;
            }

            .icon {
                margin-top: 5px;
                margin-right: 15px;

                polyline {
                    stroke: $pink;
                    stroke-width: 2;
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

    @media only screen and (max-width: 1750px) {
        .oasis-about-us {

            .content-wrapper {
                margin-bottom: -610px;
                padding-top: 50px;
                padding-left: 15%;
            }
        }
    }

    @media only screen and (max-width: 1490px) {
        .oasis-about-us {

            .content-wrapper {
                margin-bottom: -440px;
                padding-top: 50px;
                padding-left: 15%;
            }
        }
    }

    @media only screen and (max-width: 1370px) {
        .clouds .cloud {

            &:nth-child(1) {
                left: 5%;
            }

            &:nth-child(2) {
                left: 20%;
                top: 83%;
            }

            &:nth-child(3) {
                right: 1%;
                top: 9%;
            }

            &:nth-child(4) {
                right: 20%;
                top: 18%;
            }
        }
    }

    @media only screen and (max-width: 1024px) {
        .oasis-about-us {
            padding-top: 120px;

            .content-wrapper {
                margin-bottom: -310px;
                padding-top: 50px;
                padding-left: 0;
            }
        }

        .info-list .info-list-item .description {
            @include font-size(21);
            max-width: 600px;
        }

        .clouds .cloud {

            &:nth-child(1) {
                display: none;
            }

            &:nth-child(2) {
                left: 23%;
                top: 81%;
            }

            &:nth-child(3) {
                right: -6%;
                top: 42%;
            }

            &:nth-child(4) {
                right: 17%;
                top: 56%;
                @include transform(scale(-0.5, 0.5));
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .oasis-about-us .content-wrapper {
            margin-bottom: -220px;
        }

        .info-list {
            margin: 65px 0;

            .info-list-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 40px;

                .description {
                    max-width: 540px;
                    @include font-size(19);
                    color: $text-primary;
                    font-weight: 800;
                    line-height: 1.5;
                }
            }
        }
    }

    @media only screen and (max-width: 760px) {
        .oasis-about-us {
            padding-top: 30px;

            .content-wrapper {
                margin-bottom: -70px;
            }
        }

        .info-list {
            margin: 35px 0;
        }

        .clouds .cloud {

            &:nth-child(3),
            &:nth-child(4) {
                display: none;
            }

            &:nth-child(2) {
                left: 3%;
                top: 86%;
                @include transform(scale(0.35));
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .info-list .info-list-item {

            .description {
                color: $dark-mode-text;
            }
        }
    }
</style>
