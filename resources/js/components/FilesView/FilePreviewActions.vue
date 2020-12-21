<template>
	<div v-if="filteredFiles.length > 1">
        <div @click.prevent="prev" class="prev">
		    <chevron-left-icon size="17"></chevron-left-icon>
        </div>

        <div @click.prevent="next" class="next">
		    <chevron-right-icon size="17"></chevron-right-icon>
        </div>
	</div>
</template>

<script>
import { events } from '@/bus'
import { mapGetters } from 'vuex'
import { ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons'
export default {
	name: 'FilePreviewActions',
	components: {
		ChevronLeftIcon,
		ChevronRightIcon
	},
	computed: {
		...mapGetters(['fileInfoDetail', 'data']),

		filteredFiles() {
			let filteredData = []
			this.data.filter((element) => {
				if (element.type == this.fileInfoDetail[0].type) {
					filteredData.push(element)
				}
			})
			return filteredData
		}
	},

	methods: {
		next: function() {
			events.$emit('filePreviewAction:next')
		},
		prev: function() {
			events.$emit('filePreviewAction:prev')
		}
	}
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
.prev,
.next {
	cursor: pointer;
	position: absolute;
	top: 53.5%;
	display: flex;
	justify-content: center;
	color: $text;
	border-radius: 50%;
	text-decoration: none;
	user-select: none;
	filter: drop-shadow(0px 1px 0 rgba(255, 255, 255, 1));
    padding: 10px;
}
.next {
	right: 0;
}
.prev {
	left: 0;
}

@media (prefers-color-scheme: dark) {
	.prev,
	.next {
		color: $light-text;
		filter: drop-shadow(0px 1px 0 rgba(17, 19, 20, 1));
	}
}
</style>