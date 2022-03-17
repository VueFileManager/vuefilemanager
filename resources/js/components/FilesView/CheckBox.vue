<template>
    <div>
        <div
            class="flex h-5 w-5 items-center justify-center rounded-md"
            :class="{
                'bg-theme': isClicked,
                'bg-light-background dark:bg-4x-dark-foreground': !isClicked,
            }"
            @click="changeState"
        >
            <CheckIcon v-if="isClicked" class="vue-feather text-white" size="17" />
        </div>
    </div>
</template>

<script>
import { CheckIcon } from 'vue-feather-icons'

export default {
    name: 'CheckBox',
    props: ['isClicked'],
    components: {
        CheckIcon,
    },
    data() {
        return {
            isSwitched: undefined,
        }
    },
    methods: {
        changeState() {
            this.isSwitched = !this.isSwitched
            this.$emit('input', this.isSwitched)
        },
    },
    mounted() {
        this.isSwitched = this.isClicked
    },
}
</script>
