<template>
    <div v-if="!hasSubscription" class="card shadow-card">
        <FormLabel>
            {{ $t('subscription') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
            {{ $t('free_plan') }}
        </b>

        <b v-if="$store.getters.config.allowed_payments" class="mb-3 mb-8 block text-sm text-gray-400">
            {{ $t('upgrade_to_get_more') }}
        </b>

        <ButtonBase
            v-if="$store.getters.config.allowed_payments"
            @click.native="$openSubscribeOptions"
            type="submit"
            button-style="theme"
            class="mt-4 w-full"
        >
            {{ $t('upgrade_your_account') }}
        </ButtonBase>
    </div>
</template>

<script>
import InfoBox from '../UI/Others/InfoBox'
import FormLabel from '../UI/Labels/FormLabel'
import ButtonBase from '../UI/Buttons/ButtonBase'

export default {
    name: 'UserEmptySubscription',
    components: {
        ButtonBase,
        FormLabel,
        InfoBox,
    },
    computed: {
        hasSubscription() {
            return this.$store.getters.user.data.relationships.subscription
        },
    },
}
</script>
