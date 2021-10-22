<template>
	<div class="user-preview">
		<img
			v-if="item.data.relationships.user.data.attributes.avatar"
			class="user-image"
			:src="item.data.relationships.user.data.attributes.avatar"
			alt="item.data.relationships.user.data.attributes.name"
		>
		<TypedAvatar
			v-else
			:size="24"
			:letter="item.data.relationships.user.data.attributes.name.substr(0, 1)"
			:color="item.data.relationships.user.data.attributes.color"
		/>
	</div>
</template>
<script>
	import TypedAvatar from "../Others/TypedAvatar"

	export default {
		name: 'AuthorThumbnail',
		components: {
			TypedAvatar
		},
		props: {
			item: {type: Function}
		}
	}
</script>
<style scoped lang="scss">
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

.user-preview {
	position: absolute;
	right: -5px;
	bottom: -5px;

	.user-image {
		width: 22px;
		height: 22px;
		outline: 3px solid white;
		border-radius: 6px;
	}
}

</style>