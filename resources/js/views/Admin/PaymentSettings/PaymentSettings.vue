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
    name: 'PaymentSettings',
    components: {
        CardNavigation,
    },
    data() {
        return {
            pages: [
                {
                    title: this.$t('payments'),
                    route: 'AppPayments',
                },
                {
                    title: this.$t('billings'),
                    route: 'AppBillings',
                },
            ],
        }
    },
    mounted() {
        this.$router.replace({ name: 'AppPayments' })
    },
}
</script>
