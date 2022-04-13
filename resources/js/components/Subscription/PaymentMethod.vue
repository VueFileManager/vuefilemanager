<template>
    <div class="flex items-center justify-between border-b border-dashed border-light py-4 dark:border-opacity-5">
        <div>
            <img :src="$getPaymentLogo(driver)" :alt="driver" class="h-6" />
            <small class="block pt-2 text-xs leading-4 dark:text-gray-500 text-gray-500">
                {{ description }}
            </small>
        </div>
        <div v-if="$slots.default" class="bg-theme-200 relative inline-block rounded-lg px-3 py-1">
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    name: 'PaymentMethod',
    props: ['description', 'driver'],
}
</script>
