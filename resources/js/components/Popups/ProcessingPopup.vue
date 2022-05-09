<template>
    <transition name="popup">
        <div
            v-if="processingPopup"
            class="popup fixed top-0 left-0 right-0 bottom-0 z-50 grid h-full overflow-y-auto p-10 lg:absolute"
        >
            <div
                class="fixed top-0 bottom-0 left-0 right-0 z-10 m-auto w-full bg-white shadow-xl dark:bg-dark-foreground md:relative md:w-[490px] md:rounded-xl"
            >
                <div
                    class="flex h-full -translate-y-7 transform items-center justify-center px-8 text-center md:translate-y-0"
                >
                    <div>
                        <div class="relative pb-16 pt-10">
                            <Spinner />
                        </div>

                        <h1 v-if="processingPopup.title" class="mb-2 text-2xl font-bold">
                            {{ processingPopup.title }}
                        </h1>
                        <p v-if="processingPopup.message" class="mb-4 text-sm">
                            {{ processingPopup.message }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import Spinner from '../UI/Others/Spinner'
import { mapGetters } from 'vuex'
import PopupWrapper from './Components/PopupWrapper'

export default {
    name: 'ProcessingPopup',
    components: {
        PopupWrapper,
        Spinner,
    },
    computed: {
        ...mapGetters(['processingPopup']),
    },
}
</script>
