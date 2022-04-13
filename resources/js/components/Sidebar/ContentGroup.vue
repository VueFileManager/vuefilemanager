<template>
    <div class="ml-6 mb-6" :class="{ 'is-collapsed': !isVisible, collapsable: canCollapse }">
        <div @click="hideGroup" class="mb-2 flex items-center justify-between">
            <small class="text-xs font-bold text-gray-400 dark:text-gray-600">
                {{ title }}
            </small>
            <chevron-up-icon
                v-if="canCollapseWrapper"
                size="12"
                class="vue-feather mr-5 transform cursor-pointer text-gray-300"
                :class="{ 'rotate-180': !isVisible }"
            />
        </div>

        <slot v-if="isVisible" />
    </div>
</template>

<script>
import TextLabel from '../UI/Labels/TextLabel'
import { ChevronUpIcon } from 'vue-feather-icons'

export default {
    name: 'ContentGroup',
    props: ['canCollapse', 'title', 'slug'],
    components: {
        ChevronUpIcon,
        TextLabel,
    },
    data() {
        return {
            isVisible: true,
            canCollapseWrapper: false,
        }
    },
    methods: {
        hideGroup() {
            if (!this.canCollapseWrapper) return

            this.isVisible = !this.isVisible
            localStorage.setItem('panel-group-' + this.slug, this.isVisible)
        },
    },
    created() {
        if (this.canCollapse) {
            let savedVisibility = localStorage.getItem('panel-group-' + this.slug)

            this.isVisible = savedVisibility ? !!JSON.parse(String(savedVisibility).toLowerCase()) : true
            this.canCollapseWrapper = true
        }
    },
}
</script>
