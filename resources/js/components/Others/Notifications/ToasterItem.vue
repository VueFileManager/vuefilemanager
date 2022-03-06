<template>
    <transition appear name="fade">
        <div
            v-if="isActive"
            class="relative mt-4 w-full overflow-hidden rounded-xl bg-opacity-80 p-4 shadow-lg backdrop-blur-lg backdrop-filter md:w-96"
            :class="{
                'bg-red-50 dark:bg-2x-dark-foreground': isDanger,
                'bg-green-50 dark:bg-2x-dark-foreground': isSuccess,
            }"
        >
            <!--Content-->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div>
                        <check-icon v-if="isSuccess" size="22" class="vue-feather dark:text-green-600 text-green-600" />
                        <x-icon v-if="isDanger" size="22" class="vue-feather dark:text-red-600 text-red-600" />
                    </div>

                    <p
                        class="px-4 font-bold"
                        :class="{
                            'text-green-600': isSuccess,
                            'text-red-600': isDanger,
                        }"
                    >
                        {{ item.message }}
                    </p>
                </div>

                <div @click="isActive = false" class="cursor-pointer p-2">
                    <x-icon size="16" class="vue-feather text-black opacity-50 dark:text-white" />
                </div>
            </div>

            <!--Progress bar-->
            <div class="absolute bottom-0 left-0 right-0">
                <span
                    class="bar-animation block h-1 w-0"
                    :class="{
                        'bg-green-400': isSuccess,
                        'bg-red-400': isDanger,
                    }"
                ></span>
            </div>
        </div>
    </transition>
</template>

<script>
import { XIcon, CheckIcon } from 'vue-feather-icons'

export default {
    components: {
        CheckIcon,
        XIcon,
    },
    props: ['item'],
    computed: {
        isDanger() {
            return this.item.type === 'danger'
        },
        isSuccess() {
            return this.item.type === 'success'
        },
    },
    data() {
        return {
            isActive: 1,
        }
    },
    created() {
        setTimeout(() => (this.isActive = 0), 6000)
    },
}
</script>

<style lang="scss" scoped>
.bar-animation {
    animation: progressbar 6s linear;
}

@keyframes progressbar {
    0% {
        width: 0;
    }
    100% {
        width: 100%;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: 0.3s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
