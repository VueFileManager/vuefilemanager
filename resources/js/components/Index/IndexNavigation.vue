<template>
    <nav class="main-navigation">
        <router-link :to="{ name: 'Homepage' }" tag="div" class="logo">
            <img
				class="max-h-6"
                v-if="config.app_logo_horizontal"
                :src="$getImage(logoSrc)"
                :alt="config.app_name"
            />
            <b v-if="!config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
        </router-link>
        <div class="navigation">
            <ul class="navigation-links">
                <!--<li v-if="config.stripe_public_key">
                    <a href="/#pricing">
                        {{ $t('pricing') }}
                    </a>
                </li>-->
                <li>
                    <router-link :to="{ name: 'ContactUs' }" class="hover-text-theme">
                        {{ $t('contact_us') }}
                    </router-link>
                </li>
            </ul>
            <ul v-if="!config.isAuthenticated" class="navigation-links">
                <li>
                    <router-link :to="{ name: 'SignIn' }" class="hover-text-theme">
                        {{ $t('log_in') }}
                    </router-link>
                </li>
                <li v-if="config.userRegistration">
                    <router-link class="cta-button text-theme bg-theme-100" :to="{ name: 'SignUp' }">
                        {{ $t('page_index.menu.sign_in') }}
                    </router-link>
                </li>
            </ul>
            <ul v-if="config.isAuthenticated" class="navigation-links">
                <li>
                    <router-link class="cta-button text-theme bg-theme-100" :to="{ name: 'Files' }">
                        {{ $t('go_to_files') }}
                    </router-link>
                </li>
            </ul>
        </div>
        <router-link class="cta-button log-in text-theme bg-theme-100" :to="{ name: 'SignIn' }">
            {{ $t('log_in') }}
        </router-link>
    </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'IndexNavigation',
    computed: {
        ...mapGetters(['config', 'index', 'isDarkMode']),
		logoSrc() {
			return this.isDarkMode && this.config.app_logo_horizontal ? this.config.app_logo_horizontal_dark : this.config.app_logo_horizontal
		}
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/landing-page';
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.main-navigation {
    justify-content: space-between;
    padding-bottom: 25px;
    align-items: center;
    padding-top: 25px;
    display: flex;
}

.logo {
    cursor: pointer;

    img {
        cursor: pointer;
        height: 38px;
        width: auto;
    }

    .logo-text {
        font-weight: 800;
        @include font-size(25);
    }
}

.navigation-links {
    display: inline-block;
    margin-left: 25px;

    &:first-child {
        margin-left: 0;
    }

    li {
        display: inline-block;

        a {
            padding: 14px;
            font-weight: 700;
            @include font-size(17);
            @include transition(150ms);
        }
    }
}

.cta-button {
    border-radius: 6px;
    padding: 8px 23px;
    @include font-size(17);
    font-weight: 700;

    &.log-in {
        display: none;
    }
}

@media only screen and (max-width: 690px) {
    .navigation {
        display: none;
    }

    .logo {
        img {
            height: auto;
            width: 190px;
        }
    }

    .cta-button.log-in {
        display: block;
    }
}
</style>
