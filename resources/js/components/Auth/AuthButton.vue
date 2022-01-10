<template>
    <button class="group flex items-center border-2 border-black rounded-lg inline-block mx-auto px-7 py-2.5 whitespace-nowrap">
        <span class="font-extrabold text-lg pr-1">
			{{ text }}
		</span>
		<refresh-cw-icon v-if="loading" size="20" class="vue-feather text-theme -mr-1 sync-alt"/>
		<chevron-right-icon v-if="! loading && icon" size="20" class="vue-feather text-theme -mr-1"/>
    </button>
</template>

<script>
    import { ChevronRightIcon, RefreshCwIcon } from 'vue-feather-icons'

    export default {
        name: 'AuthContent',
        props: [
			'loading',
			'icon',
			'text'
		],
		components: {
			ChevronRightIcon,
			RefreshCwIcon,
		},
        data() {
            return {
                isVisible: false,
            }
        },
        created() {
            this.isVisible = this.visible
        }
    }
</script>

<style scoped lang="scss">

    .sync-alt {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
