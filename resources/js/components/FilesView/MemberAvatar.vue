<template>
	<div>
		<img
			:style="{width: size + 'px', height: size + 'px'}"
			v-if="member.data.attributes.avatar"
			:src="member.data.attributes.avatar"
			:alt="member.data.attributes.name"
			:class="[borderRadius, {'border-3 border-white dark:border-dark-background': isBorder}]"
			class=""
		>
		<div
			v-else
			class="flex items-center justify-center"
			:class="[borderRadius, {'border-3 border-white': isBorder}]"
			:style="{width: size + 'px', height: size + 'px', background: member.data.attributes.color ? member.data.attributes.color : '#f4f5f6'}"
		>
			<span :class="fontSize" class="uppercase font-extrabold">
				{{ letter }}
			</span>
		</div>
	</div>
</template>
<script>

	export default {
		name: 'MemberAvatar',
		props: [
			'isBorder',
			'member',
			'size',
		],
		computed: {
			letter() {
				return this.member.data.attributes.name.substr(0, 1)
			},
			borderRadius() {
				return this.size > 32 ? 'rounded-xl' : 'rounded-lg'
			},
			fontSize() {
				return this.size > 32 ? 'text-base' : 'text-sm'
			},
		}
	}
</script>
