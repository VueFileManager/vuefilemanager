<template>
	<div>
		<!--Custom content-->
		<slot></slot>

		<!--Default application logo-->
		<div v-if="! $slots.default">
			<img v-if="config.app_logo" class="logo mx-auto" :src="$getImage(config.app_logo)" :alt="config.app_name">
			<b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>
		</div>

		<h1>{{ title }}</h1>
		<h2>{{ description }}:</h2>
	</div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
	name: 'Headline',
	props: [
		'description',
		'title',
	],
	computed: {
		...mapGetters([
			'config',
		])
	}
}
</script>

<style lang="scss">
	@import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';

	.logo {
	  width: 120px;
	  margin-bottom: 20px;
	}

	h1 {
	  @include font-size(34);
	  font-weight: 800;
	  line-height: 1.2;
	  margin-bottom: 2px;
	  color: $text;
	}

	h2 {
	  @include font-size(23);
	  font-weight: 500;
	  margin-bottom: 50px;
	  color: $text;
	}

	@media only screen and (max-width: 690px) {

		h1 {
			@include font-size(30);
		}

		h2 {
			@include font-size(21);
		}
	}

	@media only screen and (max-width: 490px) {

		h1 {
			@include font-size(22);
		}

		h2 {
			@include font-size(18);
		}
	}
</style>