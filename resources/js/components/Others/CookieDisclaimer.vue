<template>
    <div class="cookie-wrapper" v-if="isVisibleDisclaimer && config.isSaaS">
        <span @click="closeDisclaimer" class="close-icon">
            <x-icon size="12"></x-icon>
        </span>
        <i18n path="cookie_disclaimer.description" tag="p">
            <router-link :to="{name: 'DynamicPage', params: {slug: 'cookie-policy'}}">{{ $t('cookie_disclaimer.button') }}</router-link>
        </i18n>
    </div>
</template>

<script>
    import {XIcon} from 'vue-feather-icons'
    import { mapGetters } from 'vuex'

    export default {
        name: 'CookieDisclaimer',
        components: {
            XIcon
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isVisibleDisclaimer: false
            }
        },
        methods: {
            closeDisclaimer() {
                localStorage.setItem('isHiddenDisclaimer', 'true')

                this.isVisibleDisclaimer = false
            }
        },
        created() {
            this.isVisibleDisclaimer = localStorage.getItem('isHiddenDisclaimer') ? false : true;
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .cookie-wrapper {
        @include widget-card;
        position: fixed;
        bottom: 0;
        left: 115px;
        max-width: 225px;
        z-index: 3;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;

        .close-icon {
            position: absolute;
            right: -4px;
            top: -4px;
            cursor: pointer;
            padding: 12px;

            line {
                stroke: $text-muted;
            }
        }

        p {
            font-size: 12px;
            line-height: 1.6;

            a {
                font-size: 12px;
                color: $theme;
            }
        }
    }

    @media only screen and (max-width: 690px) {
        .cookie-wrapper {
            padding: 10px 15px;
            left: 0;
            right: 0;
            max-width: 100%;

            p {
                max-width: 300px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .cookie-wrapper {
            background: $dark_mode_foreground;

            p {
                color: $dark_mode_text_primary;
            }

            .close-icon {

                line {
                    stroke: $dark_mode_text_primary;
                }
            }
        }
    }
</style>
