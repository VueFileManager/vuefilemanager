<template>
    <div v-if="!hasSubscription" class="card shadow-card">
        <FormLabel>
            {{ $t('Subscription') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
            {{ $t('Free Plan') }}
        </b>

        <b class="mb-3 mb-8 block text-sm text-gray-400">
            {{ $t('1GB Free storage space with 5 Team members') }}
        </b>

        <ButtonBase @click.native="$openUpgradeOptions" type="submit" button-style="theme" class="mt-4 w-full">
            {{ $t('Upgrade Your Account') }}
        </ButtonBase>
    </div>
</template>

<script>
import InfoBox from '../Others/Forms/InfoBox'
import FormLabel from '../Others/Forms/FormLabel'
import ButtonBase from '../FilesView/ButtonBase'

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
