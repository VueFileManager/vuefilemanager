<template>
    <div class="page-tab">
        <div id="loader" v-show="isLoading">
            <Spinner />
        </div>
        <slot v-show="!isLoading" />
    </div>
</template>

<script>
import Spinner from '../UI/Others/Spinner'

export default {
    name: 'PageTab',
    props: ['isLoading'],
    components: {
        Spinner,
    },
}
</script>