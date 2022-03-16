<template>
    <transition appear name="fade">
        <div
            class="relative w-full overflow-hidden rounded-xl bg-opacity-80 p-4 shadow-lg backdrop-blur-2xl"
            :class="{
                'bg-red-50 dark:bg-2x-dark-foreground': item.type === 'danger',
                'bg-green-50 dark:bg-2x-dark-foreground': item.type === 'success',
            }"
        >
            <!--Content-->
            <div class="flex items-center justify-between">
                <div class="flex items-start">
					<check-icon v-if="item.type === 'success'" size="22" class="vue-feather dark:text-green-600 text-green-600" />
					<x-icon v-if="item.type === 'danger'" size="22" class="vue-feather dark:text-red-600 text-red-600" />

                    <p
                        class="px-4 font-bold"
                        :class="{
                            'text-green-600': item.type === 'success',
                            'text-red-600': item.type === 'danger',
                        }"
                    >
                        {{ item.message }}
                    </p>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import { CheckIcon, XIcon } from 'vue-feather-icons'

export default {
	name: 'Toaster',
    components: {
        CheckIcon,
		XIcon,
    },
    props: [
		'barColor',
		'item',
	],
}
</script>
