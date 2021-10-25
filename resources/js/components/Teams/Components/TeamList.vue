<template>
	<ul class="member-list">
		<li v-if="Object.values(members).length > 0 && entry.id !== user.data.id" v-for="(entry, i) in members" :key="i" class="member-item">
			<div @click="deleteMember(entry)" class="terminate">
				<x-icon size="14" class="close-icon" />
			</div>
			<div class="member-preview">
				<div class="avatar">
					<MemberAvatar
						class="mr-3 ml-2"
						:is-border="true"
						:size="44"
						:member="$mapIntoMemberResource(entry)"
					/>
				</div>
				<div v-if="entry.type === 'member'" class="info">
					<b class="title">
						{{ entry.name }}
					</b>
					<span class="subtitle">
						{{ entry.email }}
					</span>
				</div>
				<div v-if="entry.type === 'invitation'" class="info">
					<b class="title">
						{{ entry.email }}
					</b>
					<span v-if="entry.id" class="subtitle">
						{{ $t('Waiting for accept invitation...') }}
					</span>
				</div>
			</div>
			<div class="action">
				<PermissionToggleButton @input="updateMemberPermission(entry, $event)" :item="entry" />
			</div>
		</li>
	</ul>
</template>

<script>
	import PermissionToggleButton from "./PermissionToggleButton"
	import MemberAvatar from "../../FilesView/MemberAvatar";
	import {XIcon} from 'vue-feather-icons'
	import {mapGetters} from "vuex";

	export default {
		name: "TeamList",
		props: [
			'value',
		],
		computed: {
			...mapGetters([
				'user'
			])
		},
		components: {
			PermissionToggleButton,
			MemberAvatar,
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

			.info {

				.title {
					@include font-size(15);
					font-weight: 700;
					display: block;
					max-width: 190px;
					overflow: hidden;
					text-overflow: ellipsis;
				}

				.subtitle {
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