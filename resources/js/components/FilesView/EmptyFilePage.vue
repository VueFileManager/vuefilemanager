<template>
    <div class="flex items-center justify-center h-full" v-if="isLoading || isEmpty">

		<!--Show message for user-->
		<div v-if="!isLoading" class="text-content text-center">
			<slot></slot>
		</div>

		<!--Show spinner when loading content-->
		<div v-else class="sm:relative fixed top-0 bottom-0">
			<Spinner />
		</div>
    </div>
</template>

<script>
	import Spinner from "./Spinner";
	import {mapGetters} from 'vuex'

	export default {
		name: 'EmptyFilePage',
		components: {
			Spinner,
		},
		computed: {
			...mapGetters([
				'isLoading',
				'entries',
			]),
			isEmpty() {
				return this.entries && this.entries.length === 0
			}
		}
	}
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';

	.title {
		@include font-size(20);
		color: $text;
		font-weight: 700;
		margin: 0;
	}

	.description {
		@include font-size(13);
		color: $text-muted;
		margin-bottom: 20px;
		display: block;
	}

	.dark {

		.title {
			color: $dark_mode_text_primary;
		}

		.description {
			color: $dark_mode_text_secondary;
		}
	}
</style>
