<template>
    <div class="empty-page" v-if="isLoading || isEmpty">
        <div class="empty-state">
			<div v-if="!isLoading" class="text-content">
				<slot></slot>
            </div>
            <div v-else class="text-content">
                <Spinner />
            </div>
        </div>
    </div>
</template>

<script>
	import Spinner from '/resources/js/components/FilesView/Spinner'
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

	.empty-page {
		margin-top: 85px;

		.empty-state {
			margin: 0 auto;
			padding-left: 15px;
			padding-right: 15px;
		}
	}

	.text-content {
		text-align: center;
		margin: 30px 0;

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
	}

	.dark {
		.text-content {

			.title {
				color: $dark_mode_text_primary;
			}

			.description {
				color: $dark_mode_text_secondary;
			}
		}
	}
</style>
