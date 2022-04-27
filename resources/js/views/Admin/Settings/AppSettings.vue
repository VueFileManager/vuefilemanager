<template>
    <div>
        <!--Page Tab links-->
        <div class="card z-10 shadow-card" style="padding-bottom: 0; padding-top: 0">
            <CardNavigation :pages="pages" class="-mx-1" />
        </div>

        <!--Page Content-->
        <router-view />
    </div>
</template>

<script>
import CardNavigation from '../../../components/UI/Others/CardNavigation'

export default {
    name: 'AppSettings',
    components: {
        CardNavigation,
    },
    data() {
        return {
            pages: [
                {
                    title: this.$t('admin_settings.tabs.others'),
                    route: 'AppOthers',
                },
                {
                    title: this.$t('appearance'),
                    route: 'AppAppearance',
                },
                {
                    title: this.$t('environment'),
                    route: 'AppEnvironment',
                },
                {
                    title: this.$t('server'),
                    route: 'AppServer',
                },
            ],
        }
    },
    mounted() {
		if (this.$route.path === '/admin/settings') {
        	this.$router.push({ name: 'AppOthers' })
		}
    },
}
</script>
