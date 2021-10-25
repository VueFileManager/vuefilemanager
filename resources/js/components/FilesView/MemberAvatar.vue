<template>
	<div class="user-preview">
		<img
			:style="{width: size + 'px', height: size + 'px'}"
			v-if="member.data.attributes.avatar"
			:src="member.data.attributes.avatar"
			:alt="member.data.attributes.name"
			class="rounded-xl border-4 border-white"
		>
		<TypedAvatar
			v-else
			:size="size"
			:letter="member.data.attributes.name.substr(0, 1)"
			:color="member.data.attributes.color"
		/>
	</div>
</template>
<script>
	import TypedAvatar from "../Others/TypedAvatar"

	export default {
		name: 'MemberAvatar',
		props: [
			'member',
			'size',
		],
		components: {
			TypedAvatar,
		},
	}
</script>
