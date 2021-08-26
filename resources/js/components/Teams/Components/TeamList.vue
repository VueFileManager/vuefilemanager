<template>
	<ul class="member-list">
		<li v-if="Object.values(members).length > 0" v-for="(member, i) in members" :key="i" class="member-item">
			<div @click="deleteMember(member)" class="terminate">
				<x-icon size="14" class="close-icon" />
			</div>
			<div class="member-preview">
				<div class="avatar">
					<img :src="member.settings ? member.settings.avatar : '/assets/images/default-avatar.png'">
				</div>
				<div class="info">
					<b class="name">{{ member.settings ? member.settings.name : member.email }}</b>
					<span v-if="member.settings" class="email">{{ member.email }}</span>
				</div>
			</div>
			<div class="action">
				<PermissionToggleButton @input="updateMemberPermission(member, $event)" :item="member" />
			</div>
		</li>
		<li v-if="Object.values(members).length === 0">
			<p class="input-help">{{ $t('Please add some member into your Team Folder.') }}</p>
		</li>
	</ul>
</template>

<script>
	import PermissionToggleButton from "./PermissionToggleButton";
	import {XIcon} from 'vue-feather-icons'

	export default {
		name: "TeamList",
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
		max-height: 270px;
		overflow-y: scroll;
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

	.dark-mode {

		.member-item .info .email {
			color: $dark_mode_text_secondary;
		}
	}
</style>