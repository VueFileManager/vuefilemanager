<template>
    <div v-if="isVisibleDisclaimer" class="fixed bottom-0 sm:left-16 left-0 sm:right-auto right-0 sm:p-3 sm:w-56 w-full p-4 shadow-xl rounded-tl-xl rounded-tr-lg dark:bg-dark-foreground bg-white z-20">
		<span @click="closeDisclaimer" class="absolute -right-1 -top-1 p-3 cursor-pointer">
            <x-icon size="10" />
        </span>
        <i18n path="cookie_disclaimer.description" tag="p" class="text-xs">
            <router-link :to="{name: 'DynamicPage', params: {slug: 'cookie-policy'}}" class="text-theme text-xs">
				{{ $t('cookie_disclaimer.button') }}
			</router-link>
        </i18n>
    </div>
</template>

<script>
    import {XIcon} from 'vue-feather-icons'
    import { mapGetters } from 'vuex'

    export default {
        name: 'CookieDisclaimer',
        components: {
            XIcon,
        },
        computed: {
            ...mapGetters([
				'config'
			]),
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
            this.isVisibleDisclaimer = !localStorage.getItem('isHiddenDisclaimer');
        }
    }
</script>
