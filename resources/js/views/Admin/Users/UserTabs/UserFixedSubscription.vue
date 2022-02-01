<template>
    <div class="card shadow-card">
        <FormLabel>
            {{ $t('Subscription') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-xl font-extrabold sm:text-3xl">
            {{ status }}
        </b>

        <b class="mb-3 mb-8 block text-sm text-gray-400">
            {{ subscription.relationships.plan.data.attributes.name }} /
            {{ price }}
        </b>

        <div v-for="(limit, i) in limitations" :key="i" :class="{ 'mb-6': Object.keys(limitations).length - 1 !== i }">
            <b class="mb-3 block text-sm text-gray-400">
                {{ limit.message }}
            </b>
            <ProgressLine :data="limit.distribution" />
        </div>
    </div>
</template>
<script>
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import ProgressLine from '../../../../components/Admin/ProgressLine'
import { mapGetters } from 'vuex'

export default {
    name: 'UserFixedSubscription',
    props: ['subscription', 'user'],
    components: {
        ProgressLine,
        FormLabel,
    },
    computed: {
        status() {
            return {
                active: `Active until ${this.subscription.attributes.renews_at}`,
                cancelled: `Active until ${this.subscription.attributes.ends_at}`,
            }[this.subscription.attributes.status]
        },
        price() {
            return {
                month: `${this.subscription.relationships.plan.data.attributes.price} Per Month`,
                year: `${this.subscription.relationships.plan.data.attributes.price} Per Year`,
            }[this.subscription.relationships.plan.data.attributes.interval]
        },
    },
    data() {
        return {
            limitations: [],
        }
    },
    created() {
        Object.entries(this.user.data.meta.limitations).map(([key, item]) => {
            let payload = {
                color: {
                    max_storage_amount: 'warning',
                    max_team_members: 'purple',
                },
                message: {
                    max_storage_amount: `Total ${item.use} of ${item.total} Used`,
                    max_team_members: `Total ${item.use} of ${item.total} Members`,
                },
                title: {
                    max_storage_amount: `Storage`,
                    max_team_members: `Team Members`,
                },
            }

            this.limitations.push({
                message: payload.message[key],
                distribution: [
                    {
                        progress: item.percentage,
                        color: payload.color[key],
                        title: payload.title[key],
                    },
                ],
            })
        })
    },
}
</script>
