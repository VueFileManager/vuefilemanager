<template>
    <div class="ml-6 mb-6" :class="{'is-collapsed': ! isVisible, 'collapsable': canCollapse}">
        <div @click="hideGroup" class="flex items-center justify-between mb-2">
            <small class="text-xs font-bold dark:text-gray-700 text-gray-400">
				{{ title }}
			</small>
            <chevron-up-icon v-if="canCollapseWrapper" size="12" class="mr-5 cursor-pointer vue-feather text-gray-300 transform" :class="{'rotate-180': ! isVisible}" />
        </div>

		<slot v-if="isVisible" />
    </div>
</template>

<script>
    import TextLabel from "../Others/TextLabel";
    import { ChevronUpIcon } from 'vue-feather-icons'

    export default {
        name: 'ContentGroup',
        props: [
			'canCollapse',
			'title',
			'slug'
		],
        components: {
            ChevronUpIcon,
            TextLabel,
        },
        data() {
            return {
                isVisible: true,
                canCollapseWrapper: false
            }
        },
        methods: {
            hideGroup() {
                if (! this.canCollapseWrapper)
                    return

                this.isVisible = !this.isVisible
                localStorage.setItem('panel-group-' + this.slug, this.isVisible)
            }
        },
        created() {

            if (this.canCollapse) {

                let savedVisibility = localStorage.getItem('panel-group-' + this.slug)

                this.isVisible = savedVisibility ? !!JSON.parse(String(savedVisibility).toLowerCase()) : true
                this.canCollapseWrapper = true
            }
        }
    }
</script>
