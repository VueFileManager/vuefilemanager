<template>
    <div class="oasis-header">
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
    import { mapGetters } from 'vuex'

    export default {
        name: 'Header',
        components: {

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

    .oasis-header {
        margin-top: -110px;
        padding-top: 250px;
        background: linear-gradient(180deg, rgba(149, 189, 230, 0.1) 0%, rgba(255, 255, 255, 0.1) 100%);

        .main-title {
            max-width: 820px;
            margin: 0 auto;
        }

        .sub-title {
            margin-bottom: 50px;
        }
    }

    .hero {
        width: 100%;
        margin-top: -100px;
        margin-bottom: -10px;
    }
</style>
