<template>
    <div
        class="flex items-center justify-between rounded-lg bg-light-background py-3 px-2 dark:bg-2x-dark-foreground md:px-4"
    >
        <div class="flex items-center">
            <img :src="`/assets/gateways/${card.data.attributes.brand}.svg`" alt="" class="mr-3 h-5 rounded" />
            <b class="whitespace-nowrap text-sm font-bold capitalize leading-none">
                {{ card.data.attributes.brand }} ••••
                {{ card.data.attributes.last4 }}
            </b>
        </div>
        <b class="text-sm font-bold leading-none"> {{ $t('expires') }} {{ card.data.attributes.expiration }} </b>
        <Trash2Icon @click="deleteCreditCard(card.data.id)" size="15" class="cursor-pointer" />
    </div>
</template>
<script>
import { Trash2Icon } from 'vue-feather-icons'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'PaymentCard',
    components: {
        Trash2Icon,
    },
    props: ['card'],
    methods: {
        deleteCreditCard(id) {
            events.$emit('confirm:open', {
                title: this.$t('want_to_delete_card_title'),
                message: this.$t('want_to_delete_card_description'),
                action: {
                    id: id,
                    operation: 'delete-credit-card',
                },
            })
        },
    },
    created() {
        events.$on('action:confirmed', (data) => {
            if (data.operation === 'delete-credit-card')
                axios
                    .delete(`/api/stripe/credit-cards/${data.id}`)
                    .then(() => {
                        this.$store.dispatch('getAppData')

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('credit_card_deleted'),
                        })
                    })
                    .catch(() => this.$isSomethingWrong())
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
