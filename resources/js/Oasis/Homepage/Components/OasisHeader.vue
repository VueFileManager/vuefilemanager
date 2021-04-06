<template>
    <div class="oasis-header">
        <Clouds class="clouds">
            <Cloud type="white" class="cloud" />
            <Cloud type="white" class="cloud" />
            <Cloud type="white" class="cloud" />
            <Cloud type="white" class="cloud" />
            <Cloud type="white" class="cloud" />
        </Clouds>

        <header class="header container">
            <h1 class="main-title">
                Jednoduchý a Bezpečný Cloud vo Vrecku
            </h1>
            <h2 class="sub-title">
                Virtuální šanon, vždy s tebou.
            </h2>

            <div v-if="config.isAuthenticated">
                <router-link :to="{name: 'Files'}" class="theme-button">
                    {{ $t('go_to_files') }}
                </router-link>
            </div>
            <div v-if="! config.isAuthenticated">
                <router-link :to="{name: 'SignUp'}" class="theme-button">
                    Zaregistrujte se
                </router-link>
                <span class="log-in">
                    alebo <router-link :to="{name: 'SignIn'}">prihláste se</router-link>
                </span>
            </div>
        </header>

        <img src="/oasis/header-image.svg" alt="OasisDrive" class="hero">
    </div>
</template>

<script>
    import Clouds from '@/Oasis/Homepage/Components/Clouds'
    import Cloud from '@/Oasis/Homepage/Components/Cloud'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Header',
        components: {
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
            @include transform(scale(-0.4, 0.4));
            top: 12%;
            left: 16%;
        }

        &:nth-child(2) {
            @include transform(scale(-0.8, 0.8));
            top: 17%;
            left: -11%;
        }

        &:nth-child(3) {
            @include transform(scale(1));
            top: 32%;
            left: 8%;
        }

        &:nth-child(4) {
            @include transform(scale(0.55));
            top: 9%;
            right: 21%;
        }

        &:nth-child(5) {
            @include transform(scale(-1.35, 1.35));
            top: 20%;
            right: -2%;
        }
    }

    .oasis-header {
        position: relative;
        margin-top: -110px;
        padding-top: 250px;
        background: linear-gradient(180deg, rgba(149, 189, 230, 0.1) 0%, rgba(255, 255, 255, 0.1) 100%);

        .main-title {
            max-width: 820px;
            margin: 0 auto 20px;
        }

        .sub-title {
            margin-bottom: 47px;
        }
    }

    .hero {
        width: 100%;
        margin-top: -100px;
        margin-bottom: -10px;
    }

    @media only screen and (max-width: 1690px) {
        .header {
            margin-bottom: 40px;
        }
    }

    @media only screen and (max-width: 1370px) {
        .oasis-header {
            padding-top: 210px;
        }

        .clouds .cloud {

            &:nth-child(1) {
                top: 9%;
                left: 10%;
            }

            &:nth-child(2) {
                @include transform(scale(-0.8, 0.8));
                top: 17%;
                left: -11%;
            }

            &:nth-child(3) {
                @include transform(scale(0.8));
                top: 34%;
                left: 3%;
            }

            &:nth-child(4) {
                top: 11%;
                right: 7%;
            }

            &:nth-child(5) {
                @include transform(scale(-1.15, 1.15));
                top: 32%;
                right: -6%;
            }
        }
    }

    @media only screen and (max-width: 1024px) {

        .clouds .cloud {

            &:nth-child(1) {
                top: 10%;
                left: 6%;
                @include transform(scale(-0.8, 0.8));
            }

            &:nth-child(2) {
                @include transform(scale(-0.6, 0.6));
                top: 21%;
                left: -18%;
            }

            &:nth-child(3) {
                @include transform(scale(0.6));
                top: 41%;
                left: 0%;
            }

            &:nth-child(4) {
                @include transform(scale(0.6));
                top: 13%;
                right: -6%;
            }

            &:nth-child(5) {
                @include transform(scale(-0.9, 0.9));
                top: 32%;
                right: -16%;
            }
        }

        .oasis-header {
            padding-top: 160px;

            .main-title {
                max-width: 690px;
                margin-bottom: 10px;
            }
        }

        .header {
            margin-bottom: 60px;
        }
    }

    @media only screen and (max-width: 960px) {
        .header {
            margin-bottom: 100px;
        }

        .oasis-header {
            padding-top: 200px;

            .main-title {
                max-width: 590px;
            }
        }

        .clouds .cloud {

            &:nth-child(1) {
                top: 11%;
                left: -1%;
                @include transform(scale(-0.6, 0.6));
            }

            &:nth-child(2) {
                @include transform(scale(-0.4, 0.4));
                top: 30%;
                left: -24%;
            }

            &:nth-child(3) {
                @include transform(scale(0.6));
                top: 43%;
                left: -5%;
            }

            &:nth-child(4) {
                right: -26%;
            }

            &:nth-child(5) {
                @include transform(scale(-0.7, 0.7));
                top: 38%;
            }
        }
    }

    @media only screen and (max-width: 760px) {

        .clouds .cloud {

            &:nth-child(1) {
                top: 12%;
                left: -36%;
                @include transform(scale(-0.4, 0.4));
            }

            &:nth-child(2) {
                @include transform(scale(-0.4, 0.4));
                top: 65%;
                left: -48%;
            }

            &:nth-child(3) {
                @include transform(scale(0.5));
                top: 48%;
                left: -35%;
            }

            &:nth-child(4) {
                right: -35%;
                top: 15%;
            }

            &:nth-child(5) {
                top: 44%;
                right: -58%;
            }
        }

        .oasis-header .main-title {
            max-width: 500px;
        }

        .header {
            margin-bottom: 120px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .oasis-header {
            background: darken($theme-bg-dark, 10%);

            .main-title {
                color: white;
            }
        }

        .cloud {
            opacity: 0.03;
        }
    }
</style>
