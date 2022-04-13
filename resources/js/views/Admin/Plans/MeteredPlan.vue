<template>
    <div>
        <div v-if="plan" class="card sticky top-0 z-10 shadow-card" style="padding-bottom: 0">
            <div class="mb-2">
                <h1 class="text-lg font-bold sm:text-xl">
                    {{ plan.attributes.name }}
                </h1>
                <small class="text-xs font-bold dark:text-gray-500 text-gray-500 sm:text-sm">
                    {{ $t('x_days_intervals') }}
                </small>
            </div>

            <!--Navigation-->
            <CardNavigation :pages="pages" class="-mx-1" />
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
import { mapGetters } from 'vuex'

export default {
    name: 'MeteredPlan',
    components: {
        CardNavigation,
        Spinner,
    },
    computed: {
        ...mapGetters(['config']),
        pages() {
            let pages = [
                {
                    title: this.$t('settings'),
                    route: 'PlanMeteredSettings',
                },
                {
                    title: this.$t('subscribers'),
                    route: 'PlanMeteredSubscribers',
                },
            ]

            if (this.plan && this.plan.attributes.status === 'active') {
                pages.push({
                    title: this.$t('delete_plan'),
                    route: 'PlanMeteredDelete',
                })
            }

            return pages
        },
    },
    data() {
        return {
            isLoading: true,
            plan: undefined,
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
