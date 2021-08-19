<template>
	<ul class="member-list">
		<li v-for="(member, i) in members" :key="i" class="member-item">
			<div @click="deleteMember(member)" class="terminate">
				<x-icon size="14" class="close-icon" />
			</div>
			<div class="member-preview">
				<div class="avatar">
					<img :src="member.avatar">
				</div>
				<div class="info">
					<b class="name">{{ member.name ? member.name : member.email }}</b>
					<span v-if="member.name" class="email">{{ member.email }}</span>
				</div>
			</div>
			<div class="action">
				<PermissionToggleButton @input="updateMemberPermission(member, $event)" :item="member" />
			</div>
		</li>
	</ul>
</template>

<script>
	import PermissionToggleButton from "./PermissionToggleButton";
	import {XIcon} from 'vue-feather-icons'

	export default {
		name: "TeamMemberList",
		props: [
			'value',
		],
		components: {
			PermissionToggleButton,
			XIcon,
		},
		data() {
			return {
				members: undefined
			}
		},
		methods: {
			updateMemberPermission(member, value) {
				this.members.map(e => e === member ? e.permission = value : e)

				this.emitMembers()
			},
			deleteMember(member) {
				this.members = this.members.filter(m => m !== member)

				this.emitMembers()
			},
			emitMembers() {
				this.$emit('input', this.members)
			}
		},
		created() {
			this.members = this.value
		}
	}
</script>

<style lang="scss" scoped>
    @import "resources/sass/vuefilemanager/_inapp-forms.scss";
	@import '/resources/sass/vuefilemanager/_forms';

	.member-list {
		margin-bottom: 20px;
	}

	.member-item {
		display: flex;
		align-items: center;
		padding: 8px 0;

		.terminate {
			line-height: 0;
			cursor: pointer;

			line {
				color: $light_text;
			}
		}

		.member-preview {
			display: flex;
			align-items: center;

			.avatar {
				padding: 0 12px;

				img {
					width: 38px;
					height: 38px;
					border-radius: 8px;
					object-fit: cover;
					display: block;
				}
			}

			.info {

				.name {
					@include font-size(15);
					font-weight: 700;
					display: block;
					max-width: 190px;
					overflow: hidden;
					text-overflow: ellipsis;
				}

				.email {
					@include font-size(12);
					color: $text-muted;
					display: block;
				}
			}
		}

		.action {
			margin-left: auto;
		}
	}
</style>