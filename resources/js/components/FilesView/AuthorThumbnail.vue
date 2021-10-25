<template>
	<div class="absolute -right-1 -bottom-1">
		<img
			v-if="item.data.relationships.user.data.attributes.avatar"
			class="w-5 h-5 rounded"
			style="outline: 2px solid white;"
			:src="item.data.relationships.user.data.attributes.avatar"
			:alt="item.data.relationships.user.data.attributes.name"
		>
		<TypedAvatar
			v-else
			:size="22"
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
		props: [
			'item',
		]
	}
</script>