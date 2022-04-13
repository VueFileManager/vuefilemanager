<template>
    <div>
        <div v-if="plan" class="card sticky top-0 z-10 shadow-card" style="padding-bottom: 0">
            <div class="mb-2">
                <h1 class="text-lg font-bold sm:text-xl">
                    {{ plan.attributes.name }}
                </h1>
                <small class="text-xs font-bold dark:text-gray-500 text-gray-500 sm:text-sm">
                    {{ plan.attributes.price }} /
                    {{ $t(`interval.${plan.attributes.interval}`) }}
                </small>
            </div>

            <CardNavigation :pages="pages" class="-mx-1.5" />
        </div>

        <router-view v-if="!isLoading" :plan="plan" />

        <div id="loader" v-if="isLoading">
            <Spinner />
        </div>
    </div>
</template>

<script>
import CardNavigation from '../../../components/UI/Others/CardNavigation'
import Spinner from '../../../components/UI/Others/Spinner'
import axios from 'axios'

export default {
    name: 'FixedPlan',
    components: {
        CardNavigation,
        Spinner,
    },
    data() {
        return {
            isLoading: true,
            plan: undefined,
            pages: [
                {
                    title: this.$t('settings'),
                    route: 'PlanFixedSettings',
                },
                {
                    title: this.$t('subscribers'),
                    route: 'PlanFixedSubscribers',
                },
                {
                    title: this.$t('delete_plan'),
                    route: 'PlanFixedDelete',
                },
            ],
        }
    },
    created() {
        axios
            .get('/api/subscriptions/admin/plans/' + this.$route.params.id)
            .then((response) => {
                this.plan = response.data.data
            })
            .finally(() => {
                this.isLoading = false
            })
    },
}
</script>
