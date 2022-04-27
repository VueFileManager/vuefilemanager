<template>
    <div
		v-if="toasters.length"
		class="fixed bottom-4 right-4 left-4 z-[55] sm:w-[360px] sm:left-auto lg:bottom-8 lg:right-8"
	>
		<ToasterWrapper
			v-for="(toaster, i) in toasters"
			:key="i"
			class="mt-4 overflow-hidden rounded-xl shadow-xl"
			:bar-color="getToasterColor(toaster)"
		>
        	<Toaster :item="toaster" />
        </ToasterWrapper>
    </div>
</template>

<script>
import ToasterWrapper from './ToasterWrapper'
import {events} from '../../bus'
import Toaster from './Toaster'

export default {
	components: {
		ToasterWrapper,
		Toaster,
	},
	data() {
		return {
			toasters: [],
		}
	},
	methods: {
		getToasterColor(toaster) {
			return {
				danger: 'bg-red-400',
				success: 'bg-green-400',
			}[toaster.type]
		}
	},
	created() {
		events.$on('toaster', (toaster) => this.toasters.push(toaster))
	},
}
</script>
