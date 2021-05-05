<template>
    <div class="empty-page" v-if="isLoading || isEmpty">
        <div class="empty-state">

			<!--Base invoice browser empty message-->
            <div class="text-content" v-if="$isThisLocation(['regular-invoice', 'advance-invoice']) && !isLoading">
                <h1 class="title">
					Create Your First Invoice
				</h1>

                <p class="description">
					It's very easy, just click on the button below.
				</p>

                <ButtonBase @click.native="createInvoice" button-style="theme" class="button">
					{{ buttonTitle }}
				</ButtonBase>
            </div>
            <div class="text-content" v-if="$isThisLocation('clients') && !isLoading">
                <h1 class="title">
					Create Your First Client
				</h1>

                <p class="description">
					It's very easy, just click on the button below.
				</p>

                <ButtonBase @click.native="createClient" button-style="theme" class="button">
					Create Client
				</ButtonBase>
            </div>

			<!--Spinner-->
            <div class="text-content" v-if="isLoading">
                <Spinner />
            </div>
        </div>
    </div>
</template>

<script>
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import Spinner from '@/components/FilesView/Spinner'
	import {mapGetters} from 'vuex'

	export default {
		name: 'EmptyFilePage',
		props: [
			'title',
			'description',
		],
		components: {
			ButtonBase,
			Spinner,
		},
		computed: {
			...mapGetters([
				'currentFolder',
				'isLoading',
				'entries',
			]),
			isEmpty() {
				return this.entries && this.entries.length == 0
			},
			buttonTitle() {
				return this.$isThisLocation('regular-invoice') ? 'Create Regular Invoice' : 'Create Advance Invoice'
			}
		},
		methods: {
			createInvoice() {
				this.$router.push({name: 'CreateInvoice', query: {type: this.currentFolder.location}})
			},
			createClient() {
				this.$router.push({name: 'CreateClient'})
			}
		}
	}
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';

	.empty-page {
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		top: 0;
		margin-top: 20px;
		display: flex;
		align-items: center;

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

		.button {
			margin-left: auto;
			margin-right: auto;
		}
	}

	@media (prefers-color-scheme: dark) {
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
