<template>
    <PageTab>
        <PageTabGroup>
            <FormLabel>{{ $t('user_token.title') }}</FormLabel>
        </PageTabGroup>
    </PageTab>
</template>

<script>
import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
import FormLabel from '@/components/Others/Forms/FormLabel'
import PageTab from '@/components/Others/Layout/PageTab'

    export default {
        name: 'AccessToken',
        components: {
            PageTabGroup,
            FormLabel,
            PageTab
        },
        data () {
            return {
                tokens: undefined
            }
        },
        created () {
            axios.
                get('/api/user/tokens')
                .then(response => {
                   this.tokens = response.data
                })
        }
        
    }
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

</style>
